<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Color;
use Faker\Generator as Faker;
use MixCode\User;

$factory->define(Color::class, function (Faker $faker) {
    return [
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'color'             => $faker->colorName,
        'creator_id'        => factory(User::class)->create()->id,
        'deleted_at'        => null,
    ];
});
