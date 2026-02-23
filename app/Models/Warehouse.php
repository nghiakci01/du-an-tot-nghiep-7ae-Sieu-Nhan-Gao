<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'is_active',
    ];

    public function vouchers()
    {
        return $this->hasMany(InventoryVoucher::class);
    }

    public function stocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
