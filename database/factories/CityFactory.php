<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\City;
use Faker\Generator as Faker;
use MixCode\Country;
use MixCode\User;

$factory->define(City::class, function (Faker $faker) {
    return [
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'status'            => City::ACTIVE_STATUS,
        'country_id'        => factory(Country::class)->create()->id,
        'creator_id'        => factory(User::class)->create()->id,
        'deleted_at'        => null,
    ];
});
