<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'size',
        'color',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'sku',
        'alert_threshold',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'alert_threshold' => 'integer',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function voucherDetails()
    {
        return $this->hasMany(InventoryVoucherDetail::class, 'product_variant_id');
    }

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class, 'product_variant_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sizeRelationship(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function colorRelationship(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}
