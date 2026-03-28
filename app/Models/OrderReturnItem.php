<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnItem extends Model
{
    protected $fillable = [
        'order_return_request_id',
        'order_item_id',
        'quantity',
        'price',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(OrderReturnRequest::class, 'order_return_request_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
