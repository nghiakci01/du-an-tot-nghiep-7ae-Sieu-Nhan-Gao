<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderReturnRequest;
use App\Models\User;
use App\Notifications\OrderReturnRequestStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReturnService
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Approve a return request
     */
    public function approve(OrderReturnRequest $returnRequest, User $processor, string $adminNote = null)
    {
        Log::info("Đang duyệt hoàn trả cho đơn hàng #{$returnRequest->order_id} bởi user #{$processor->id}");
        return DB::transaction(function () use ($returnRequest, $processor, $adminNote) {
            $returnRequest->update([
                'status' => 'approved',
                'admin_note' => $adminNote,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'approved'));

            Log::info("Hoàn trả #{$returnRequest->id} đã được duyệt.");

            return $returnRequest;
        });
    }

    /**
     * Mark as shipping
     */
    public function markAsShipping(OrderReturnRequest $returnRequest, User $processor)
    {
        Log::info("Cập nhật trạng thái 'Đang vận chuyển' cho yêu cầu #{$returnRequest->id}");
        $returnRequest->update([
            'status' => 'shipping',
            'processed_by' => $processor->id,
            'processed_at' => now(),
        ]);

        Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'shipping'));

        return $returnRequest;
    }

    /**
     * Mark as received
     */
    public function markAsReceived(OrderReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => 'received']);
        Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'received'));
        return $returnRequest;
    }

    /**
     * Reject a return request
     */
    public function reject(OrderReturnRequest $returnRequest, User $processor, string $adminNote = null)
    {
        return DB::transaction(function () use ($returnRequest, $processor, $adminNote) {
            $returnRequest->update([
                'status' => 'rejected',
                'admin_note' => $adminNote,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'rejected'));

            return $returnRequest;
        });
    }

    /**
     * Complete the refund process
     */
    public function complete(OrderReturnRequest $returnRequest, User $processor)
    {
        if (!in_array($returnRequest->status, ['approved', 'shipping', 'received'])) {
            throw new \Exception('Yêu cầu không ở trạng thái hợp lệ để hoàn tiền.');
        }

        return DB::transaction(function () use ($returnRequest, $processor) {
            // 1. Update Return Request
            $returnRequest->update([
                'status' => 'completed',
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);


            // 3. Update Order Status & Payment Status
            $order = $returnRequest->order;
            
            // Determine if this is a full or partial return
            $totalOrderedQty = $order->items->sum('quantity');
            $totalReturnedQty = $returnRequest->items->sum('quantity');
            
            $newOrderStatus = ($totalReturnedQty >= $totalOrderedQty) 
                ? Order::STATUS_RETURNED 
                : Order::STATUS_PARTIALLY_RETURNED;
            
            $this->orderService->updateOrderStatus(
                $order, 
                $newOrderStatus, 
                $processor, 
                'Hàng đã được hoàn trả thành công (Số lượng: ' . $totalReturnedQty . '/' . $totalOrderedQty . ').',
                $returnRequest
            );

            $paymentStatus = ($totalReturnedQty >= $totalOrderedQty) ? 'refunded' : 'partially_refunded';
            $order->update(['payment_status' => $paymentStatus]);

            // 4. Notify User
            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'completed'));

            Log::info("Hoàn trả hoàn tất cho đơn hàng #{$returnRequest->order_id}. Số lượng: {$totalReturnedQty}/{$totalOrderedQty}");

            return $returnRequest;
        });
    }
}
