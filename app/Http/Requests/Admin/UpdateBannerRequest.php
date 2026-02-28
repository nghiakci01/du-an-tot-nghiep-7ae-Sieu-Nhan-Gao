<?php

namespace App\Http\Requests\Admin;

use Illuminate\Http\UploadedFile;

class UpdateBannerRequest extends BaseAdminFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'nullable|string|max:255',
            'image'      => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! $value instanceof UploadedFile) {
                        return;
                    }
                    try {
                        $ext  = strtolower($value->getClientOriginalExtension());
                        $size = @$value->getSize();
                    } catch (\Throwable $e) {
                        return;
                    }
                    if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $fail('Hình ảnh phải có định dạng: jpg, jpeg, png, gif, webp.');
                    }
                    if ($size !== false && $size > 2 * 1024 * 1024) {
                        $fail('Kích thước hình ảnh không được vượt quá 2MB (2048 KB).');
                    }
                },
            ],
            'link'       => 'nullable|string|max:255',
            'position'   => 'required|string|in:slider,banner_top,banner_bottom',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'      => 'Tiêu đề',
            'image'      => 'Hình ảnh',
            'link'       => 'Liên kết',
            'position'   => 'Vị trí hiển thị',
            'sort_order' => 'Thứ tự ưu tiên',
            'is_active'  => 'Trạng thái hiển thị',
        ];
    }

    public function messages(): array
    {
        return [
            'required'   => ':attribute không được để trống.',
            'max'        => ':attribute không được vượt quá :max ký tự.',
            'integer'    => ':attribute phải dưới dạng số nguyên.',
            'min'        => ':attribute phải lớn hơn hoặc bằng :min.',
            'in'         => ':attribute không hợp lệ.',
            'string'     => ':attribute phải là chuỗi ký tự.',
        ];
    }
}
