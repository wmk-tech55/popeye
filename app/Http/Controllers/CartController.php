<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Cart;
use MixCode\Http\Requests\CartsRequest;
use MixCode\Product;
use MixCode\Utils\UsingSEO;
use MoaAlaa\ApiResponder\ApiResponder;

class CartController extends Controller
{
    use ApiResponder, UsingSEO;

    
    public function index()
    {      
        tap(trans('site.cart'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

        $carts = Cart::where('customer_id', auth()->id())->get();
 
          if (! $carts ) {
            return $this->api()->error(trans('main.not_found'), Response::HTTP_NOT_FOUND);
        }
     
         if (request()->wantsJson()) {
            return $this->api()->response($carts , null, Response::HTTP_CREATED);
        }
 
        return view('cart',compact('carts' ));
    }

    /**
     * Add to cart
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Product $product, int $quantity = 1)
    { 
        // Check if product have available order quantity or not
        if ($quantity > $product->quantity) {
            return $this->api()->error(trans('main.product_order_limit_error'), Response::HTTP_NOT_FOUND); 
        }
 
        if ($product->isInCart()) {
            return $this->api()->error(trans('main.product_already_in_cart'), Response::HTTP_FORBIDDEN);
        }

        $cart = $product->addToCart($quantity); 

        $cart->load('product');
  
        return $this->api()->response($cart, trans('main.product_added_to_cart'), Response::HTTP_CREATED);
    }

    /**
     * Add multi products to cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addMultiProductsToCart(Request $request)
    {            
        $carts = collect($request->carts);

        $carts->each(function ($cart) {
            $product = Product::find($cart['product']['id']);
            
            if (!! $product) {
                $this->addToCart($product, $cart['quantity']);
            }
        });
        
        return $this->api()->response(null, trans('main.product_added_to_cart'), Response::HTTP_CREATED);
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Cart $cart, Request $request)
    {

        abort_unless($cart->quantity >= 1, Response::HTTP_NOT_FOUND);
      
        if ($request->update_quantity == 'decrease_quantity' ) {
            $price           = $cart->price ;
            $new_quantity    = $cart->quantity - 1 ;

            if( $cart->product->has_special_price == 'yes'){
          
                $special_price_items =  $cart->product->specialPrice->filter(function ($query) use ($new_quantity)  {
                     return $query->special_quantity <=   $new_quantity ;
                });
      
        if( ! $special_price_items->isEmpty() ){
            $last_special_price_item = $special_price_items->last();

             $price = $last_special_price_item->special_price ; 

          } 

            }

            $total_new_price = $price * $new_quantity ;

            abort_unless($new_quantity >= 1, Response::HTTP_NOT_FOUND);  
 
            $cart->update(['quantity'=>$new_quantity , 'price'=> $price,'total_price'=>$total_new_price]) ;

        }elseif($request->update_quantity == 'increase_quantity'){
            $price           = $cart->price ;
            
            $new_quantity    = $cart->quantity + 1 ;

            if( $cart->product->has_special_price == 'yes'){
          
                $special_price_items =  $cart->product->specialPrice->filter(function ($query) use ($new_quantity)  {
                     return $query->special_quantity <=   $new_quantity ;
                });
      
        if( ! $special_price_items->isEmpty() ){
            $last_special_price_item = $special_price_items->last();

             $price = $last_special_price_item->special_price ; 

          } 

       }

            $total_new_price = $price * $new_quantity ;

            if ($cart->product->quantity < $new_quantity) {
            
                return $this->api()->error(trans('main.product_order_limit_error'), Response::HTTP_NOT_FOUND);
            }

    
            $cart->update(['quantity'=> $new_quantity , 'price'=> $price,  'total_price'=>$total_new_price]) ;
        }

        return $this->api()->response($cart, null, Response::HTTP_OK);
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
        $cart->delete();
 
        return $this->api()->response(null ,trans('main.deleted-message'), Response::HTTP_OK);
    }


}
