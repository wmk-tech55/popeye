<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MixCode\Order;
use Faker\Generator as Faker;
use MixCode\Address;
use MixCode\User;

$factory->define(Order::class, function (Faker $faker) {
    $customer   = factory(User::class)->create();
    $driver     = factory(User::class)->create(['type' => User::DRIVER_TYPE]);
    $address    = factory(Address::class)->create(['owner_id' => $customer]);

    return [
        'day'              => null,
        'time'              => \Arr::random(Order::DELIVERY_PERIODS),
        'payment_method'    => \Arr::random(Order::PAYMENT_METHODS),
        'total'             => 10,
        'comment'           => $faker->paragraph,
        'promo_code'        => null,
        'invoice_id'        => null,
        'is_paid'           => Order::NOT_PAID_STATUS,
        'is_cancelled'      => Order::NOT_CANCELLED_STATUS,
        'is_approved'       => Order::NOT_APPROVED_STATUS,
        'is_preparing'      => Order::NOT_PREPARED_STATUS,
        'in_shipping'       => Order::NOT_SHIPPED_STATUS,
        'is_delivered'      => Order::NOT_DELIVERED_STATUS,
        'address_id'        => $address->id,
        'customer_id'       => $customer->id,
        'driver_id'         => $driver->id,
    ];
});
