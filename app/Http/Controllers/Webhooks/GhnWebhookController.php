<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GhnWebhookController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function handle(Request $request)
    {
        $payload = $request->all();
        
        Log::info('GHN Webhook received', ['payload' => $payload]);

        $ghnOrderCode = $payload['order_code'] ?? null;
        $orderId = $payload['client_order_code'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$ghnOrderCode) {
            return response()->json(['message' => 'Missing order_code'], 400);
        }

        // Tìm đơn hàng theo client_order_code hoặc tracking_code
        $order = null;
        if ($orderId) {
            $order = Order::find($orderId);
        }
        
        if (!$order) {
            $order = Order::where('tracking_code', $ghnOrderCode)->first();
        }

        if (!$order) {
            Log::warning('GHN Webhook: Order not found', ['order_code' => $ghnOrderCode, 'client_order_code' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $newStatus = $this->mapGhnStatusToInternal($status);

        if ($newStatus && $order->status !== $newStatus) {
            try {
                $this->orderService->updateOrderStatus(
                    $order, 
                    $newStatus, 
                    null, 
                    "Cập nhật tự động từ GHN (Trạng thái: $status)"
                );
                Log::info("GHN Webhook: Updated order #{$order->id} to $newStatus");
            } catch (\Exception $e) {
                Log::error("GHN Webhook: Failed to update order #{$order->id}", ['error' => $e->getMessage()]);
            }
        }

        return response()->json(['message' => 'OK']);
    }

    protected function mapGhnStatusToInternal(string $ghnStatus): ?string
    {
        return match ($ghnStatus) {
            'ready_to_pick', 'picking', 'picked' => Order::STATUS_CONFIRMED,
            'storing', 'transporting', 'sorting', 'delivering' => Order::STATUS_SHIPPED,
            'delivered' => Order::STATUS_COMPLETED,
            'delivery_fail' => Order::STATUS_FAILED,
            'cancel' => Order::STATUS_CANCELLED,
            'returning', 'returned' => Order::STATUS_RETURNED,
            default => null,
        };
    }
}
