<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'reply_message',
        'replied_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
