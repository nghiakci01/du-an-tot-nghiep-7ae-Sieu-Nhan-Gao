<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group'];

    /**
     * Get a setting by key, or return default.
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Calculate shipping fee based on subtotal
     */
    public static function getShippingFee($subtotal)
    {
        // Free shipping for orders >= 799,000 đ
        if ($subtotal >= 799000) {
            return 0;
        }

        return (float) self::get('shipping_fee', 30000);
    }
}
