<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::first();
echo "Order ID: " . $order->id . "\n";
echo "Email: '" . $order->email . "'\n";
echo "Phone: '" . $order->phone . "'\n";
