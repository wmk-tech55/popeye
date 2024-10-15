<?php

namespace MixCode;

use Artisan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingMedia;
use MixCode\Utils\UsingStatus;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Country extends Model implements HasMedia
{
    use UsingUuid,
        UsingSerializeDate,
        UsingStatus,
        UsingApiScopes,
        UsingMedia,
        HasMediaTrait,
        RefreshCache,
        SoftDeletes;

    const ACTIVE_STATUS        = 'active';
    const INACTIVE_STATUS      = 'disable';
    const CREATOR_RELATION_KEY = 'creator_id';
    const STATUSES             = [self::ACTIVE_STATUS, self::INACTIVE_STATUS];

    protected $appends = ['name_by_lang', 'currency_by_lang', 'main_media_url'];
    protected $with = ['media'];

    protected $fillable = [
        'en_name',
        'ar_name',
        'country_code',
        'en_currency',
        'ar_currency',
        'status',
        'creator_id',
    ];

    protected $hidden = ['media'];


    protected static function boot()
    {
        parent::boot();

        // When Deleting country
        static::deleting(function (Country $country) {
            if ($country->isForceDeleting()) {
                $country->shippingFeePerDistances()->delete();
            }
        });

        Artisan::call('cache:clear');
    }

    public function setCountryCodeAttributes($value)
    {
        return $this->attribute['country_code'] = strtoupper($value);
    }

    public function path()
    {
        return route('dashboard.countries.show', $this);
    }

    public function apiPath()
    {
        return route('api.countries.show', $this);
    }

    public function viewPath()
    {
        return route('countries.show', $this);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, static::CREATOR_RELATION_KEY)->withoutGlobalScopes();
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id')->withoutGlobalScopes();
    }

    public function shippingFeePerDistances()
    {
        return $this->hasMany(ShippingFeePerDistance::class, 'country_id')
            ->orderBy("distance_from", "asc")
            ->withoutGlobalScopes();
    }


    /**
     * Create New country With It's Relations
     *
     * @param Request $request
     * @return \MixCode\Country
     */
    public function createNew($request)
    {
        /** @var \MixCode\Country */

        $data           = $request->all();
        $data['country_code'] = \Str::upper($request->country_code);

        $country  = static::create($data);

        $country->attachShippingFeePerDistance($request);

        return $country;
    }

    public function attachShippingFeePerDistance($request)
    {
        if ($request->has('shipping_fees') && count($request->shipping_fees) > 0) {

            foreach (ShippingFeePerDistance::shippingFeePerDistances() as $index => $shippingFee) {
                $data[] = [
                    'distance_from'       => $shippingFee['distance_from'],
                    'distance_to'         => $shippingFee['distance_to'],
                    'is_default_distance' => $shippingFee['is_default_distance'],
                    'shipping_fee'        => $request->shipping_fees[$index],
                    'unit'                => $shippingFee['unit']
                ];
            }
            $this->shippingFeePerDistances()->createMany($data);
        }

        return $this;
    }


    /**
     * Update Exist country With It's Relations
     *
     * @param Request $request
     * @return \MixCode\Country
     */
    public function updateCountry($request)
    {
        /** @var \MixCode\Country */

        $data           = $request->all();
        $data['country_code'] = \Str::upper($request->country_code);

        $this->update($data);

        $this->syncShippingFeePerDistance($request);

        return $this;
    }


    public function syncShippingFeePerDistance($request)
    {
        if ($request->has('shipping_fees') && count($request->shipping_fees) > 0) {

            foreach ($request->shipping_fees as $id => $shippingFee) {

                $shippingFeePerDistance = ShippingFeePerDistance::find($id);
                if ($shippingFeePerDistance) {
                    $shippingFeePerDistance->update(['shipping_fee' => $shippingFee]);
                }
            }
        }

        return $this;
    }

    public function getNameByLangAttribute()
    {
        $field = $this->getLang() . '_name';

        return $this->{$field};
    }

    public function getCurrencyByLangAttribute()
    {
        $field = $this->getLang() . '_currency';

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
