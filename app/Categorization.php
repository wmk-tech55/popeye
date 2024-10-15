<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingMedia;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class   Categorization extends Model implements HasMedia
{
    use UsingUuid,
        UsingSerializeDate,
        UsingApiScopes,
        HasMediaTrait,
        UsingMedia,
        RefreshCache,
        SoftDeletes;

    const CREATOR_RELATION_KEY = 'creator_id';

    /**
     * The attributes that should be appended.
     *
     * @var array
     */
    protected $appends = ['name_by_lang','icon'];

    protected $fillable = [
        'en_name',
        'ar_name',
        'order_field',
        'creator_id',
    ];


    protected $hidden = ['media'];
 //   protected $with   = ['products'];


    public function path()
    {
        return route('dashboard.categorizations.show', $this);
    }

    public function apiPath()
    {
        return route('api.categorizations.show', $this);
    }

    public function viewPath()
    {
        return route('categorizations.show', $this);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'categorization_id')->withoutGlobalScopes();
    }
    

    public function classifications()
    {
        return $this->belongsToMany(Classification::class, 'classification_categorizations', 'categorization_id', 'classification_id')
            ->using(ClassificationCategorization::class)
            ->withoutGlobalScopes();
    }


    public function users()
    {
        return $this->belongsToMany(Categorization::class, 'user_categorizations', 'categorization_id', 'user_id')
            ->using(CategorizationUser::class)
            ->withoutGlobalScopes();
    }


    public function getIconAttribute()
    {
        return $this->safeMediaUrl($this->mainMediaUrl('icon'));
    }


    public function getNameByLangAttribute()
    {
        $field = $this->getLang() . '_name';

        return $this->{$field};
    }

    /**
     * Return lang key based on if request wants json response for api
     *
     * @return string
     */
    protected function getLang()
    {
        return request()->wantsJson() ? $this->hasLang() : app()->getLocale();
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
