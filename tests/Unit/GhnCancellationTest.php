<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\Shipping\GhnShippingProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GhnCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancellation_triggers_ghn_api()
    {
        // 1. Mock GHN API
        Http::fake([
            '*/shiip/public-api/v2/shipping-order/cancel' => Http::response(['code' => 200, 'message' => 'Success'], 200),
            '*/shiip/public-api/v2/master-data/province' => Http::response(['data' => []], 200)
        ]);

        // 2. Setup Order
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
            'tracking_code' => 'GHN_CANCEL_ME',
            'shipping_provider' => 'ghn'
        ]);

        // 3. Trigger cancellation via OrderService
        $service = app(OrderService::class);
        $service->updateOrderStatus($order, Order::STATUS_CANCELLED);

        // 4. Assertions
        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/shipping-order/cancel') &&
                   $request['order_codes'] === ['GHN_CANCEL_ME'];
        });
        
        $order->refresh();
        $this->assertEquals(Order::STATUS_CANCELLED, $order->status);
    }
}
