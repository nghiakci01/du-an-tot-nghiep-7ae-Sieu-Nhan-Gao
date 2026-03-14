<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Auditable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'sale_price',
        'sale_start',
        'sale_end',
        'is_active',
        'is_featured',
        'image',
        'vton_image',
        'vton_model_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function vtonModel(): BelongsTo
    {
        return $this->belongsTo(VtonModel::class, 'vton_model_id');
    }

    /**
     * Get the effective VTON model (product-specific or category-default)
     */
    public function getEffectiveVtonModel()
    {
        if ($this->vton_model_id) {
            return $this->vtonModel;
        }

        if ($this->category && $this->category->vton_model_id) {
            return $this->category->vtonModel;
        }

        return null;
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Accessor for original_price (maps to price field)
     * Used for view compatibility
     */
    public function getOriginalPriceAttribute()
    {
        return $this->price;
    }

    /**
     * Accessor for image_url with fallback
     * Returns placeholder if image file doesn't exist
     */
    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            return asset('storage/'.$this->image);
        }

        return asset('frontend-assets/img/product/product21.jpg');
    }

    public function isOnFlashSale(): bool
    {
        if (!$this->sale_price || !$this->sale_start || !$this->sale_end) {
            return false;
        }
        $now = now();
        return $now->gte($this->sale_start) && $now->lte($this->sale_end);
    }

    public function getFlashSaleEndsAtAttribute(): ?string
    {
        return $this->isOnFlashSale() ? $this->sale_end->toIso8601String() : null;
    }

    public function scopeFlashSale($query)
    {
        return $query->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->whereNotNull('sale_start')
            ->whereNotNull('sale_end')
            ->where('sale_start', '<=', now())
            ->where('sale_end', '>=', now())
            ->where('is_active', true);
    }

    public function getTotalSoldAttribute(): int
    {
        return (int) \App\Models\OrderItem::where('product_id', $this->id)
            ->whereHas('order', fn($q) => $q->whereNotIn('status', ['cancelled', 'failed', 'returned']))
            ->sum('quantity');
    }
}
