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
            'shipping_info' => 'nullable|string|max:1000',
            'shipping_proof' => 'required|array|min:1',
            'shipping_proof.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'shipping_video' => 'nullable|array',
            'shipping_video.*' => 'file|mimes:mp4,mov,avi,wmv|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_proof.required' => 'Vui lòng tải lên ảnh minh chứng đã gửi hàng.',
            'shipping_video.mimes' => 'Video không đúng định dạng (mp4, mov, avi, wmv).',
            'shipping_video.max' => 'Dung lượng video không được vượt quá 20MB.',
        ];
    }
}
