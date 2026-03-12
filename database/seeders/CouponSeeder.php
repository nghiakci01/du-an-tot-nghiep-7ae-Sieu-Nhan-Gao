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
                'usage_limit' => 100,
                'min_order_amount' => 200000,
                'end_date' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'SALE10',
                'type' => 'percentage',
                'value' => 10,
                'usage_limit' => 500,
                'min_order_amount' => 500000,
                'end_date' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'ELITE2025',
                'type' => 'percentage',
                'value' => 20,
                'usage_limit' => 50,
                'min_order_amount' => 1000000,
                'end_date' => now()->addMonths(1),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
