<?php

namespace App\Services;

use App\Mail\ReturnStatusMail;
use App\Models\Order;
use App\Models\OrderReturnRequest;
use App\Models\User;
use App\Notifications\OrderReturnRequestStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            $oldStatus = $returnRequest->status;

            $returnRequest->update([
                'status'       => OrderReturnRequest::STATUS_APPROVED,
                'admin_note'   => $adminNote,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            // Ghi audit trail
            \App\Models\OrderReturnHistory::create([
                'order_return_request_id' => $returnRequest->id,
                'user_id' => $processor->id,
                'previous_status' => $oldStatus,
                'new_status' => OrderReturnRequest::STATUS_APPROVED,
                'note' => 'Admin đã duyệt yêu cầu hoàn hàng: ' . $adminNote,
            ]);



            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_APPROVED));

            // Gửi email thông báo
            Mail::to($returnRequest->user->email)->send(new ReturnStatusMail($returnRequest, OrderReturnRequest::STATUS_APPROVED));

            Log::info("Hoàn trả #{$returnRequest->id} đã được duyệt cho đơn hàng #{$returnRequest->order_id}.");

            return $returnRequest;
        });
    }

    /**
     * Mark as shipping back (client sent the package)
     */
    public function markAsShipping(OrderReturnRequest $returnRequest, User $processor = null)
    {
        Log::info("Cập nhật trạng thái 'Đang vận chuyển về' cho yêu cầu #{$returnRequest->id}");
        $oldStatus = $returnRequest->status;
        $returnRequest->update([
            'status' => OrderReturnRequest::STATUS_SHIPPING_BACK,
            'processed_by' => $processor ? $processor->id : $returnRequest->processed_by,
            'processed_at' => now(),
        ]);

        // Ghi audit trail
        \App\Models\OrderReturnHistory::create([
            'order_return_request_id' => $returnRequest->id,
            'user_id' => $processor ? $processor->id : null,
            'previous_status' => $oldStatus,
            'new_status' => OrderReturnRequest::STATUS_SHIPPING_BACK,
            'note' => 'Khách hàng đã gửi hàng trả về',
        ]);

        Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_SHIPPING_BACK));

        // Gửi email thông báo
        Mail::to($returnRequest->user->email)->send(new ReturnStatusMail($returnRequest, OrderReturnRequest::STATUS_SHIPPING_BACK));

        return $returnRequest;
    }

    /**
     * Mark as received at warehouse
     */
    public function markAsReceived(OrderReturnRequest $returnRequest)
    {
        $oldStatus = $returnRequest->status;
        $returnRequest->update([
            'status' => OrderReturnRequest::STATUS_RECEIVED,
            'inspection_date' => now(), // Ghi ngày kiểm tra
        ]);

        // Ghi audit trail
        \App\Models\OrderReturnHistory::create([
            'order_return_request_id' => $returnRequest->id,
            'user_id' => null, // System action
            'previous_status' => $oldStatus,
            'new_status' => OrderReturnRequest::STATUS_RECEIVED,
            'note' => 'Hàng trả về đã nhận tại kho và kiểm tra',
        ]);

        Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_RECEIVED));

        // Gửi email thông báo
        Mail::to($returnRequest->user->email)->send(new ReturnStatusMail($returnRequest, OrderReturnRequest::STATUS_RECEIVED));

        return $returnRequest;
    }

    /**
     * Reject a return request
     */
    public function reject(OrderReturnRequest $returnRequest, User $processor, string $adminNote = null)
    {
        return DB::transaction(function () use ($returnRequest, $processor, $adminNote) {
            $oldStatus = $returnRequest->status;

            $returnRequest->update([
                'status' => OrderReturnRequest::STATUS_REJECTED,
                'admin_note' => $adminNote,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            // Ghi audit trail
            \App\Models\OrderReturnHistory::create([
                'order_return_request_id' => $returnRequest->id,
                'user_id' => $processor->id,
                'previous_status' => $oldStatus,
                'new_status' => OrderReturnRequest::STATUS_REJECTED,
                'note' => 'Admin đã từ chối yêu cầu hoàn hàng: ' . $adminNote,
            ]);

            Notification::send($returnRequest->user, new OrderReturnRequestStatusNotification($returnRequest, OrderReturnRequest::STATUS_REJECTED));

            // Gửi email thông báo
            Mail::to($returnRequest->user->email)->send(new ReturnStatusMail($returnRequest, OrderReturnRequest::STATUS_REJECTED));

            return $returnRequest;
        });
    }

    /**
     * Complete the return process (Refund or Exchange)
     */
    public function complete(OrderReturnRequest $returnRequest, User $processor)
    {
        // Chỉ cho phép hoàn tất khi đã nhận hàng (STATUS_RECEIVED)
        if ($returnRequest->status !== OrderReturnRequest::STATUS_RECEIVED) {
            throw new \Exception('Không thể hoàn tất yêu cầu trả hàng trước khi nhận hàng tại kho.');
        }

        return DB::transaction(function () use ($returnRequest, $processor) {
            // 1. Calculate Amount (trừ phí ship và giảm giá)
            $baseAmount = $returnRequest->items->reduce(function ($carry, $item) {
                return $carry + ($item->price * $item->quantity);
            }, 0);
            $shippingFee = $returnRequest->order->shipping_fee ?? 0;
            $discount = $returnRequest->order->discount_amount ?? 0;
            $amount = max(0, $baseAmount - $shippingFee - $discount); // Đảm bảo không âm

            // 2. Branch Logic based on Type
            if ($returnRequest->type === OrderReturnRequest::TYPE_EXCHANGE) {
                $returnRequest->update([
                    'status'        => OrderReturnRequest::STATUS_EXCHANGED,
                    'refund_amount' => 0, // No monetary refund for exchange
                    'processed_by'  => $processor->id,
                    'processed_at'  => now(),
                ]);
                $finalStatus = OrderReturnRequest::STATUS_EXCHANGED;

                // Ghi audit trail
                \App\Models\OrderReturnHistory::create([
                    'order_return_request_id' => $returnRequest->id,
                    'user_id' => $processor->id,
                    'previous_status' => $returnRequest->getOriginal('status'),
                    'new_status' => OrderReturnRequest::STATUS_EXCHANGED,
                    'note' => 'Hoàn tất quy trình đổi hàng',
                ]);
            } else {
                $returnRequest->update([
                    'status'        => OrderReturnRequest::STATUS_REFUNDED,
                    'refund_amount' => $amount,
                    'processed_by'  => $processor->id,
                    'processed_at'  => now(),
                ]);
                $finalStatus = OrderReturnRequest::STATUS_REFUNDED;

                // Ghi audit trail
                \App\Models\OrderReturnHistory::create([
                    'order_return_request_id' => $returnRequest->id,
                    'user_id' => $processor->id,
                    'previous_status' => $returnRequest->getOriginal('status'),
                    'new_status' => OrderReturnRequest::STATUS_REFUNDED,
                    'note' => 'Hoàn tất quy trình hoàn tiền, số tiền: ' . $amount,
                ]);

                // Refund dựa trên phương thức thanh toán gốc
                $user = $returnRequest->user;
                $paymentMethod = strtolower($returnRequest->order->payment_method ?? 'cod');

                if ($paymentMethod === 'vnpay') {
                    // TODO: Tích hợp VNPAY refund API
                    Log::warning("Cần hoàn tiền qua VNPAY cho yêu cầu #{$returnRequest->id}, số tiền: {$amount}");
                    throw new \Exception('Hoàn tiền qua VNPAY chưa được triển khai. Vui lòng xử lý thủ công.');
                } elseif (in_array($paymentMethod, ['cod', 'bank_transfer', 'cash'])) {
                    // Hoàn vào ví
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
                } else {
                    Log::error("Phương thức thanh toán không xác định: '{$paymentMethod}' cho đơn hàng #{$returnRequest->order_id}");
                    throw new \Exception("Phương thức thanh toán '{$paymentMethod}' không được hỗ trợ cho hoàn tiền.");
                }
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

            // Gửi email thông báo
            Mail::to($returnRequest->user->email)->send(new ReturnStatusMail($returnRequest, $finalStatus));

            Log::info("Hoàn hàng hoàn tất (#{$finalStatus}) cho đơn hàng #{$returnRequest->order_id}. Type: {$returnRequest->type}");

            return $returnRequest;
        });
    }
}
