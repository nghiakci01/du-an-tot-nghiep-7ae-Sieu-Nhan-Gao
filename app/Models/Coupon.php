<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'user_id',
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
        'user_id' => 'integer',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'used_count' => 'integer',
        'usage_limit' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
        // Only check end_date for expiration
        if ($this->end_date && now()->isAfter($this->end_date->endOfDay())) {
            return true;
        }

        return false;
    }

    /**
     * Check if coupon has not started yet
     */
    public function isNotYetStarted(): bool
    {
        if ($this->start_date && now()->isBefore($this->start_date->copy()->startOfDay())) {
            return true;
        }

        return false;
    }

    /**
     * Check if coupon has reached usage limit
     */
    public function hasReachedUsageLimit(): bool
    {
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return true;
        }

        return false;
    }

    /**
     * Calculate discount amount for given order total
     */
    public function calculateDiscount(float $orderTotal): float
    {
        // Check if order meets minimum amount
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = ($orderTotal * $this->value) / 100;

            // Apply max discount limit if set
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = $this->max_discount_amount;
            }
        } else {
            // Fixed amount
            $discount = $this->value;
        }

        // Discount cannot exceed order total
        return min($discount, $orderTotal);
    }

    /**
     * Get status badge for display
     */
    public function getStatusBadge(): string
    {
        if (!$this->is_active) {
            return '<span class="badge bg-warning">Không hoạt động</span>';
        }

        if ($this->isExpired()) {
            return '<span class="badge bg-danger">Hết hạn</span>';
        }

        if ($this->isNotYetStarted()) {
            return '<span class="badge bg-info">Chưa bắt đầu</span>';
        }

        if ($this->hasReachedUsageLimit()) {
            return '<span class="badge bg-secondary">Hết lượt dùng</span>';
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
