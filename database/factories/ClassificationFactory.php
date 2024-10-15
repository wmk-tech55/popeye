<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Classification;
use Faker\Generator as Faker;
use MixCode\User;

$factory->define(Classification::class, function (Faker $faker) {
    return [
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'creator_id'        => factory(User::class)->create()->id,
        'deleted_at'        => null,
    ];
});
