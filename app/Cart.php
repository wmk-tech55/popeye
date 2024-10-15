<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class Cart extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingPriceRatio;

    protected $fillable = [
        'quantity',
        'price',
        'total_price',
        'total_price_before_additions',
        'total_additions_price',
        'product_id',
        'store_id',
        'customer_id'
    ];


    protected $with    = ['product', 'cartAdditions'];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Cart $cart) {

            $cart->cartGroupAdditions()->delete();
            $cart->cartAdditions()->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withoutGlobalScopes();
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withoutGlobalScopes();
    }


    public function cartGroupAdditions()
    {
        return $this->hasMany(CartGroupAddition::class, 'cart_id')->withoutGlobalScopes();
    }

    public function cartAdditions()
    {
        return $this->hasMany(CartAddition::class, 'cart_id')->withoutGlobalScopes();
    }

    public function reserveQuantity()
    {
        return $this->product()->decrement('quantity', $this->quantity);
    }

    public function isAllowedToAddToCart($store_id)
    {
        $auth  = auth();

        if (request()->wantsJson()) $auth = auth('api');
        // Reuse User with "web" guard in ajax calls
        if (request()->ajax()) $auth = auth();

        $userId = $auth->id();

        $cart =  $auth->user()->carts()
            ->where('customer_id', $userId)
            ->first();

        if (!$cart) return false;
        return $cart->store_id == $store_id;
    }

    public function updateCart($request)
    {

        $total_additions_price      = $this->total_additions_price ?? 0;
        $quantity                   = $request->quantity;
        $additions                  = $request->additions ;
        $total_price_after_addition = $quantity * $this->price;
        $total_price                = $total_price_after_addition;
        $totalCart                  = [];

        $totalCart = [
            'quantity'                     => $quantity,
            'total_price'                  => $total_additions_price +   $total_price_after_addition,
            'total_price_before_additions' => $total_price_after_addition,
        ];

        $this->update($totalCart);

        if ($additions && count($additions) > 0) {

            $this->cartAdditions()->delete();
 
  
 
                foreach ($additions as $addition) {

                    $cartAddition = ProductAddition::find($addition['id']);

                    if (!$cartAddition) return   false;

                    $additionsData[] =   [
                        'price'                  => $cartAddition->price,
                        'total_price'            => $addition['quantity'] * $cartAddition->price,
                        'quantity'               => $addition['quantity'],
                        "addition_id"            => $cartAddition->id,
 
                    ];
                }
 
             

            $this->attachAdditions($additionsData, $total_price);
        }

        return  $this;
    }


    public function attachAdditions($additions, $total_price)
    {

        if (count($additions) > 0) {

            $this->cartAdditions()->createMany($additions);

            $total_additions_price = $this->cartAdditions()->sum('total_price');

            $this->update([
                'total_additions_price'        => $total_additions_price,
                'total_price'                  => $total_additions_price + $total_price,
                'total_price_before_additions' => $total_price,
            ]);

            return    $this;
        }
    }
}
