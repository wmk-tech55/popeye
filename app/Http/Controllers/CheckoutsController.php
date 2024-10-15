<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Cart;
use MixCode\Order;
use MixCode\PromoCode;
use MixCode\UserPromoCode;
use Illuminate\Support\Str;
use MixCode\Http\Requests\OrdersRequest;
use MixCode\Payment\PaymentGateway;
use MixCode\Product;
use MixCode\ProductOrder;
use MixCode\Utils\UsingSEO;

class CheckoutsController extends Controller
{
    use UsingSEO;

    public function showCheckout(Order $order)
    {

        tap(trans('site.checkout'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name_by_lang . ' ' . $seoTitle]);
        });

        $carts     = $order->getUserCartData();

        $total    =  $carts->map(function ($cart) {
            return $cart['total_price'];
        })->sum();


        $address      = null;
        $street_name  = null;
        $flat_number  = null;
        $floor_number = null;
        $block_number = null;
        $country      = null;
        $city         = null;
        $email        = null;
        $phone        = null;
        $full_name    = null;


        if (auth()->user()) {


            $address      = optional(auth()->user()->address)->first();
            if (!!$address) {

                $street_name  = $address->street_name;
                $flat_number  = $address->flat_number;
                $floor_number = $address->floor_number;
                $block_number = $address->block_number;
                $country      = $address->country;
                $city         = $address->city;
            }
            $email        = auth()->user()->email;
            $phone        = auth()->user()->phone;
            $full_name    = auth()->user()->full_name;
        }

        // if theres now products in cart redirect back 
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index');
        }


        return view('checkout', compact(
            'carts',
            'address',
            'street_name',
            'flat_number',
            'floor_number',
            'block_number',
            'country',
            'city',
            'email',
            'phone',
            'full_name',
            'total',
        ));
    }


    public function completePaymentShow(Order $order)
    {

        tap(trans('site.complete_payment'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name_by_lang . ' ' . $seoTitle]);
        });

        if (!$order) {

            return redirect()->route('cart.index');
        }

        return view('complete-payment', compact('order'));
    }

    public function  checkout(Order $order, OrdersRequest $request)
    {
        // request  =>  address id   customer id   promo code 
        $data = [];

        $data = $request->all();

        if (auth()->check()) {

            $data['customer_id'] = auth()->id();
            /* $data['address_id']  = auth()->user()->address->first()->id; */
        }

        $carts =    $order->getUserCartData();

      
        // check product publishing and quantity limit 
        $carts->each(function ($cart) {

            $cart = collect($cart);

            $product = collect($cart['product']);

         

            abort_unless($product['status'] == Product::ACTIVE_STATUS, Response::HTTP_NOT_FOUND, trans('site.product_not_available'));
           
            abort_if($cart['quantity'] > $product['quantity'], Response::HTTP_NOT_FOUND, trans('main.product_order_limit_error'));
            
        });

      
        if (session()->has('promo_code')) {

            $code = session()->get('promo_code');

            $promoCode = PromoCode::lookFor($code);

            if (!!$promoCode) {

                abort_if($promoCode->isExpired(), Response::HTTP_NOT_FOUND, trans('main.promoCode_is_expired'));

                $data['promo_code'] = $promoCode->discount;
                $data['promo_code_id'] = $promoCode->id;
            }
        }

     
        // Create Invoice
        $data['invoice_id'] = 'INV-' . substr(md5(time() . Str::random(10)), 0, 7);

       
        $order = Order::create($data);

        $orderProducts = [];
 
        foreach ($carts as $cart) {

            
            $cart = collect($cart);
           
            $product = Product::find($cart['id']);
 
            $new_quantity = $product->quantity - $cart['quantity'];

            $product->update(['quantity' => $new_quantity]);

            $orderProducts[] = [
                'product_id'  => $product['id'],
                'quantity'    => $cart['quantity'],
                'price'       => $cart['price'],
                'final_price' => $cart['quantity'] * $cart['price'],
                'creator_id'  => $product->creator_id,
            ];
        }


        $total = [];

        $order->products()->createMany($orderProducts);

        foreach ($orderProducts as $orderProducts) {

            $total[] = $orderProducts['final_price'];
        }

        $total = array_sum($total);


        $promoCodeInSession = session()->get('promo_code');

        if (!!$promoCodeInSession) {

            $total = $total - ($total * $data['promo_code']) / 100;
        }

        $order->update(['total' => $total]);

        $order->load('products');

        //$order->notifyAdminsForNewOrder();


        if (auth()->check()) {

            auth()->user()->carts()->delete();
        } else {

            session()->forget('products_in_cart');
            session()->forget('promo_code');
        }

        notify('success', trans('main.added-message'));

        if ($data['payment_method'] == Order::CASH_PAYMENT_METHOD) {


            return  redirect()->route('payment_cash', $order);
        } elseif ($data['payment_method'] == Order::TAP_PAYMENT_METHOD) {

            return  redirect()->route('payment_tap', $order);
        }

        return redirect()->route('cart.index');
    }


    public function paymentCash(Order $order)
    {
        // Check if auth user is order owner
        // abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

        if ($order->isCancelled()) {
            notify('error', trans('site.cant_pay_canceled_order'));

            return back();
        }

        // Check iof Order Is Paid Or Not (if paid abort immediately)
        abort_if($order->isPaid(), Response::HTTP_INTERNAL_SERVER_ERROR, trans('site.payment_already_completed'));

        $order->markAsPaid()
            ->payWithCash()
            ->paidOnWebPlatform();
        //->notifyCreatorForOrderPaid()
        //->notifyCustomerForOrderPaid();

        return redirect()->route('order-completed');
    }

    public function paymentTap(Request $request, Order $order)
    {
        // Check if auth user is order owner
        // abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

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
                'first_name'            =>  $order->name,
                'email'                 =>  $order->email,
                'phone' => [
                    'country_code' => 002,
                    'number'       => $order->phone,
                ],

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
        // abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

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
                ->setPaymentId($request->tap_id);
            // ->notifyCreatorForOrderPaid()
            // ->notifyCustomerForOrderPaid();
        }

        return redirect()->route('order-completed');
    }

    public function orderCompleted()
    {

        tap(trans('main.order'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name_by_lang . ' ' . $seoTitle]);
        });

        return view('order-completed');
    }


    public function complete(Order $order)
    {
        /*  abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

        abort_unless(!!$order, Response::HTTP_NOT_FOUND);

        $order->load('product');
        $title = trans('site.order_completed');

        if ($order->isCancelled() && $order->isNotCompleted()) {
            $title = trans('site.order_cancelled');
        }

        tap($title, function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name_by_lang . ' ' . $seoTitle]);
        });

        return view('payment.checkout-steps.step-three-order-completed', compact('order')); */
    }


    public function cancelled(Order $order)
    {
        abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

        abort_unless(!!$order, Response::HTTP_NOT_FOUND);

        if (!$order->canCancelled()) {
            notify('error', trans('main.cant_canceled_order'));

            return back();
        }

        if ($order->isCancelled()) {
            notify('error', trans('main.cant_update_canceled_order'));

            return back();
        }

        $order
            ->markAsCancelled()
            ->notifyCreatorForOrderCancelled();

        notify('success', trans('main.cancelled'));

        return back();
    }
}
