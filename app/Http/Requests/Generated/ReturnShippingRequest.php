<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class ReturnShippingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_info' => 'required|string|max:1000',
            'shipping_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_info.required' => 'Vui lòng nhập thông tin vận chuyển (Mã vận đơn, đơn vị vận chuyển...).',
            'shipping_proof.required' => 'Vui lòng tải lên ảnh minh chứng đã gửi hàng.',
        ];
    }
}
