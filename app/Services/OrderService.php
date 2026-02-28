<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShippedMail;
use Exception;

class OrderService
{
    /**
     * Update order status with history tracking and stock management
     */
    public function updateOrderStatus(Order $order, string $newStatus, ?User $user = null, ?string $note = null)
    {
        if (!$order->canTransitionTo($newStatus)) {
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
                'note' => $note
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
                \Log::error('Failed to send shipped email for order ' . $order->id . ': ' . $e->getMessage());
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

        if ($isCancelledState && !$wasCancelledState) {
            $this->restoreStock($order);
        }

        // 2. If transition FROM Cancelled/Returned/Failed TO Processing statuses -> Deduct Stock again
        // (Just in case specific admin flow allows un-cancelling, though usually hard. But good to handle)
        if (!$isCancelledState && $wasCancelledState) {
            $this->deductStock($order);
        }
    }

    protected function restoreStock(Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock_quantity', $item->quantity);
            }
        }
    }

    protected function deductStock(Order $order)
    {
        foreach ($order->items as $item) {
            if ($item->variant) {
                if ($item->variant->stock_quantity >= $item->quantity) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                } else {
                    // potentially throw exception or allow negative if configured? 
                    // For now, let's just decrement, or we could strict check.
                    // Assuming admin overrides, we just decrement.
                    $item->variant->decrement('stock_quantity', $item->quantity);
                }
            }
        }
    }
}
