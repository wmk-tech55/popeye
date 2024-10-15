<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use MixCode\Utils\UsingMedia;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ProductOrderAddition extends Model implements HasMedia
{
    use UsingUuid,
        UsingSerializeDate,
        UsingMedia,
        UsingApiScopes,
        HasMediaTrait,
        RefreshCache;

    protected $table    = 'product_order_additions';

    protected $fillable = [
        'quantity',
        'price',
        'final_price',
        'product_order_id',
        'addition_id' 
    ];

    protected $with = ['addition'];
    
    public function addition()
    {
        return $this->belongsTo(ProductAddition::class, 'addition_id')->withoutGlobalScopes();
    }
    public function productOrder()
    {
        return $this->belongsTo(ProductOrder::class, 'product_order_id')->withoutGlobalScopes();
    }
 
}
