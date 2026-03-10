<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartAbandonment extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'cart_data',
        'cart_total',
        'item_count',
        'status',
        'abandoned_at',
        'recovered_at',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'cart_total' => 'decimal:2',
        'abandoned_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }

    public function scopeRecovered($query)
    {
        return $query->where('status', 'recovered');
    }
}
