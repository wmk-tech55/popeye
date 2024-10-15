<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
class Classification extends Model
{
    use UsingUuid ,UsingSerializeDate ,
        UsingApiScopes,
        RefreshCache,
        SoftDeletes;

    const CREATOR_RELATION_KEY = 'creator_id';

    protected $fillable = ['en_name', 'ar_name', 'creator_id','categorization_id'];

    protected $appends = ['name_by_lang'];

    public function path()
    {
        return route('dashboard.classifications.show', $this);
    }

    public function apiPath()
    {
        return route('api.classifications.show', $this);
    }

    public function viewPath()
    {
        return route('classifications.show', $this);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function categorization()
    {
        return $this->belongsTo(Categorization::class,  'categorization_id')->withoutGlobalScopes();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variations', 'classification_id', 'product_id')
            ->using(ProductVariation::class)
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



    public function getValueByLangAttribute()
    {
        $lang = app()->getLocale();

        if (request()->wantsJson()) {
            $lang = $this->hasLang();
        }

        $field = $lang . '_value';

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
