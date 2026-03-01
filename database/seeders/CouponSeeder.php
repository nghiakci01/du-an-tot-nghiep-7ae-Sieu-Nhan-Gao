<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME50',
                'type' => 'fixed',
                'value' => 50000,
                'max_uses' => 100,
                'min_order_amount' => 200000,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'SALE10',
                'type' => 'percentage',
                'value' => 10,
                'max_uses' => 500,
                'min_order_amount' => 500000,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'ELITE2025',
                'type' => 'percentage',
                'value' => 20,
                'max_uses' => 50,
                'min_order_amount' => 1000000,
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
