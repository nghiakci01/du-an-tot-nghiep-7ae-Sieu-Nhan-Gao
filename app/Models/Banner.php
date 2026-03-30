<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($banner) {
            self::clearBannerCache();
        });

        static::deleted(function ($banner) {
            self::clearBannerCache();
        });
    }

    private static function clearBannerCache()
    {
        \Illuminate\Support\Facades\Cache::forget('home_sliders');
        \Illuminate\Support\Facades\Cache::forget('home_midBanner');
    }
}
