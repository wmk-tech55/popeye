<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Cart;
use Faker\Generator as Faker;
use MixCode\Product;
use MixCode\User;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'quantity'      => $faker->randomNumber(),
        'product_id'    => factory(Product::class)->create()->id,
        'customer_id'   => factory(User::class)->create()->id,
    ];
});
