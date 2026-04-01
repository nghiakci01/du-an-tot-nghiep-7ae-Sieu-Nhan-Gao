<?php

return [
    'pickup' => [
        'province' => env('SHIPPING_PICKUP_PROVINCE', 'Ha Noi'),
        'district' => env('SHIPPING_PICKUP_DISTRICT', 'Quan Hai Ba Trung'),
        'ward' => env('SHIPPING_PICKUP_WARD'),
        'address' => env('SHIPPING_PICKUP_ADDRESS'),
    ],

    'ghtk' => [
        'enabled' => env('GHTK_ENABLED', true),
        'token' => env('GHTK_TOKEN'),
        'client_source' => env('GHTK_CLIENT_SOURCE'),
        'api_url' => env('GHTK_API_URL', 'https://services.giaohangtietkiem.vn'),
        'transport' => env('GHTK_TRANSPORT', 'road'),
    ],
];
