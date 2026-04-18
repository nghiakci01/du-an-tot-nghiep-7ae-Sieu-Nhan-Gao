<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_name' => 'required|string|max:100',
            'phone' => ['required', 'regex:/^(03|05|07|08|09)\d{8}$/'],
            'province' => 'required|string|max:100',
            'commune' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng 03, 05, 07, 08 hoặc 09.',
        ];
    }
}
