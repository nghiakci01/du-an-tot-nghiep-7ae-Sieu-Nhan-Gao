<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhnWebhookFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_ghn_webhook_updates_order_status()
    {
        // 1. Setup - Create an order
        $order = Order::create([
            'name' => 'Test Customer',
            'phone' => '0912345678',
            'province' => 'Hồ Chí Minh',
            'address' => '123 Test Street',
            'status' => Order::STATUS_CONFIRMED,
            'total_price' => 500000,
            'final_total' => 500000,
            'payment_method' => 'COD',
            'payment_status' => 'pending',
            'shipping_address' => '123 Test Street, HCM',
            'tracking_code' => 'GHN123456',
            'shipping_provider' => 'ghn'
        ]);

        // 2. Mock GHN Webhook Request (Delivered)
        $payload = [
            'order_code' => 'GHN123456',
            'status' => 'delivered',
            'client_order_code' => (string) $order->id
        ];

        $response = $this->postJson('/api/webhooks/shipping/ghn', $payload);

        // 3. Assertions
        $response->assertStatus(200);
        $order->refresh();
        
        $this->assertEquals(Order::STATUS_COMPLETED, $order->status);
        $this->assertEquals('paid', $order->payment_status);
    }

    public function test_ghn_webhook_updates_to_shipped()
    {
        $order = Order::create([
            'name' => 'Test Customer',
            'phone' => '0912345678',
            'province' => 'Hồ Chí Minh',
            'address' => '123 Test Street',
            'status' => Order::STATUS_CONFIRMED,
            'total_price' => 500000,
            'final_total' => 500000,
            'payment_method' => 'COD',
            'payment_status' => 'pending',
            'shipping_address' => '123 Test Street, HCM',
            'tracking_code' => 'GHN789012',
            'shipping_provider' => 'ghn'
        ]);

        $payload = [
            'order_code' => 'GHN789012',
            'status' => 'delivering',
            'client_order_code' => (string) $order->id
        ];

        $response = $this->postJson('/api/webhooks/shipping/ghn', $payload);

        $response->assertStatus(200);
        $order->refresh();
        
        $this->assertEquals(Order::STATUS_SHIPPED, $order->status);
    }
}
