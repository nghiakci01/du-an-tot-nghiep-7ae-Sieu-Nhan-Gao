<?php

namespace App\Services;

use App\Mail\OrderShippedMail;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class OrderService
{

    public function __construct()
    {
    }

    /**
     * Update order status with history tracking and stock management
     */
    public function updateOrderStatus(Order $order, string $newStatus, ?User $user = null, ?string $note = null, ?\App\Models\OrderReturnRequest $returnRequest = null)
    {
        // Prevent transitioning an unpaid online order to progressive statuses
        if ($order->payment_method !== 'COD' && $order->payment_status !== 'paid') {
            if (!in_array($newStatus, [Order::STATUS_CANCELLED, Order::STATUS_FAILED])) {
                throw new Exception("Không thể chuyển trạng thái (sang {$newStatus}) do khách chưa hoàn tất thanh toán Online.");
            }
        }

        if (! $order->canTransitionTo($newStatus)) {
            throw new Exception("Không thể chuyển đổi trạng thái từ {$order->status} sang {$newStatus}");
        }

        if ($order->status === $newStatus) {
            return $order;
        }

        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $newStatus, $oldStatus, $user, $note, $returnRequest) {
            // update status
            $order->update(['status' => $newStatus]);

            // create history
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => $user ? $user->id : null,
                'previous_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => $note,
            ]);

            // Handle stock logic
            $this->handleStockAdjustment($order, $oldStatus, $newStatus, $returnRequest);

        });

        // Dispatch Database & Email Notifications
        try {
            if ($order->user) {
                // If the order belongs to a registered user, send via Notification system (DB + Mail)
                $order->user->notify(new \App\Notifications\OrderStatusNotification($order, $oldStatus, $newStatus));
            } elseif ($order->email) {
                // For guest orders, send direct email based on status
                match ($newStatus) {
                    Order::STATUS_SHIPPED => Mail::to($order->email)->send(new OrderShippedMail($order)),
                    Order::STATUS_COMPLETED => Mail::to($order->email)->send(new \App\Mail\OrderCompletedMail($order)),
                    default => null,
                };
            }

            // Send cancellation email (for both registered & guest users)
            if ($newStatus === Order::STATUS_CANCELLED) {
                $email = $order->email ?? ($order->user ? $order->user->email : null);
                if ($email) {
                    Mail::to($email)->send(new \App\Mail\OrderCancelledMail($order, $note ?? ''));
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to send status notification for order '.$order->id.': '.$e->getMessage());
        }

        return $order;
    }

    protected function handleStockAdjustment(Order $order, $oldStatus, $newStatus, ?\App\Models\OrderReturnRequest $returnRequest = null)
    {
        // 1. If transition TO Cancelled/Returned/Failed -> Restore Stock (if coming from a status where stock was held)
        // Assuming stock IS deducted on Order Placement (which is standard).
        // So any transition TO Cancelled/Returned/Failed requires restoration.
        // UNLESS the old status was ALSO Cancelled/Returned/Failed (which shouldn't happen due to transition rules, but good to be safe)

        $isCancelledState = in_array($newStatus, [Order::STATUS_CANCELLED, Order::STATUS_RETURNED, Order::STATUS_PARTIALLY_RETURNED, Order::STATUS_FAILED]);
        $wasCancelledState = in_array($oldStatus, [Order::STATUS_CANCELLED, Order::STATUS_RETURNED, Order::STATUS_PARTIALLY_RETURNED, Order::STATUS_FAILED]);

        if ($isCancelledState && ! $wasCancelledState) {
            $this->restoreStock($order, $returnRequest);
        }

        // 2. If transition FROM Cancelled/Returned/Failed TO Processing statuses -> Deduct Stock again
        // (Just in case specific admin flow allows un-cancelling, though usually hard. But good to handle)
        if (! $isCancelledState && $wasCancelledState) {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    // Sử dụng lockForUpdate để tránh Race Condition khi trừ kho lại
                    $variant = \App\Models\ProductVariant::where('id', $item->variant_id)->lockForUpdate()->first();
                    if ($variant && $variant->stock_quantity >= $item->quantity) {
                        $variant->decrement('stock_quantity', $item->quantity);
                    }
                }
            }
        }
    }

    /**
     * Khôi phục số lượng tồn kho cho các sản phẩm trong đơn hàng
     */
    protected function restoreStock(Order $order, ?\App\Models\OrderReturnRequest $returnRequest = null)
    {
        if ($returnRequest && $returnRequest->items->count() > 0) {
            // Restore ONLY specific items from return request
            foreach ($returnRequest->items as $returnItem) {
                $orderItem = $returnItem->orderItem;
                if ($orderItem && $orderItem->variant_id) {
                    $variant = \App\Models\ProductVariant::where('id', $orderItem->variant_id)->lockForUpdate()->first();
                    if ($variant) {
                        $variant->increment('stock_quantity', $returnItem->quantity);
                        Log::info("Restored {$returnItem->quantity} units for variant #{$orderItem->variant_id} (ReturnRequest #{$returnRequest->id})");
                    }
                }
            }
        } else {
            // Restore ALL items in the order (e.g. Cancelled status)
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    // Sử dụng lockForUpdate để tránh Race Condition
                    $variant = \App\Models\ProductVariant::where('id', $item->variant_id)->lockForUpdate()->first();
                    if ($variant) {
                        $variant->increment('stock_quantity', $item->quantity);
                        Log::info("Restored {$item->quantity} units for variant #{$item->variant_id} (Full restoration for order #{$order->id})");
                    }
                }
            }
        }
    }

}
