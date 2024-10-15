<?php

namespace MixCode\Payment\Drivers\Tap;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MixCode\Payment\Contracts\Driver;
use MixCode\Payment\Contracts\Item;

class TapDriver implements Driver
{   
    /**
     * Payment Request
     *
     * @var array
     */
    protected $paymentData = [];
    
    /**
     * Payment Client
     *
     * @var object
     */
    protected $payment;
    
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
     * Guzzle Client
     * 
     * @var \GuzzleHttp\Client
     */
    protected $requestClient;

    /**
     * Initialize New Tap Driver
     */
    public function __construct()
    {
        $this->initClient();

        $this->addPaymentData('currency', config('tap-payment.settings.currency'));
        $this->addPaymentData('source', config('tap-payment.settings.source'));
        $this->addPaymentData('receipt', config('tap-payment.settings.receipt'));
        $this->addPaymentData('save_card', config('tap-payment.settings.save_card'));
        $this->addPaymentData('threeDSecure', config('tap-payment.settings.three_d_secure'));
    }

    /**
     * Add new item to driver payment items
     *
     * @param \MixCode\Payment\Contracts\Item $item item to be added in driver  payment items
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addItem(Item $item): \MixCode\Payment\Contracts\Driver
    {
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
        $this->addPaymentData('redirect', ['url' => $this->getProperData($success_return_url)]);
        
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
        $this->addPaymentData('amount', $total);

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
        $this->paymentData = array_merge($this->paymentData, $data);
        
        return $this;
    }

    /**
     * Request payment From tap payments
     *
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function requestPayment(): \MixCode\Payment\Contracts\Driver
    {
 
        $this->payment = $this->call('https://api.tap.company/v2/charges', $this->paymentData);
        
        
        if (strtolower($this->payment->status) !== 'initiated') {
            $this->hasError = true;
            $this->errorMessage = 'Error During Request Payments';
        }
        
        return $this;
    }

    /**
     * Load payment From tap payments By PaymentId given after Request Payment
     *
     * @param string $paymentId
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function loadPayment(string $paymentId): \MixCode\Payment\Contracts\Driver
    {
        $this->payment = $this->call("https://api.tap.company/v2/charges/{$paymentId}", [], 'GET');
         
        if (strtolower($this->payment->status) !== 'captured') {
            
            $this->hasError = true;
            $this->errorMessage = 'Error During Loading Payments Status';

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
        return $this;
    }
        
    /**
     * Check whether checkout completed or not
     *
     * @return bool
     */
    public function isPaymentComplete(): bool
    {
        return strtolower($this->payment->status) === 'captured';
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
        return $this->paymentData['amount'];
    }

    /**
     * Get checkout payment redirect url to complete checkout payment
     *
     * @return string
     */
    public function getApprovalLink(): string
    {
        return $this->payment->transaction->url;
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

    /**
     * Add Payment Data To Payment Request 
     *
     * @param string $payment_data_key
     * @param mixed $payment_data_value
     * @return void
     */
    protected function addPaymentData(string $payment_data_key, $payment_data_value)
    {
        $this->paymentData[$payment_data_key] = $payment_data_value;
    }

    /**
     * Make A request using Guzzle
     *
     * @param string $url
     * @param array $data
     * @param string $method
     * @return object
     */
    protected function call(string $url, array $data = [], string $method = 'POST')
    {
        try {

            $res = $this->requestClient->request($method, $url, ['form_params' => $data]);
        
            return  json_decode($res->getBody()->getContents());
        } catch (GuzzleException $th) {
           
            $this->hasError = true;
            $error = json_decode($th->getResponse()->getBody()->getContents(), true);
            $this->errorMessage = $error['errors'][0]['description'];

            return null;
        }
    }

    /**
     * Initilized Guzzle Client
     *
     * @return void
     */
    protected function initClient()
    {
        $secret_key = config('tap-payment.secret');

        $this->requestClient = new Client([
            'headers' => [
                'Authorization' => "Bearer {$secret_key}",
            ]
        ]);
    }
}