<?php

/*
|--------------------------------------------------------------------------
| Application Defaults From A To Z
|--------------------------------------------------------------------------
|
| Use This Values In When You Need Default Values.
|
*/

return [
      
    /*
    |--------------------------------------------------------------------------
    | System
    |--------------------------------------------------------------------------
    |
    | Here Our Default system related configs that not belongs to any module.
    |
    */

    'system' => [
        'country_testing_ip'      => env('COUNTRY_TESTING_IP'),
        'country_testing_code'    => env('COUNTRY_TESTING_CODE', 'EG'),
        'main_notification_mail'  => env('MAIN_NOTIFICATION_MAIL', 'info@mix-code.com'),
        'currency_freaks_api_key' => env('CURRENCY_FREAKS_API_KEY'),
        'fcm_server_key'          => env('FIREBASE_CLOUD_MESSAGING_SERVER_KEY'),
        'msegat_sendeName'        => env('MSEGAT_SENDER_NAME'),
        'msegat_username'         => env('MSEGAT_USERNAME'),
        'msegat_api_key'          => env('MSEGAT_API_KEY'),
        'google_map_key'          => env('GOOGLE_MAP_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Here Our Default Settings, That Shipped With This Project.
    |
    */

    'settings' => [
        'name'              => env('APP_NAME'),
        'email'             => 'email@example.com',
        'address'           => 'address',
        'phone'             => '00000000000',
        'usd_price_ratio'   => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Main Branch
    |--------------------------------------------------------------------------
    |
    | Here Our Default Main Branch, That Shipped With This Project.
    |
    */

    'main_branch' => [
        'en_name'       => 'Head Office',
        'ar_name'       => 'المقر الرئيسي',
        'en_address'    => 'Address',
        'ar_address'    => 'العنوان',
        'email'         => 'example@email.com',
    ],

];