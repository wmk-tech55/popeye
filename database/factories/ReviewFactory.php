<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Review;
use Faker\Generator as Faker;
use MixCode\Product;
use MixCode\User;

$factory->define(Review::class, function (Faker $faker) {
    $user = factory(User::class)->create();

    return [
        'name'          => $user->full_name,
        'email'         => $user->email, 
        'review'        => $faker->paragraph,
        'rate'          => rand(1, 5),
        'customer_id'   => $user->id,
        'product_id'       => factory(Product::class)->create()->id,
        'deleted_at'    => null,
    ];
});
