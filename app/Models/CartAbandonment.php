<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CartAbandonment extends Model
{
    protected $table = 'cart_abandonments';

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
        'cart_data'   => 'array',
        'cart_total'  => 'decimal:2',
        'abandoned_at' => 'datetime',
        'recovered_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeAbandoned(Builder $query): Builder
    {
        return $query->where('status', 'abandoned');
    }

    public function scopeRecovered(Builder $query): Builder
    {
        return $query->where('status', 'recovered');
    }

    public function scopeConverted(Builder $query): Builder
    {
        return $query->where('status', 'converted');
    }
}
