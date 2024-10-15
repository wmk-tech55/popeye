<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Faq;
use Faker\Generator as Faker;
use MixCode\User;

$factory->define(Faq::class, function (Faker $faker) {
    return [
        'en_question'       => $faker->title,
        'ar_question'       => $faker->title,
        'en_answer'         => $faker->paragraph,
        'ar_answer'         => $faker->paragraph,
        'status'            => Faq::ACTIVE_STATUS,
        'creator_id'        => factory(User::class)->create()->id,
        'deleted_at'        => null,
    ];
});
