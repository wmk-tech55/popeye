<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Order;

class CashPaymentController extends Controller
{
    public function payment(Order $order)
    {
     
        // Check if auth user is order owner
        abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

        if ($order->isCancelled()) {
            notify('error', trans('site.cant_pay_canceled_order'));

            return back();
        }

        // Check iof Order Is Paid Or Not (if paid abort immediately)
        abort_if($order->isPaid(), Response::HTTP_INTERNAL_SERVER_ERROR, trans('site.payment_already_completed'));
        
        $order->markAsPaid()
            ->payWithCash()
            ->paidOnWebPlatform()
            ->notifyCreatorForOrderPaid()
            ->notifyCustomerForOrderPaid();
         
            return redirect()->route('payment.complete');
    }
}
