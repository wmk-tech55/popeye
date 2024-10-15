<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class ProductVariation extends Pivot
{
    use UsingUuid, UsingSerializeDate;

    protected $table    = 'product_variations';

    const ONE_OPTION      = "one_option";
    const MULTIPLE_OPTION = "multiple_options";
    const TYPE_OPTIONS    = [self::ONE_OPTION, self::MULTIPLE_OPTION];

    protected $fillable = [
        'price',
        'quantity',
        'en_name',
        'ar_name',
        'type',
        'classification_id',
        'product_id',
        'is_primary'
    ];

    protected $casts   = ['is_primary' => 'boolean'];
    protected $with    = ['size'];
    protected $hidden  = ['classification_id'];
    protected $appends = ['name_by_lang'];


    public function size()
    {
        return $this->belongsTo(Classification::class, 'classification_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getNameByLangAttribute()
    {
        $lang = app()->getLocale();

        if (request()->wantsJson()) {
            $lang = $this->hasLang();
        }

        $field = $lang . '_name';

        return $this->{$field};
    }

    /**
     * return lang key if exists in request or fall back to "ar"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'ar';
    }
}
