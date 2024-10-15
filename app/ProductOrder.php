<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;use MixCode\Utils\UsingMedia;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ProductOrder extends Model implements HasMedia
{
    use UsingUuid ,UsingSerializeDate  ,
    UsingMedia,
    UsingApiScopes,
    HasMediaTrait,
    RefreshCache;

    protected $table    = 'product_orders';

    protected $fillable = ['quantity', 'price', 'final_price', 'order_id','variation_id', 'product_id','creator_id'];

    protected $with     = ['product','additions','variation'];

 
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withoutGlobalScopes();
    }
    
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id')->withoutGlobalScopes();
    }
 
    public function additions()
    {
        return $this->hasMany(ProductOrderAddition::class, 'product_order_id')->withoutGlobalScopes();
    }

   
}
