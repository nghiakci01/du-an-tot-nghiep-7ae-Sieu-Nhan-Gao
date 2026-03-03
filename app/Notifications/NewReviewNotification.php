<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    protected $review;

    public function __construct($review)
    {
        $this->review = $review;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_review',
            'product_name' => $this->review->product->name ?? 'N/A',
            'customer_name' => $this->review->user->name ?? 'Khách lẻ',
            'rating' => $this->review->rating,
            'message' => 'Có đánh giá mới ' . $this->review->rating . ' sao cho sản phẩm ' . ($this->review->product->name ?? 'N/A'),
            'link' => route('admin.reviews.index'),
        ];
    }
}
