<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class VoucherClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupon_id' => 'required|exists:coupons,id',
            'source' => 'nullable|string|max:20',
            'source_id' => 'nullable|exists:posts,id',
        ];
    }
}
