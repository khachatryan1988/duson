<?php

return [
    'default' => 'ameria',

    'drivers' => [
        'ameria' => [
            'name' => 'Ameria',
            'ClientId' => 'c7342127-bbd5-43c1-b31c-f55a3025ded4',
            'Username' => '19539095_api',
            'Password' => 'XwJ2LFNpHX67kg3S',
            'Language' => 'hy',
            'Description' => env('APP_NAME'),
            'ReturnUrl' => url('/payment/ameria'),
            'TestMode' => false,
        ],
        'ineco' => [
            'name' => 'Ineco',
            "UserName" => '384.29008676.29538676',
            "Password" => 'V@PQzn8#5qj4YVwK-EWVuk*Uh!VA58_U',
            "Language" => 'hy',
            "Description" => env('APP_NAME'),
            "ReturnUrl" => url('/payment/ineco'),
            'TestMode' => false
        ],
        'arca' => [
            'name' => 'Arca',
            "UserName" => '34558023_api',
            "Password" => 'Rh6lzl=Sksd5',
            "Language" => 'hy',
            "Description" => env('APP_NAME'),
            "ReturnUrl" => url('/payment/arca'),
            'TestMode' => false
        ],
        'idram' => [
            'name' => 'Idram',
            "Email" => 'dav.hovsepian@gmail.com',
            "Language" => 'hy',
            "AccountId" => '100049973',
            "SecretKey" => 'g5P1V1cocCjoqqfRF7MYagwleRe1y8hZ3BM18s'
        ]
    ]
];
