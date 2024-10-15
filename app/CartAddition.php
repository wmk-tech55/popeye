<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Matrix\Operators\Addition;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class CartAddition extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingPriceRatio;

    protected $fillable = [
        'price',
        'total_price',
        'quantity',
        'cart_id',
        'addition_id',
        'group_id',
        'cart_group_addition_id',
    ];

    protected  $with = ['addition','cartGroupAddition'];  
  
    public function addition() 
    {
        return $this->belongsTo(ProductAddition::class ,'addition_id')->withoutGlobalScopes();
    }
    public function cartGroupAddition() 
    {
        return $this->belongsTo(CartGroupAddition::class ,'cart_group_addition_id')->withoutGlobalScopes();
    }
  
}
