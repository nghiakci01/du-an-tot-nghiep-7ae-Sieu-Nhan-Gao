<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\User;

// 1. Đảm bảo có Shipper (elite22326@gmail.com)
$shipper = User::where('email', 'elite22326@gmail.com')->first();
if (!$shipper) {
    $shipper = User::create([
        'name' => 'Shipper Test',
        'email' => 'elite22326@gmail.com',
        'password' => bcrypt('password'),
        'role' => User::ROLE_STAFF,
    ]);
} else if ($shipper->role !== User::ROLE_STAFF) {
    $shipper->update(['role' => User::ROLE_STAFF]);
}

// 2. Chuẩn bị Đơn hàng mẫu
$order = Order::orderBy('id', 'desc')->first();
if (!$order) {
    // Nếu chưa có đơn nào, tạo mới
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
        'shipper_id' => null,
        'delivery_note' => null
    ]);
}

echo "SUCCESS|OrderID:{$order->id}|ShipperEmail:{$shipper->email}\n";
