<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/admin/orders/create', 'GET');
$response = app()->handle($request);
echo "GET /admin/orders/create => " . $response->getStatusCode() . "\n";

$request2 = Illuminate\Http\Request::create('/admin/orders', 'GET');
$response2 = app()->handle($request2);
echo "GET /admin/orders => " . $response2->getStatusCode() . "\n";
