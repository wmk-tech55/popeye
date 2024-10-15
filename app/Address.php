<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use MixCode\Utils\RefreshCache;
use MixCode\Utils\UsingApiScopes;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class Address extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingApiScopes,
        RefreshCache;

    const OWNER_RELATION_KEY = 'owner_id';

    protected $casts = ['is_default' => 'boolean'];
    protected $with  = [];

    protected $fillable = [
        'area',
        'country_code',
        'country',
        'city',
        'latitude',
        'longitude',
        'block_number',
        'floor_number',
        'flat_number',
        'street_name',
        'type',
        'address',
        'address_description',
        'postal_code',
        'zoom',
        'owner_id',
        'is_default',
        'country_id',
        'city_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, static::OWNER_RELATION_KEY)->withoutGlobalScopes();
    }

    public function country()
    {
        return $this->belongsTo(User::class, 'country_id')->withoutGlobalScopes();
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id')->withoutGlobalScopes();
    }

    public function isDefault()
    {
        return $this->is_default == true;
    }

    public function scopeDefault(Builder $builder)
    {
        return $builder->where('is_default', true);
    }

    public function getFullAddressAttribute()
    {
        return $this->street_name . ' , ' . $this->state . ' , ' . $this->city . ' , ' . $this->country;
    }


    public function createNew($request)
    {
        $auth           = auth();
        $data           = $request->all();
        $defaultAddress = auth()->user()->defaultAddress;
        $address        = auth()->user()->addresses;


        if ($address && $address->count() >= 5)  return false;


        if (request()->wantsJson())   $auth = auth('api');

        if (request()->ajax())        $auth = auth();

        $data['owner_id'] =  $auth->id();
 
        if (!$address || $defaultAddress) {

            $data['is_default'] = true;

            $userAddress =  $auth->user()->addresses()->create($data);
        } else {

            $userAddress = $auth->user()->addresses()->create($data);
        }


        return $userAddress;
    }
}
