<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderHistory;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', User::ROLE_USER)->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }

        $shippers = User::where('role', User::ROLE_STAFF)->get();
        
        if ($users->isEmpty()) {
            return;
        }

        $variants = ProductVariant::with('product')->get();

        if ($variants->isEmpty()) {
            return;
        }

        $statuses = [
            Order::STATUS_PENDING,
            Order::STATUS_CONFIRMED,
            Order::STATUS_SHIPPED,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
            Order::STATUS_FAILED,
        ];

        $provinces = ['Hà Nội', 'TP Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ'];

        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $province = $provinces[array_rand($provinces)];
            
            DB::transaction(function () use ($user, $status, $province, $variants, $shippers, $i) {
                // Determine payment status
                $paymentMethod = collect(['COD', 'BANK_TRANSFER', 'VNPAY'])->random();
                $paymentStatus = 'pending';
                
                if ($status === Order::STATUS_COMPLETED) {
                    $paymentStatus = 'paid';
                } elseif ($paymentMethod !== 'COD' && !in_array($status, [Order::STATUS_PENDING, Order::STATUS_CANCELLED])) {
                    $paymentStatus = 'paid';
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '09' . fake()->numerify('########'),
                    'province' => $province,
                    'address' => 'Số ' . fake()->numberBetween(1, 200) . ' Đường ABC, Phường XYZ',
                    'status' => $status,
                    'total_price' => 0,
                    'discount_amount' => 0,
                    'shipping_fee' => 30000,
                    'final_total' => 30000,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'shipping_address' => 'Số ' . fake()->numberBetween(1, 200) . ' Đường ABC, Phường XYZ, ' . $province,
                    'note' => $i % 5 === 0 ? 'Giao hàng giờ hành chính' : null,
                ]);

                // Add 1-3 items
                $numItems = fake()->numberBetween(1, 3);
                $totalPrice = 0;
                $orderVariants = $variants->random($numItems);

                foreach ($orderVariants as $variant) {
                    $qty = fake()->numberBetween(1, 2);
                    $price = $variant->price ?? ($variant->product->price ?? 100000);
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $variant->product_id,
                        'variant_id' => $variant->id,
                        'quantity' => $qty,
                        'price' => $price,
                        'cost_price' => $price * 0.7,
                    ]);

                    $totalPrice += $price * $qty;
                }

                $finalTotal = $totalPrice + 30000;
                $order->update([
                    'total_price' => $totalPrice,
                    'final_total' => $finalTotal
                ]);

                // Add some history
                OrderHistory::create([
                    'order_id' => $order->id,
                    'new_status' => Order::STATUS_PENDING,
                    'note' => 'Đơn hàng được tạo tự động bởi seeder.',
                ]);

                if ($status !== Order::STATUS_PENDING) {
                    OrderHistory::create([
                        'order_id' => $order->id,
                        'previous_status' => Order::STATUS_PENDING,
                        'new_status' => $status,
                        'note' => 'Cập nhật trạng thái tự động bởi seeder.',
                        'user_id' => 1, // Assume admin
                    ]);
                }
            });
        }
    }
}
