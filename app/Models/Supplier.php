<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'description',
        'is_active',
    ];

    public function vouchers()
    {
        return $this->hasMany(InventoryVoucher::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
