<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$order = Order::orderBy('id', 'desc')->first();
if (!$order) {
    $order = Order::create([
        'user_id' => null,
        'name' => 'Khách Test Giao Hàng',
        'email' => 'customer@test.com',
        'phone' => '0987654321',
        'status' => Order::STATUS_CONFIRMED,
        'total_price' => 100000,
        'shipping_fee' => 20000,
        'final_total' => 120000,
        'shipping_address' => '123 Đường Test, Quận 1, TP.HCM',
        'payment_method' => 'COD',
        'payment_status' => 'pending',
    ]);
} else {
    $order->update([
        'status' => Order::STATUS_CONFIRMED,
    ]);
}

echo "SUCCESS|OrderID:{$order->id}\n";
