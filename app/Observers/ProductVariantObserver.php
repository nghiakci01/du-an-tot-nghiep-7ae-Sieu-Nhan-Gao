<?php

namespace App\Observers;

use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class ProductVariantObserver
{
    /**
     * Handle the ProductVariant "updated" event.
     */
    public function updated(ProductVariant $variant): void
    {
        // Only trigger if stock_quantity was decreased
        if ($variant->isDirty('stock_quantity')) {
            $oldStock = $variant->getOriginal('stock_quantity');
            $newStock = $variant->stock_quantity;

            // Only notify if it went from OK to LOW, or if it stayed/went deeper into LOW
            $threshold = $variant->alert_threshold ?? 5;

            if ($newStock <= $threshold && ($oldStock > $threshold || $newStock < $oldStock)) {
                $admins = User::getAdmins();
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new LowStockNotification($variant));
                }
            }
        }
    }
}
