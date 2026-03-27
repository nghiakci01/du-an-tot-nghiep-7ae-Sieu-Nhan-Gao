<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'province',
        'address',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Đặt địa chỉ này làm mặc định (bỏ mặc định các địa chỉ khác của cùng user)
     */
    public function setAsDefault(): void
    {
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    /**
     * Địa chỉ đầy đủ (địa chỉ + tỉnh/thành)
     */
    public function getFullAddressAttribute(): string
    {
        return $this->province
            ? "{$this->address}, {$this->province}"
            : $this->address;
    }
}
