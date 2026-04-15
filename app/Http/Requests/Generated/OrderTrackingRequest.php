<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class OrderTrackingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|string',
            'contact' => 'required|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        $orderId = trim(str_replace('#', '', $this->input('order_id')));
        $contact = trim($this->input('contact'));

        $this->merge([
            'order_id' => $orderId,
            'contact' => $contact,
        ]);
    }
}
