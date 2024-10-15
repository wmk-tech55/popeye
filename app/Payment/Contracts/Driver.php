<?php

namespace MixCode\Payment\Contracts;

interface Driver
{
    /**
     * Add new item to driver payment items
     *
     * @param \MixCode\Payment\Contracts\Item $item item to be added in driver  payment items
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addItem(Item $item): \MixCode\Payment\Contracts\Driver;

    /**
     * Add success return url to driver payment
     *
     * @param string|callable $success_return_url success return url to be added in driver payment
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addSuccessReturnUrl($success_return_url): \MixCode\Payment\Contracts\Driver;

    /**
     * Add fail return url to driver payment
     *
     * @param string|callable $fail_return_url fail return url to be added in driver payment
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addFailReturnUrl($fail_return_url): \MixCode\Payment\Contracts\Driver;

    /**
     * Add total price to driver payment
     *
     * @param float|callable $total total price to be added in driver payment
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addTotal($total): \MixCode\Payment\Contracts\Driver;
    
    /**
     * Add Payment Additional Data
     *
     * @param array $data
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function addAdditionalData(array $data): \MixCode\Payment\Contracts\Driver;

    /**
     * Request payment From paypal
     *
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function requestPayment(): \MixCode\Payment\Contracts\Driver;

    /**
     * Load payment From paypal By PaymentId given after Request Payment
     *
     * @param string $paymentId
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function loadPayment(string $paymentId): \MixCode\Payment\Contracts\Driver;

    /**
     * Complete Payment after Getting payment Details
     *
     * @param string $token token
     * @param string $payer_id payer id
     * @return \MixCode\Payment\Contracts\Driver
     */
    public function completePayment(string $token, string $payer_id): \MixCode\Payment\Contracts\Driver;
        
    /**
     * Check whether checkout completed or not
     *
     * @return bool
     */
    public function isPaymentComplete(): bool;

    /**
     * Check whether request failed or not
     *
     * @return bool
     */
    public function haveAnyErrors(): bool;

    /**
     * Get Error Message
     *
     * @return string
     */
    public function getErrorMessage(): ?string;
    
    /**
     * Get all driver payment items
     *
     * @return mixed
     */
    public function getItems();

    /**
     * Get driver payment total price
     *
     * @return float
     */
    public function getTotal(): float;

    /**
     * Get checkout payment redirect url to complete checkout payment from payload
     *
     * @return string
     */
    public function getApprovalLink(): string;
}