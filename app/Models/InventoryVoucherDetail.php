<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryVoucherDetail extends Model
{
    protected $fillable = [
        'inventory_voucher_id',
        'product_variant_id',
        'quantity',
        'unit_price',
    ];

    public function voucher()
    {
        return $this->belongsTo(InventoryVoucher::class, 'inventory_voucher_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
