<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'reason',
        'note',
        'images',
        'videos',
        'shipping_info',
        'shipping_proof',
        'status',
        'admin_note',
        'refund_amount',
        'processed_by',
        'processed_at',
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

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isShipping()
    {
        return $this->status === 'shipping';
    }

    public function isReceived()
    {
        return $this->status === 'received';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt/Chờ hàng',
            'shipping' => 'Đang vận chuyển',
            'received' => 'Đã nhận tại kho',
            'completed' => 'Đã hoàn tất',
            'rejected' => 'Bị từ chối',
            default => 'Không xác định'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning text-dark',
            'approved' => 'bg-info',
            'shipping' => 'bg-primary',
            'received' => 'bg-dark',
            'completed' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
}
