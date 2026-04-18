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
            $order = $returnRequest->order;
            $oldStatus = $order->status;

            $returnRequest->update([
                'status'       => OrderReturnRequest::STATUS_APPROVED,
                'admin_note'   => $adminNote,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            // Determine if this is a full or partial return
            $totalOrderedQty = $order->items->sum('quantity');
            $totalReturnedQty = $returnRequest->items->sum('quantity');
            
            $newOrderStatus = ($totalReturnedQty >= $totalOrderedQty) 
                ? Order::STATUS_RETURNED 
                : Order::STATUS_PARTIALLY_RETURNED;

            $order->update(['status' => $newOrderStatus]);

            \App\Models\OrderHistory::create([
                'order_id'        => $order->id,
                'user_id'         => $processor->id,
                'previous_status' => $oldStatus,
                'new_status'      => $newOrderStatus,
                'note'            => 'Admin đã duyệt yêu cầu hoàn hàng: ' . $adminNote,
            ]);

            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_APPROVED));

            Log::info("Hoàn trả #{$returnRequest->id} đã được duyệt, đơn hàng #{$order->id} chuyển sang {$newOrderStatus}.");

            return $returnRequest;
        });
    }

    /**
     * Mark as shipping back (client sent the package)
     */
    public function markAsShipping(OrderReturnRequest $returnRequest, User $processor = null)
    {
        Log::info("Cập nhật trạng thái 'Đang vận chuyển về' cho yêu cầu #{$returnRequest->id}");
        $returnRequest->update([
            'status' => OrderReturnRequest::STATUS_SHIPPING_BACK,
            'processed_by' => $processor ? $processor->id : $returnRequest->processed_by,
            'processed_at' => now(),
        ]);

        Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_SHIPPING_BACK));

        return $returnRequest;
    }

    /**
     * Mark as received at warehouse
     */
    public function markAsReceived(OrderReturnRequest $returnRequest)
    {
        $returnRequest->update(['status' => OrderReturnRequest::STATUS_RECEIVED]);
        Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_RECEIVED));
        return $returnRequest;
    }

    /**
     * Reject a return request
     */
    public function reject(OrderReturnRequest $returnRequest, User $processor, string $adminNote = null)
    {
        return DB::transaction(function () use ($returnRequest, $processor, $adminNote) {
            $returnRequest->update([
                'status' => OrderReturnRequest::STATUS_REJECTED,
                'admin_note' => $adminNote,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_REJECTED));

            return $returnRequest;
        });
    }

    /**
     * Complete the return process (Refund or Exchange)
     */
    public function complete(OrderReturnRequest $returnRequest, User $processor)
    {
        if (!in_array($returnRequest->status, [OrderReturnRequest::STATUS_APPROVED, OrderReturnRequest::STATUS_SHIPPING_BACK, OrderReturnRequest::STATUS_RECEIVED])) {
            throw new \Exception('Yêu cầu không ở trạng thái hợp lệ để hoàn tất.');
        }

        return DB::transaction(function () use ($returnRequest, $processor) {
            // 1. Calculate Amount (for refund tracking)
            $amount = $returnRequest->items->reduce(function ($carry, $item) {
                return $carry + ($item->price * $item->quantity);
            }, 0);

            // 2. Branch Logic based on Type
            if ($returnRequest->type === OrderReturnRequest::TYPE_EXCHANGE) {
                $returnRequest->update([
                    'status'        => OrderReturnRequest::STATUS_EXCHAGED ?? 'exchanged',
                    'refund_amount' => 0, // No monetary refund for exchange
                    'processed_by'  => $processor->id,
                    'processed_at'  => now(),
                ]);
                $finalStatus = OrderReturnRequest::STATUS_EXCHANGED;
            } else {
                $returnRequest->update([
                    'status'        => OrderReturnRequest::STATUS_REFUNDED,
                    'refund_amount' => $amount,
                    'processed_by'  => $processor->id,
                    'processed_at'  => now(),
                ]);
                $finalStatus = OrderReturnRequest::STATUS_REFUNDED;

                // Refund to Wallet
                $user = $returnRequest->user;
                $oldBalance = $user->wallet_balance ?? 0;
                $newBalance = $oldBalance + $amount;
                $user->update(['wallet_balance' => $newBalance]);

                \App\Models\WalletTransaction::create([
                    'user_id'        => $user->id,
                    'type'           => 'credit',
                    'amount'         => $amount,
                    'balance_after'  => $newBalance,
                    'description'    => "Hoàn tiền cho yêu cầu trả hàng #{$returnRequest->id} của đơn hàng #{$returnRequest->order_id}",
                    'reference_type' => OrderReturnRequest::class,
                    'reference_id'   => $returnRequest->id,
                ]);
            }

            // 3. Update Original Order Status
            $order = $returnRequest->order;
            $totalOrderedQty = $order->items->sum('quantity');
            $totalReturnedQty = $returnRequest->items->sum('quantity');
            
            $newOrderStatus = ($totalReturnedQty >= $totalOrderedQty) 
                ? Order::STATUS_RETURNED 
                : Order::STATUS_PARTIALLY_RETURNED;
            
            $this->orderService->updateOrderStatus(
                $order, 
                $newOrderStatus, 
                $processor, 
                'Hệ thống hoàn tất quy trình ' . ($returnRequest->type === 'exchange' ? 'đổi hàng' : 'hoàn tiền') . '.',
                $returnRequest
            );

            if ($returnRequest->type === OrderReturnRequest::TYPE_REFUND) {
                $paymentStatus = ($totalReturnedQty >= $totalOrderedQty) ? 'refunded' : 'partially_refunded';
                $order->update(['payment_status' => $paymentStatus]);
            }

            // 4. Notify User
            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, $finalStatus));

            Log::info("Hoàn hàng hoàn tất (#{$finalStatus}) cho đơn hàng #{$returnRequest->order_id}. Type: {$returnRequest->type}");

            return $returnRequest;
        });
    }
}
