<?php

namespace MixCode\Payment;

use MixCode\Exceptions\PaymentDriverNotFound;
use MixCode\Payment\Contracts\Item;
use MixCode\Payment\Contracts\Driver;

class PaymentGateway
{    
    /**
     * Payment driver name
     *
     * @var string
     */
    public $driverName;

    /**
     * Payment Driver Instance
     *
     * @var \MixCode\Payment\Contracts\Driver
     */
    protected $driver;

    /**
     * Payment Drivers
     *
     * @var array
     */
    protected $drivers = [
        'paypal' => \MixCode\Payment\Drivers\PayPal\PayPalDriver::class,
        'tap' => \MixCode\Payment\Drivers\Tap\TapDriver::class,
    ];
    
    /**
     * Initialize Driver
     */
    public function __construct($driver = 'paypal')
    {
        throw_unless(isset($this->drivers[$driver]), new PaymentDriverNotFound("Payment Driver \"{$driver}\" was not found in registered drivers"));

        $this->driverName = $driver;

        $driverInstance = $this->drivers[$this->driverName];

        $this->driver = new $driverInstance();
    }

    /**
     * Add new item to payment items
     *
     * @param \MixCode\Payment\Contracts\Item $item item to be added in payment items
     * @return \MixCode\Payment\PaymentGateway
     */
    public function addItem(Item $item): \MixCode\Payment\PaymentGateway
    {
        $this->driver->addItem($item);
        
        return $this;
    }

    /**
     * Add success return url to payment
     *
     * @param string|callable $success_return_url success return url to be added in payment
     * @return \MixCode\Payment\PaymentGateway
     */
    public function addSuccessReturnUrl($success_return_url): \MixCode\Payment\PaymentGateway
    {
        $this->driver->addSuccessReturnUrl($success_return_url);
        
        return $this;
    }

    /**
     * Add fail return url to payment
     *
     * @param string|callable $fail_return_url fail return url to be added in payment
     * @return \MixCode\Payment\PaymentGateway
     */
    public function addFailReturnUrl($fail_return_url): \MixCode\Payment\PaymentGateway
    {
        $this->driver->addFailReturnUrl($fail_return_url);
        
        return $this;
    }

    /**
     * Add total price to payment
     *
     * @param float|callable $total total price to be added in payment
     * @return \MixCode\Payment\PaymentGateway
     */
    public function addTotal($total): \MixCode\Payment\PaymentGateway
    {
        $this->driver->addTotal($total);
        
        return $this;
    }

    /**
     * Add Payment Additional Data
     *
     * @param array $data
     * @return \MixCode\Payment\PaymentGateway
     */
    public function addAdditionalData(array $data): \MixCode\Payment\PaymentGateway
    {
        $this->driver->addAdditionalData($data);
        
        return $this;
    }

    /**
     * Request payment From Payment Gateway
     *
     * @return \MixCode\Payment\PaymentGateway
     */
    public function requestPayment(): \MixCode\Payment\PaymentGateway
    {
        $this->driver->requestPayment();

        return $this;
    }

    /**
     * Load payment From paypal By PaymentId given after Request Payment
     *
     * @param string $paymentId
     * @return \MixCode\Payment\PaymentGateway
     */
    public function loadPayment(string $paymentId): \MixCode\Payment\PaymentGateway
    {
        $this->driver->loadPayment($paymentId);

        return $this;
    }    

    /**
     * Complete Payment after Getting payment Details
     *
     * @param string $token token
     * @param string $payer_id payer id
     * @return \MixCode\Payment\PaymentGateway
     */
    public function completePayment(string $token, string $payer_id): \MixCode\Payment\PaymentGateway
    {
        $this->driver->completePayment($token, $payer_id);

        return $this;
    }

    /**
     * Check whether checkout completed or not
     *
     * @return bool
     */
    public function isPaymentComplete(): bool
    {
        return $this->driver->isPaymentComplete();
    }

    /**
     * Check whether request failed or not
     *
     * @return bool
     */
    public function haveAnyErrors(): bool
    {
        return $this->driver->haveAnyErrors();
    }
    
    /**
     * Get Error Message
     *
     * @return string
     */
    public function getErrorMessage(): ?string
    {
        return $this->driver->getErrorMessage();
    }

    /**
     * Get all payment items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->driver->getItems();
    }

    /**
     * Get payment total price
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->driver->getTotal();
    }    

    /**
     * Get checkout payment redirect url to complete checkout payment
     *
     * @return string
     */
    public function getApprovalLink(): string
    {
        return $this->driver->getApprovalLink();
    }
}