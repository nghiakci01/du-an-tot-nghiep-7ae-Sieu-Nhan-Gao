<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturnRequest;

$user = User::where('email', 'randal.conroy@example.org')->first();
if (!$user) {
    echo "User not found\n";
    exit(1);
}

$product = App\Models\Product::first();
$variant = App\Models\ProductVariant::where('product_id', $product->id)->first();

if (!$product || !$variant) {
    echo "Product or Variant not found\n";
    exit(1);
}

// Ensure no previous return request exists for this user/order to keep test clean
$order = Order::create([
    'user_id' => $user->id,
    'order_number' => 'E2E-RETURN-' . time(),
    'status' => Order::STATUS_COMPLETED,
    'payment_status' => 'paid',
    'total_price' => $variant->price,
    'final_total' => $variant->price,
    'name' => $user->name,
    'email' => $user->email,
    'phone' => '0912345678',
    'address' => 'Test Address',
    'province' => 'Test Province',
    'shipping_address' => 'Test Address, Test Province',
    'shipping_provider' => 'default',
    'payment_method' => 'COD'
]);

OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'variant_id' => $variant->id,
    'product_name' => $product->name,
    'variant_name' => $variant->variant_name ?? 'Default',
    'quantity' => 1,
    'price' => $variant->price,
    'cost_price' => $variant->price * 0.8
]);

echo "Created Order ID: " . $order->id . " for " . $user->email . "\n";
