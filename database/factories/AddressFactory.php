<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Address;
use Faker\Generator as Faker;
use MixCode\User;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'block_number'      => $faker->buildingNumber,
        'floor_number'      => $faker->randomNumber(),
        'flat_number'       => $faker->randomNumber(),
        'street_name'       => $faker->streetName,
        'type'              => \Arr::random(Address::ADDRESS_TYPE),
        'owner_id'          => factory(User::class)->create()->id,
    ];
});
