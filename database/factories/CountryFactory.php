<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Country;
use Faker\Generator as Faker;
use MixCode\User;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'country_code'      => $faker->countryCode,
        'status'            => Country::ACTIVE_STATUS,
        'creator_id'        => factory(User::class)->create()->id,
        'deleted_at'        => null,
    ];
});
