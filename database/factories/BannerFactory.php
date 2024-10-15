<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Banner;
use Faker\Generator as Faker;
use MixCode\User;

$factory->define(Banner::class, function (Faker $faker) {
    return [
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'url'               => $faker->url,
        'creator_id'        => factory(User::class)->create()->id,
        'deleted_at'        => null,
    ];
});
