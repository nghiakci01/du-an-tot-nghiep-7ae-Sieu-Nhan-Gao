<?php

namespace App\Services;

use App\Mail\OrderShippedMail;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    /**
     * Update order status with history tracking and stock management
     */
    public function updateOrderStatus(Order $order, string $newStatus, ?User $user = null, ?string $note = null)
    {
        if (! $order->canTransitionTo($newStatus)) {
            throw new Exception("Không thể chuyển đổi trạng thái từ {$order->status} sang {$newStatus}");
        }

        if ($order->status === $newStatus) {
            return $order;
        }

        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $newStatus, $oldStatus, $user, $note) {
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
            $this->handleStockAdjustment($order, $oldStatus, $newStatus);
        });

        // Send email if shipped
        if ($newStatus === Order::STATUS_SHIPPED) {
            try {
                $email = $order->user_email ?? ($order->user->email ?? null);
                if ($email) {
                    Mail::to($email)->send(new OrderShippedMail($order));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send shipped email for order '.$order->id.': '.$e->getMessage());
            }
        }

        return $order;
    }

    protected function handleStockAdjustment(Order $order, $oldStatus, $newStatus)
    {
        // 1. If transition TO Cancelled/Returned/Failed -> Restore Stock (if coming from a status where stock was held)
        // Assuming stock IS deducted on Order Placement (which is standard).
        // So any transition TO Cancelled/Returned/Failed requires restoration.
        // UNLESS the old status was ALSO Cancelled/Returned/Failed (which shouldn't happen due to transition rules, but good to be safe)

        $isCancelledState = in_array($newStatus, [Order::STATUS_CANCELLED, Order::STATUS_RETURNED, Order::STATUS_FAILED]);
        $wasCancelledState = in_array($oldStatus, [Order::STATUS_CANCELLED, Order::STATUS_RETURNED, Order::STATUS_FAILED]);

        if ($isCancelledState && ! $wasCancelledState) {
            $this->restoreStock($order);
        }

        // 2. If transition FROM Cancelled/Returned/Failed TO Processing statuses -> Deduct Stock again
        // (Just in case specific admin flow allows un-cancelling, though usually hard. But good to handle)
        if (! $isCancelledState && $wasCancelledState) {
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
