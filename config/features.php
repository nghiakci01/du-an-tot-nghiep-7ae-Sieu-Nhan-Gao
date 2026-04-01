<?php

return [
    'wallet' => (bool) env('FEATURE_WALLET_ENABLED', false),
    'stock_report' => (bool) env('FEATURE_STOCK_REPORT_ENABLED', false),
    'dev_payment_routes' => (bool) env('FEATURE_DEV_PAYMENT_ROUTES', false),
];
