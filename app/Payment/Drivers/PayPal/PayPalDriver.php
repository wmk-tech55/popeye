<?php

namespace MixCode\Payment\Drivers\PayPal;

use MixCode\Payment\Contracts\Driver;
use MixCode\Payment\Contracts\Item;
use PayPal\Api\Amount;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalDriver implements Driver
{   
    /**
     * Payment Items List 
     *
     * @var \PayPal\Api\ItemList
     */
    protected $items;
    
    /**
     * Payment Amount
     *
     * @var \PayPal\Api\Amount
     */
    protected $amount;

    /**
     * Payment transaction
     *
     * @var \PayPal\Api\Transaction
     */
    protected $transaction;

    /**
     * Payment Redirect Urls
     *
     * @var \PayPal\Api\RedirectUrls
     */
    protected $redirectUrls;
    
    /**
     * Payment Client
     *
     * @var \PayPal\Api\Payment
     */
    protected $payment;
    
    /**
     * PayPal CLient
     *
     * @var \PayPal\Rest\ApiContext
     */
    protected $client;

    /**
     * Determine if error happened or not
     *
     * @var bool
     */
    protected $hasError = false;

    /**
     * Error Message
     *
     * @var string
     */
    protected $errorMessage;
    
    /**
     * PayPal Payer
     *
     * @var \PayPal\Api\Payer
     */
    protected $payer;

    /**
     * Initialize New PayPal Driver
     */
    public function __construct()
    {
        $credential = new OAuthTokenCredential(config('paypal.client_id'), config('paypal.secret'));
        $this->client = new ApiContext($credential);
        $this->client->setConfig(config('paypal.settings'));

        $this->payer = new Payer();
        $this->payer->setPaymentMethod('paypal');
        
        $this->items = new ItemList();

        $this->amount = new Amount();
        $this->amount->setCurrency('USD');

        $this->transaction = new Transaction();

        $this->redirectUrls = new RedirectUrls();

        $this->payment = new Payment();
    }

    /**
     * Add new item to driver payment items
     *
     * @param \MixCode\Payment\Contracts\Item $item item to be added in driver  payment items
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addItem(Item $item): \MixCode\Payment\Contracts\Driver
    {
        $this->items->addItem($item->toBase());

        return $this;
    }

    /**
     * Add success return url to driver payment
     *
     * @param string|callable $success_return_url success return url to be added in driver payment
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addSuccessReturnUrl($success_return_url): \MixCode\Payment\Contracts\Driver
    {
        $this->redirectUrls->setReturnUrl($this->getProperData($success_return_url));
        
        return $this;
    }

    /**
     * Add fail return url to driver payment
     *
     * @param string|callable $fail_return_url fail return url to be added in driver payment
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addFailReturnUrl($fail_return_url): \MixCode\Payment\Contracts\Driver
    {
        $this->redirectUrls->setCancelUrl($this->getProperData($fail_return_url));
        
        return $this;
    }

    /**
     * Add total price to driver payment
     *
     * @param float|callable $total total price to be added in driver payment
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addTotal($total): \MixCode\Payment\Contracts\Driver
    {
        $this->amount->setTotal($this->getProperData($total));
        
        return $this;
    }

    /**
     * Add Payment Additional Data
     *
     * @param array $data
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addAdditionalData(array $data): \MixCode\Payment\Contracts\Driver
    {
        return $this;
    }

    /**
     * Request payment From paypal
     *
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function requestPayment(): \MixCode\Payment\Contracts\Driver
    {
        $this->transaction->setAmount($this->amount)->setItemList($this->items);

        $this->payment
            ->setIntent('Sale')
            ->setPayer($this->payer)
            ->setRedirectUrls($this->redirectUrls)
            ->setTransactions([$this->transaction]);

        try {
            $this->payment->create($this->client);
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            $this->errorMessage = optional(json_decode($e->getData()))->message;
            $this->hasError = true;
        }

        return $this;
    }

    /**
     * Load payment From paypal By PaymentId given after Request Payment
     *
     * @param string $paymentId
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function loadPayment(string $paymentId): \MixCode\Payment\Contracts\Driver
    {
        try {
            $this->payment = Payment::get($paymentId, $this->client);
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            $this->errorMessage = optional(json_decode($e->getData()))->message;
            $this->hasError = true;
        }
        
        return $this;
    }

    /**
     * Complete Payment after Getting payment Details
     *
     * @param string $token token
     * @param string $payer_id payer id
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function completePayment(string $token, string $payer_id): \MixCode\Payment\Contracts\Driver
    {
        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);
        
        try {
            $this->payment->execute($execution, $this->client);
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            $this->errorMessage = optional(json_decode($e->getData()))->message;
            $this->hasError = true;
        }

        return $this;
    }
        
    /**
     * Check whether checkout completed or not
     *
     * @return bool
     */
    public function isPaymentComplete(): bool
    {
        return ($this->payment->getState() === 'approved');
    }

    /**
     * Check whether request failed or not
     *
     * @return bool
     */
    public function haveAnyErrors(): bool
    {
        return $this->hasError;
    }

    /**
     * Get Error Message
     *
     * @return string
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
    
    /**
     * Get all driver payment items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get driver payment total price
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->amount->getTotal();
    }

    /**
     * Get checkout payment redirect url to complete checkout payment
     *
     * @return string
     */
    public function getApprovalLink(): string
    {
        return $this->payment->getApprovalLink();
    }

    /**
     * Check if data passed is callback or not and return it's value
     *
     * @param mixed $data
     * @return mixed
     */
    public function getProperData($data)
    {
        return is_callable($data) ? $data($this) : $data;
    }
}