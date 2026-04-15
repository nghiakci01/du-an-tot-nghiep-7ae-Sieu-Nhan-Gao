<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $provinces = config('vietnam_provinces') ?: [];

        $deliveryType = $this->input('delivery_type');
        if (!in_array($deliveryType, ['home','store'], true)) {
            $deliveryType = $this->input('shipping_provider') === 'store_pickup' ? 'store' : 'home';
        }

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(03|05|07|08|09)\\d{8}$/'],
            'email' => 'required|email:rfc,dns|max:255',
            'delivery_type' => 'nullable|in:home,store',
            'district' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'payment_method' => 'required|in:COD,VNPAY',
            'shipping_provider' => 'nullable|string',
            'shipping_service_name' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
        ];

        if ($deliveryType === 'store') {
            $rules['province'] = 'nullable|string|max:255';
            $rules['address'] = 'nullable|string|max:500';
        } else {
            $inList = count($provinces) ? 'in:' . implode(',', $provinces) : '';
            $rules['province'] = ($this->filled('user_address_id') ? 'required|string' : 'required|string') . ($inList ? '|' . $inList : '');
            $rules['address'] = 'required|string|max:500';
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        // Normalize province name (remove prefixes like "Tỉnh " or "Thành phố ")
        if ($this->has('province')) {
            $normalizedProv = preg_replace('/^(Tỉnh|Thành phố)\s+/u', '', $this->input('province'));
            $this->merge(['province' => $normalizedProv]);
        }

        $deliveryType = $this->input('delivery_type');
        if (!in_array($deliveryType, ['home', 'store'], true)) {
            $deliveryType = $this->input('shipping_provider') === 'store_pickup' ? 'store' : 'home';
        }
        $this->merge(['delivery_type' => $deliveryType]);

        if ($this->filled('user_address_id') && auth()->check()) {
            $userAddr = \App\Models\UserAddress::where('user_id', auth()->id())->find($this->input('user_address_id'));
            if ($userAddr) {
                $this->merge([
                    'name' => $userAddr->receiver_name,
                    'phone' => $userAddr->phone,
                    'province' => preg_replace('/^(Tỉnh|Thành phố)\s+/u', '', $userAddr->province),
                    'ward' => $userAddr->commune,
                    'address' => $userAddr->address,
                ]);
            }
        }

        if ($this->filled('commune') && !$this->filled('ward')) {
            $this->merge(['ward' => $this->input('commune')]);
        }
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09 và có đúng 10 chữ số.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'province.required' => 'Vui lòng chọn tỉnh thành.',
            'province.in' => 'Tỉnh thành không hợp lệ.',
        ];
    }
}
