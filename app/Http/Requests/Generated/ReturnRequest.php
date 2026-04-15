<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:refund,exchange',
            'reason_type' => 'required|in:wrong_size,disliked,defective,other',
            'reason' => 'required|string|max:255',
            'return_method' => 'required|string|in:at_home,at_post_office',
            'note' => 'required|string|max:1000',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos' => 'required|array|min:1',
            'videos.*' => 'required|file|mimes:mp4,mov,avi,webm|max:51200',
            'items' => 'required|array',
            'items.*.selected' => 'sometimes|boolean',
            'items.*.quantity' => 'sometimes|integer|min:1',
            'bank_name' => 'required_if:type,refund|nullable|string|max:255',
            'bank_bin' => 'required_if:type,refund|nullable|string|max:20',
            'account_number' => 'required_if:type,refund|nullable|string|max:50',
            'account_name' => 'required_if:type,refund|nullable|string|max:255',
        ];
    }
}
