<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use MixCode\ShippingFeePerDistance;

class ShippingFeePerDistancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id'                  => Str::uuid()->toString(),
                'distance_from'       => 1,
                'distance_to'         => 10,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km',
                'created_at'          => now()
            ],
            [
                'id'                  => Str::uuid()->toString(),
                'distance_from'       => 10,
                'distance_to'         => 15,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km',
                'created_at'          => now()
            ],
            [
                'id'                  => Str::uuid()->toString(),
                'distance_from'       => 15,
                'distance_to'         => 20,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km',
                'created_at'          => now()
            ],
            [
                'id'                  => Str::uuid()->toString(),
                'distance_from'       => 20,
                'distance_to'         => 30,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km',
                'created_at'          => now()
            ],
            [
                'id'                  => Str::uuid()->toString(),
                'distance_from'       => 30,
                'distance_to'         => 40,
                'is_default_distance' => 0,
                'shipping_fee'        => 0,
                'unit'                => 'km',
                'created_at'          => now()
            ],
            [
                'id'                  => Str::uuid()->toString(),
                'distance_from'       => 40,
                'distance_to'         => null,
                'is_default_distance' => 1,
                'shipping_fee'        => 0,
                'unit'                => 'km',
                'created_at'          => now()
            ],
        ];


        ShippingFeePerDistance::insert($data);
    }
}
