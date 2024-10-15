<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Product;
use Faker\Generator as Faker;
use MixCode\City;
use MixCode\Country;
use MixCode\User;

$factory->define(Product::class, function (Faker $faker) {
    return [
        // Basic Info
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'price'             => $faker->randomFloat(2, 0, 100),
        'quantity'          => $faker->randomNumber(),
        'status'            => \Arr::random([Product::ACTIVE_STATUS, Product::INACTIVE_STATUS]),
        'en_overview'       => $faker->paragraph,
        'ar_overview'       => $faker->paragraph,
        'creator_id'        => factory(User::class)->create()->id,
        'views_count'       => 0,
        'deleted_at'        => null,
     ];
});
