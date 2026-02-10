<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
        'description',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'used_count' => 'integer',
        'usage_limit' => 'integer',
    ];

    /**
     * Scope to filter active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter valid coupons (active, not expired, not reached usage limit)
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereRaw('used_count < usage_limit');
            });
    }

    /**
     * Check if coupon is valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        if ($this->hasReachedUsageLimit()) {
            return false;
        }

        return true;
    }

    /**
     * Check if coupon is expired
     */
    public function isExpired(): bool
    {
        if ($this->start_date && Carbon::parse($this->start_date)->isFuture()) {
            return true;
        }

        if ($this->end_date && Carbon::parse($this->end_date)->isPast()) {
            return true;
        }

        return false;
    }

    /**
     * Check if coupon has reached usage limit
     */
    public function hasReachedUsageLimit(): bool
    {
        if ($this->usage_limit === null) {
            return false;
        }

        return $this->used_count >= $this->usage_limit;
    }

    /**
     * Calculate discount amount for given order amount
     */
    public function calculateDiscount(float $orderAmount): float
    {
        if ($orderAmount < ($this->min_order_amount ?? 0)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $orderAmount * ($this->value / 100);
            
            if ($this->max_discount_amount) {
                $discount = min($discount, $this->max_discount_amount);
            }
            
            return round($discount, 2);
        }

        // Fixed amount
        return min($this->value, $orderAmount);
    }

    /**
     * Get status badge for display
     */
    public function getStatusBadge(): string
    {
        if (!$this->is_active) {
            return '<span class="badge bg-warning">Không hoạt động</span>';
        }

        if ($this->hasReachedUsageLimit()) {
            return '<span class="badge bg-secondary">Hết lượt dùng</span>';
        }

        if ($this->isExpired()) {
            return '<span class="badge bg-danger">Hết hạn</span>';
        }

        return '<span class="badge bg-success">Hoạt động</span>';
    }

    /**
     * Get formatted value for display
     */
    public function getFormattedValue(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }

        return number_format($this->value, 0, ',', '.') . ' VNĐ';
    }
}
