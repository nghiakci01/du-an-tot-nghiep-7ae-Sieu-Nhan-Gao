<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class WalletWithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'amount' => 'required|numeric|min:50000',
            'user_bank_account_id' => 'required|exists:user_bank_accounts,id,user_id,' . ($userId ?: '0'),
        ];
    }
}
