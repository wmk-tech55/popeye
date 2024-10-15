<?php

return [
    'secret' => env('TAP_SECRET'),
    'settings' => [
        'mode' => env('TAP_MODE', 'sandbox'),
        'currency' => env('TAP_CURRENCY', 'SAR'),
        'save_card' => false,
        'three_d_secure' => false,
        'source' => [
            'id' => 'src_all'
        ],
        'receipt' => [
            'email' => true, 
            'sms' => true
        ],
    ],
];
