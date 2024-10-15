<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Category;
use Faker\Generator as Faker;
use MixCode\User;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'en_name'           => $faker->name,
        'ar_name'           => $faker->name,
        'status'            => Category::ACTIVE_STATUS,
        'creator_id'        => factory(User::class)->create()->id,
        'parent_id'         => null,
        'deleted_at'        => null,
    ];
});
