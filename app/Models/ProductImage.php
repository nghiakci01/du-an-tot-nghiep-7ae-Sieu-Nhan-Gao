<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor for image_url with fallback
     */
    public function getImageUrlAttribute()
    {
        if (!empty($this->image_path) && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/'.$this->image_path);
        }

        return asset('frontend-assets/img/s-product/product.jpg') ?? 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    }
}
