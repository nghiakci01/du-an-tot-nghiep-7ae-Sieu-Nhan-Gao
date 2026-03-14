<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VtonModel extends Model
{
    protected $table = 'vton_models';

    protected $fillable = [
        'name',
        'image',
        'gender',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'vton_model_id');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
}
