<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('??????')),
            'type' => $this->faker->randomElement(['fixed', 'percentage']),
            'value' => $this->faker->numberBetween(10, 50),
            'min_order_amount' => 1000,
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addWeek(),
            'usage_limit' => 100,
            'used_count' => 0,
        ];
    }
}
