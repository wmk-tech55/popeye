<?php

namespace MixCode;

use Illuminate\Database\Eloquent\Model;
use MixCode\Utils\UsingPriceRatio;
use MixCode\Utils\UsingUuid;
use MixCode\Utils\UsingSerializeDate;

class ShippingFeePerDistance extends Model
{
    use UsingUuid,
        UsingSerializeDate,
        UsingPriceRatio;

    protected $fillable = [
        'distance_from',
        'distance_to',
        'is_default_distance',
        'shipping_fee',
        'unit',
        'country_id'

    ];

    protected  $casts = ['is_default_distance' => 'boolean'];

    public static function shippingFeePerDistances()
    {
        return [
            [
                'distance_from'       => 1,
                'distance_to'         => 10,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km'
            ],
            [
                'distance_from'       => 10,
                'distance_to'         => 15,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km'
            ],
            [
                'distance_from'       => 15,
                'distance_to'         => 20,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km'
            ],
            [
                'distance_from'       => 20,
                'distance_to'         => 30,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km'
            ],
            [
                'distance_from'       => 30,
                'distance_to'         => 40,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km'
            ],
            [
                'distance_from'       => 40,
                'distance_to'         => null,
                'is_default_distance' => 1,
                'shipping_fee'        => 0,
                'unit'                => 'km'
            ],
        ];
    }

    public function updateItem($request)
    {
        if ($request->has('shipping_fees') && count($request->shipping_fees) > 0) {

            foreach ($request->shipping_fees as $idIndex => $shipping_fee) {

                $shippingFee = ShippingFeePerDistance::find($idIndex);
                if ($shippingFee) {
                    $shippingFee->update(['shipping_fee' => $shipping_fee]);
                }
            }
        }
    }

    public function getDistanceFromAttribute()
    {
        return floatval($this->attributes['distance_from']);
    }
    
    public function getDistanceToAttribute()
    {
        return floatval($this->attributes['distance_to']);
    }
}
