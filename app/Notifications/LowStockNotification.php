<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $variant;

    public function __construct($variant)
    {
        $this->variant = $variant;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'low_stock',
            'product_name' => $this->variant->product->name ?? 'N/A',
            'sku' => $this->variant->sku,
            'stock' => $this->variant->stock_quantity,
            'threshold' => $this->variant->alert_threshold,
            'message' => 'Sản phẩm ' . ($this->variant->product->name ?? '') . ' (' . $this->variant->sku . ') sắp hết hàng (Còn: ' . $this->variant->stock_quantity . ')',
            'link' => route('admin.products.edit', $this->variant->product_id),
        ];
    }
}
