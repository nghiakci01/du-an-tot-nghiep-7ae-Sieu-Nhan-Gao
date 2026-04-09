<?php

return [
    'pickup' => [
        'province' => env('SHIPPING_PICKUP_PROVINCE', 'Ha Noi'),
        'district' => env('SHIPPING_PICKUP_DISTRICT', 'Quan Hai Ba Trung'),
        'ward' => env('SHIPPING_PICKUP_WARD'),
        'address' => env('SHIPPING_PICKUP_ADDRESS'),
    ],

    'ghn' => [
        'enabled' => env('GHN_ENABLED', false),
        'token' => env('GHN_TOKEN'),
        'shop_id' => env('GHN_SHOP_ID'),
        'api_url' => env('GHN_API_URL', 'https://dev-online-gateway.ghn.vn'),
        'service_type_id' => (int) env('GHN_SERVICE_TYPE_ID', 2),
    ],

    'ghtk' => [
        'enabled' => env('GHTK_ENABLED', true),
        'token' => env('GHTK_TOKEN'),
        'client_source' => env('GHTK_CLIENT_SOURCE'),
        'api_url' => env('GHTK_API_URL', 'https://services.giaohangtietkiem.vn'),
        'transport' => env('GHTK_TRANSPORT', 'road'),
    ],
];
