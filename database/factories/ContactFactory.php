<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name'          => $faker->name, 
        'email'         => $faker->safeEmail, 
        'phone'         => $faker->phoneNumber, 
        'message'       => $faker->paragraph,
        'deleted_at'    => null,
    ];
});
