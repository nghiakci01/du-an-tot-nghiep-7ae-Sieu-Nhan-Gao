<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturnRequest extends Model
{
    use HasFactory;

    const TYPE_REFUND = 'refund';
    const TYPE_EXCHANGE = 'exchange';

    const REASON_WRONG_SIZE = 'wrong_size';
    const REASON_DISLIKED = 'disliked';
    const REASON_DEFECTIVE = 'defective';
    const REASON_OTHER = 'other';

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SHIPPING_BACK = 'shipping_back';
    const STATUS_RECEIVED = 'received';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_EXCHANGED = 'exchanged';

    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'reason_type',
        'reason',
        'return_method',
        'note',
        'images',
        'videos',
        'shipping_info',
        'shipping_proof',
        'status',
        'admin_note',
        'refund_amount',
        'bank_name',
        'bank_bin',
        'account_number',
        'account_name',
        'processed_by',
        'processed_at',
        'tracking_code',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'processed_at' => 'datetime',
        'refund_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function items()
    {
        return $this->hasMany(OrderReturnItem::class, 'order_return_request_id');
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isShippingBack()
    {
        return $this->status === self::STATUS_SHIPPING_BACK;
    }

    public function isReceived()
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    public function isRefunded()
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function isExchanged()
    {
        return $this->status === self::STATUS_EXCHANGED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCompleted()
    {
        return in_array($this->status, [self::STATUS_REFUNDED, self::STATUS_EXCHANGED]);
    }

    /**
     * Check if an order can be returned (within 7 days of completion)
     */
    public static function canBeReturned(Order $order)
    {
        if ($order->status !== Order::STATUS_COMPLETED) {
            return false;
        }

        // Ideally we check history for 'completed' date, fallback to updated_at
        $completionDate = $order->histories()
            ->where('new_status', Order::STATUS_COMPLETED)
            ->latest()
            ->first()?->created_at ?? $order->updated_at;

        return $completionDate->diffInDays(now()) <= 7;
    }

    public function getTypeTextAttribute()
    {
        return match($this->type) {
            self::TYPE_REFUND => 'Hoàn tiền',
            self::TYPE_EXCHANGE => 'Đổi hàng',
            default => 'Không xác định'
        };
    }

    public function getReasonTypeTextAttribute()
    {
        return match($this->reason_type) {
            self::REASON_WRONG_SIZE => 'Sai kích cỡ',
            self::REASON_DISLIKED => 'Không ưng ý',
            self::REASON_DEFECTIVE => 'Hàng lỗi/Hư hỏng',
            self::REASON_OTHER => 'Khác',
            default => 'Không xác định'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Chờ duyệt',
            self::STATUS_APPROVED => 'Đã duyệt/Chờ hàng',
            self::STATUS_REJECTED => 'Bị từ chối',
            self::STATUS_SHIPPING_BACK => 'Đang gửi hàng về',
            self::STATUS_RECEIVED => 'Đã nhận tại kho',
            self::STATUS_REFUNDED => 'Đã hoàn tiền',
            self::STATUS_EXCHANGED => 'Đã đổi hàng',
            default => 'Không xác định'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_APPROVED => 'bg-info',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_SHIPPING_BACK => 'bg-primary',
            self::STATUS_RECEIVED => 'bg-dark',
            self::STATUS_REFUNDED => 'bg-success',
            self::STATUS_EXCHANGED => 'bg-success',
            default => 'bg-secondary'
        };
    }
    
    public function getReturnMethodTextAttribute()
    {
        return match($this->return_method) {
            'at_home' => 'Shipper đến lấy hàng',
            'at_post_office' => 'Tự mang đến bưu cục',
            default => 'Chưa chọn'
        };
    }
}
