<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'phone' => '0912345678',
            'province' => 'Hà Nội',
            'address' => $this->faker->address,
            'status' => Order::STATUS_PENDING,
            'total_price' => 500000,
            'discount_amount' => 0,
            'shipping_fee' => 30000,
            'final_total' => 530000,
            'payment_method' => 'COD',
            'shipping_address' => 'Sample Address',
        ];
    }
}
