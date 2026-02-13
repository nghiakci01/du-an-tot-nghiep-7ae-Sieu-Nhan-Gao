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
            'sort_order' => 'nullable|integer|min:0',
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
            'sort_order' => 'Thứ tự ưu tiên',
            'is_active' => 'Trạng thái hiển thị',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'image' => ':attribute phải là định dạng hình ảnh.',
            'mimes' => ':attribute phải có định dạng: :values.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'image.max' => ':attribute không được vượt quá :max KB.',
            'integer' => ':attribute phải dưới dạng số nguyên.',
            'min' => ':attribute phải lớn hơn hoặc bằng :min.',
            'in' => ':attribute không hợp lệ.',
            'string' => ':attribute phải là chuỗi ký tự.',
        ];
    }
}
