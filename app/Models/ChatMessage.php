<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    /** @use HasFactory<\Database\Factories\ChatMessageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_id',
        'user_id',
        'message',
        'sender_type',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
