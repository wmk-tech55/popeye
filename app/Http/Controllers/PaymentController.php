<?php

namespace MixCode\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Order;
use MixCode\Payment\PaymentGateway;

class PaymentController extends Controller
{
    public function payment(Request $request, Order $order)
    {
        // Check if auth user is order owner
        abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

        if ($order->isCancelled()) {
            notify('error', trans('site.cant_pay_canceled_order'));

            return back();
        }

        // Check iof Order Is Paid Or Not (if paid abort immediately)
        abort_if($order->isPaid(), Response::HTTP_INTERNAL_SERVER_ERROR, trans('site.payment_already_completed'));

        $order->load(['customer', 'products']);

        $invoiceId = $order->invoice_id;

        $paymentTitle = trans('site.invoice') . ' ' . $invoiceId;

        $payment = new PaymentGateway('tap');

        $order->update(['invoice_id' => $invoiceId]);

        $payment->addSuccessReturnUrl(route('payment.success', $order));
        
        $payment->addTotal($order->total);

        $payment->addAdditionalData([
            'customer' => [
                'first_name'            => optional($order->customer)->full_name,
                'email'                 => optional($order->customer)->email,
            ],
            'reference' => [
                'transaction'           => $invoiceId,
                'order'                 => $invoiceId,
            ],
            'description'               => $paymentTitle,
            'statement_descriptor'      => $paymentTitle,
        ]);

        $payment->requestPayment();

        if ($payment->haveAnyErrors()) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $payment->getErrorMessage());
        }

        return redirect($payment->getApprovalLink());
    }

    public function success(Request $request, Order $order)
    {
        // Check if auth user is order owner
        abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

        // Check iof Order Is Paid Or Not (if paid abort immediately)
        abort_if($order->isPaid(), Response::HTTP_INTERNAL_SERVER_ERROR, trans('site.payment_already_completed'));

        $order->load('products');

        $payment = new PaymentGateway('tap');

        $payment->loadPayment($request->tap_id);

        if ($payment->haveAnyErrors()) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $payment->getErrorMessage());
        }

        if ($payment->isPaymentComplete()) {
            $order->markAsPaid()
                ->payWithTap()
                ->paidOnWebPlatform()
                ->setPaymentId($request->tap_id)
                ->notifyCreatorForOrderPaid()
                ->notifyCustomerForOrderPaid();
        }

        return redirect()->route('payment.complete');
    }

    public function complete()
    {
        return view('order-completed');
    }

    public function cancel()
    {
        abort(Response::HTTP_INTERNAL_SERVER_ERROR, trans('site.payment_error'));
    }
}
