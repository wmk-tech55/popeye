<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingMedia;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use MixCode\Utils\UsingStatus;

class Banner extends Model implements HasMedia
{
    use UsingUuid,
        UsingSerializeDate,
        UsingMedia,
        UsingApiScopes,
        RefreshCache,
        HasMediaTrait,
        UsingStatus,
        SoftDeletes;


    const CREATOR_RELATION_KEY = 'creator_id';

    const HOME_MAIN_SLIDER    = 'home_main_slider';
    const HOME_SECOND_SLIDER  = 'home_second_slider';
    const HOME_THIRD_SLIDER   = 'home_third_slider';
    const HOME_MIDDLE_BANNER  = 'home_middle_banner';

    const WEBSITE_SECTIONS = [
        self::HOME_MAIN_SLIDER,
        self::HOME_SECOND_SLIDER,
        self::HOME_THIRD_SLIDER,
        self::HOME_MIDDLE_BANNER,
    ];

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'disable';

    protected $fillable = ['en_name', 'ar_name', 'url', 'position', 'show_title', 'store_id', 'creator_id', 'data_country_id'];

    protected $appends = ['name_by_lang', 'media_links'];

    protected $with = ['media'];

    protected $hidden = ['media'];


    public function path()
    {
        return route('dashboard.banners.show', $this);
    }

    public function viewPath()
    {
        return route('banners.show', $this);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'banner_id')->withoutGlobalScopes();
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id')->withoutGlobalScopes();
    }


    public function hasStatus($show_title)
    {
        return $this->show_title == $show_title;
    }

    public function showTitle()
    {
        return $this->hasStatus(static::ACTIVE_STATUS);
    }


    public function scopeByCountry(Builder $builder)
    {
        return $builder->where('data_country_id', countryId());
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


    public function getMediaLinksAttribute()
    {
        $media = $this->allMedia();

        $links = $media->map(function ($m) {
            return $this->safeMediaUrl($m->getUrl());
        });

        return $links;
    }

    /**
     * return lang key if exists in request or fall back to "en"
     *
     * @return string
     */
    protected function hasLang()
    {
        return (request()->has('lang') && request()->filled('lang')) ? request('lang') : 'ar';
    }
}
