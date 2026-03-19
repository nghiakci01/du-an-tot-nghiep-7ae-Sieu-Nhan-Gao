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
    protected $walletService;
    protected $orderService;

    public function __construct(WalletService $walletService, OrderService $orderService)
    {
        $this->walletService = $walletService;
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
    public function markAsShipping(OrderReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => 'shipping']);
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

            // 2. Credit Wallet
            $this->walletService->credit(
                $returnRequest->user, 
                $returnRequest->refund_amount, 
                'Hoàn tiền đơn hàng #' . $returnRequest->order_id . ' (Yêu cầu trả hàng #' . $returnRequest->id . ')', 
                'order_return', 
                $returnRequest->id
            );

            // 3. Update Order Status & Payment Status
            $order = $returnRequest->order;
            $this->orderService->updateOrderStatus(
                $order, 
                Order::STATUS_RETURNED, 
                $processor, 
                'Hệ thống đã tự động hoàn lại tiền vào ví khách hàng.'
            );

            $order->update(['payment_status' => 'refunded']);

            // 4. Notify User
            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, 'completed'));

            Log::info("Hoàn trả hoàn thành thành công cho đơn hàng #{$returnRequest->order_id}");

            return $returnRequest;
        });
    }
}
