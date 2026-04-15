<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class ShippingFeesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_type' => 'nullable|in:home,store',
            'province' => 'nullable|string',
            'district' => 'nullable|string',
            'commune' => 'nullable|string',
            'ward' => 'nullable|string',
            'weight' => 'nullable|integer',
        ];
    }
}
