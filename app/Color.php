<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class Color extends Model
{
    use UsingUuid ,UsingSerializeDate ,
        UsingApiScopes,
        RefreshCache,
        SoftDeletes;


    const CREATOR_RELATION_KEY = 'creator_id';

    protected $fillable = ['en_name', 'ar_name', 'color', 'creator_id'];

    protected $appends = ['name_by_lang'];

    public function path()
    {
        return route('dashboard.colors.show', $this);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_colors', 'color_id', 'product_id')
            ->using(ProductColor::class)
            ->withoutGlobalScopes();
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
     * return lang key if exists in request or fall back to "en"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'en';
    }
}
