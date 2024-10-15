<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Relations\Pivot;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class ProductGroupAddition extends Pivot
{
    use UsingUuid, UsingSerializeDate;

    protected $table    = 'product_group_additions';

    protected $fillable = ['en_name', 'ar_name', 'type', 'product_id'];

    protected $appends = ['name_by_lang'];
    protected $with    = ['additions'];
 

    const ONE_OPTION_GROUP_TYPE           = "one_option_group_type";
    const MULTIPLE_OPTION_GROUP_TYPE      = "multiple_options_group_type";
    const OPTIONS_WITH_COUNTER_GROUP_TYPE = "options_with_counter_group_type";

    const GROUP_TYPES    = [
        self::MULTIPLE_OPTION_GROUP_TYPE,
        self::ONE_OPTION_GROUP_TYPE,
        self::OPTIONS_WITH_COUNTER_GROUP_TYPE
    ];

    protected static function boot()
    {
        parent::boot();
 
        static::deleting(function (ProductGroupAddition $productGroupAddition) {
            // Clear  Relation
            $productGroupAddition->additions()->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function additions()
    {
        return $this->hasMany(ProductAddition::class, 'group_id');
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
