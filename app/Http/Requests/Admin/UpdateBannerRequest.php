<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'required|string|in:slider,banner_top,banner_bottom',
            'is_active' => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Tiêu đề',
            'image' => 'Hình ảnh',
            'link' => 'Liên kết',
            'position' => 'Vị trí hiển thị',
            'is_active' => 'Trạng thái hiển thị',
        ];
    }
}
