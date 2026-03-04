<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSetting extends Model
{
    protected $fillable = [
        'bank_name',
        'bank_id',
        'account_number',
        'account_name',
        'is_active',
        'is_default'
    ];
}
