<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class ProductCategory extends Pivot
{
    use UsingUuid ,UsingSerializeDate ;

protected $table = 'product_categories';
 

public function category()
{
    return $this->hasOne(static::class,'category_id')
         ;
}

}
