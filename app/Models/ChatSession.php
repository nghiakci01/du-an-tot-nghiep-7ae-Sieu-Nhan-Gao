<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'is_bot_enabled',
        'last_activity',
    ];

    protected $casts = [
        'is_bot_enabled' => 'boolean',
        'last_activity' => 'datetime',
    ];
}
