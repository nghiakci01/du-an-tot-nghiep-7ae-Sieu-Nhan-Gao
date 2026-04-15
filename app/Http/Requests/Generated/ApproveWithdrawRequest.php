<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class ApproveWithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proof_image' => 'nullable|image|max:5120',
        ];
    }
}
