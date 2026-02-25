<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'province',
        'address',
        'status',
        'total_price',
        'coupon_code',
        'discount_amount',
        'shipping_fee',
        'final_total',
        'payment_method',
        'payment_status',
        'transaction_id',
        'shipping_address',
        'note',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';
    const STATUS_RETURNED = 'returned';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class)->orderBy('created_at', 'desc');
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Chờ xác nhận',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_SHIPPED => 'Đang giao hàng',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy',
            self::STATUS_FAILED => 'Thất bại',
            self::STATUS_RETURNED => 'Đã trả hàng',
            default => 'Không xác định',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_CONFIRMED => 'bg-info',
            self::STATUS_SHIPPED => 'bg-primary',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_CANCELLED => 'bg-danger',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_RETURNED => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    public function getAllowedTransitions()
    {
        return match ($this->status) {
            self::STATUS_PENDING => [self::STATUS_CONFIRMED, self::STATUS_CANCELLED],
            self::STATUS_CONFIRMED => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED => [self::STATUS_COMPLETED, self::STATUS_RETURNED, self::STATUS_FAILED],
            self::STATUS_COMPLETED => [self::STATUS_RETURNED],
            self::STATUS_CANCELLED => [],
            self::STATUS_FAILED => [],
            self::STATUS_RETURNED => [],
            default => [],
        };
    }

    public function canTransitionTo($newStatus)
    {
        // Allow strictly expected next steps OR staying same (idempotent)
        if ($this->status === $newStatus) {
            return true;
        }

        return in_array($newStatus, $this->getAllowedTransitions());
    }
}
