<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Cart;
use MixCode\CartAddition;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\CartsRequest;
use MixCode\Product;
use MoaAlaa\ApiResponder\ApiResponder;

class CartController extends Controller
{
    use ApiResponder;


    public function index()
    {
        $carts =  Cart::where('customer_id', auth()->id())->get();

        if (!$carts) {
            return $this->api()->error(trans('main.not_found'), Response::HTTP_NOT_FOUND);
        }

        return $this->api()->response($carts, null, Response::HTTP_CREATED);
    }


    public function getCart(Cart $cart, Request $request)
    {
        $carts = Cart::where('customer_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return $this->api()->error(trans('site.cart_empty'), Response::HTTP_NOT_FOUND);
        }

        $data = getCartTotalAndFees($carts);

        return $this->api()->response($data, null, Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Cart  $cart
     * @return \Illuminate\Http\Response
     */

    public function addToCart(Cart $cart ,$product_id, Request $request)
    {
       
        $userId                             = auth()->id();
        $product                            = Product::find($product_id);
        if (request()->wantsJson()) $userId = auth('api')->id();
        if (request()->ajax()) $userId      = auth()->id();

 
        abort_unless($product, Response::HTTP_NOT_FOUND, trans('site.product_not_found'));

        if ($product->isPending())  return $this->api()->error(trans('site.is_out_of_stock'), Response::HTTP_NOT_FOUND);

        $userCart = auth()->user()->carts()->where('customer_id', $userId)->where('product_id', $product->id)->exists();

        if ($userCart) return $this->api()->error(trans('site.already_in_cart'), Response::HTTP_NOT_FOUND);
 

        if (auth()->user()->carts()->exists() &&  ! $cart->isAllowedToAddToCart($product->creator_id)) {
            return $this->api()->error(trans('site.cant_add_different_store_product'), Response::HTTP_FORBIDDEN);
        }
 
        $cart = $product->addToCart($product, $request);
 
        if (!$cart) {

            return $this->api()->response([], trans('site.group_addition_not_found'), Response::HTTP_NOT_FOUND);
        }

        $cart->load('product');

        return $this->api()->response($cart, trans('main.product_added_to_cart'), Response::HTTP_CREATED);
    }


    /**
     * Get specified resource  .
     *
     * @param  \MixCode\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function getProduct($product_id)
    {

        $product = Product::find($product_id);

        abort_unless($product, Response::HTTP_NOT_FOUND, trans('site.no_data_available'));

        $cart = Cart::query()
            ->where('customer_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        abort_unless($cart, Response::HTTP_NOT_FOUND, trans('main.not_found'));

        return $this->api()->response($cart, null, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update($cart, Request $request)
    {
        $cart = Cart::find($cart);

        abort_unless($cart, Response::HTTP_NOT_FOUND, trans('main.not_found'));

        abort_unless($request->quantity > 0, Response::HTTP_NOT_FOUND, trans('main.quantity_must_be_greater_than_zero'));

        $cart->updateCart($request);

        return $this->api()->response($cart, null, Response::HTTP_OK);
    }

    /**
     * Delete Product's additions from cart.
     *
     * @param  \MixCode\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function deleteAddition($cart_id, $addition_id)
    {
        $cartAddition = CartAddition::query()
            ->where('cart_id', $cart_id)
            ->where('addition_id', $addition_id)
            ->first();

        abort_unless($cartAddition, Response::HTTP_NOT_FOUND, trans('main.not_found'));

        $cartAddition->delete();

        return $this->api()->response(null, trans('main.deleted-message'), Response::HTTP_OK);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($cart_id)
    {
        $cart = Cart::find($cart_id);

        abort_unless($cart, Response::HTTP_NOT_FOUND, trans('main.not_found'));

        $cart->delete();

        return $this->api()->response(null, trans('main.deleted-message'), Response::HTTP_OK);
    }
}
