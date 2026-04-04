<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDeliveryProof extends Model
{
    protected $fillable = ['order_id', 'file_path', 'file_type'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
