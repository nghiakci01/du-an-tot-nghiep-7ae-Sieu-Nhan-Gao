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
        'shipping_provider',
        'shipping_service_name',
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

    public function returnRequest()
    {
        return $this->hasOne(OrderReturnRequest::class);
    }


    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
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
            self::STATUS_PENDING => 'bg-light-warning',
            self::STATUS_CONFIRMED => 'bg-light-info',
            self::STATUS_SHIPPED => 'bg-light-primary',
            self::STATUS_COMPLETED => 'bg-light-success',
            self::STATUS_CANCELLED => 'bg-light-danger',
            self::STATUS_FAILED => 'bg-light-danger',
            self::STATUS_RETURNED => 'bg-light-warning',
            default => 'bg-light-secondary',
        };
    }

    public function getAllowedTransitions()
    {
        $transitions = match ($this->status) {
            self::STATUS_PENDING => [self::STATUS_CONFIRMED, self::STATUS_CANCELLED],
            self::STATUS_CONFIRMED => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED => [self::STATUS_COMPLETED, self::STATUS_RETURNED, self::STATUS_FAILED],
            self::STATUS_COMPLETED => [self::STATUS_RETURNED],
            self::STATUS_CANCELLED => [],
            self::STATUS_FAILED => [],
            self::STATUS_RETURNED => [],
            default => [],
        };

        if ($this->payment_method !== 'COD' && $this->payment_method !== 'CASH' && $this->payment_status !== 'paid') {
            $transitions = array_values(array_intersect($transitions, [self::STATUS_CANCELLED, self::STATUS_FAILED]));
        }

        return $transitions;
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
