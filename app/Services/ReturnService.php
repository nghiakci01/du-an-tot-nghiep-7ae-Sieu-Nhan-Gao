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
                'status'       => 'approved',
                'admin_note'   => $adminNote,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            // Tự động chuyển order sang trạng thái "Khách trả hàng"
            $order = $returnRequest->order;
            $oldStatus = $order->status;
            $order->update(['status' => Order::STATUS_RETURNED]);

            \App\Models\OrderHistory::create([
                'order_id'        => $order->id,
                'user_id'         => $processor->id,
                'previous_status' => $oldStatus,
                'new_status'      => Order::STATUS_RETURNED,
                'note'            => 'Admin đã duyệt yêu cầu hoàn hàng: ' . $adminNote,
            ]);

            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'approved'));

            Log::info("Hoàn trả #{$returnRequest->id} đã được duyệt, đơn hàng #{$order->id} chuyển sang returned.");

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
            $this->orderService->updateOrderStatus(
                $order, 
                Order::STATUS_RETURNED, 
                $processor, 
                'Hàng đã được hoàn trả thành công.'
            );

            $order->update(['payment_status' => 'refunded']);

            // 4. Notify User
            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'completed'));

            Log::info("Hoàn trả hoàn thành thành công cho đơn hàng #{$returnRequest->order_id}");

            return $returnRequest;
        });
    }
}
