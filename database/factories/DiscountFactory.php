<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use MixCode\Discount;
use MixCode\User;

$factory->define(Discount::class, function (Faker $faker) {
    return [
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'discount'          => $faker->randomNumber(),
        'status'            => Discount::ACTIVE_STATUS,
        'creator_id'        => factory(User::class)->create()->id,
        'deleted_at'        => null,
    ];
});
