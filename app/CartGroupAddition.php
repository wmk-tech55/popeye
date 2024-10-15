<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Matrix\Operators\Addition;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class CartGroupAddition extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingPriceRatio;

    protected $fillable = [
        'cart_id',
        'group_id',
    ];

    protected  $with = ['additionGroup'];

    public function additionGroup()
    {
        return $this->belongsTo(ProductGroupAddition::class, 'group_id')->withoutGlobalScopes();
    }
}
