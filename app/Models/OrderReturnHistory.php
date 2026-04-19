<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnHistory extends Model
{
    protected $fillable = [
        'order_return_request_id',
        'user_id',
        'previous_status',
        'new_status',
        'note',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(OrderReturnRequest::class, 'order_return_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
