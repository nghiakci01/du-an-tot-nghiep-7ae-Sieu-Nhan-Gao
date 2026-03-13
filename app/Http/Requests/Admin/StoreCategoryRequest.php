<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Http\UploadedFile;

class StoreCategoryRequest extends BaseAdminFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50|unique:categories,name',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parent = Category::find($value);
                        // Kiểm tra parent đã có parent chưa (tức là đang ở cấp 2)
                        if ($parent && $parent->parent_id !== null) {
                            $fail('Không thể tạo danh mục cấp 3. Chỉ cho phép 2 cấp danh mục.');
                        }
                    }
                },
            ],
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! $value instanceof UploadedFile) {
                        return;
                    }
                    try {
                        $ext = strtolower($value->getClientOriginalExtension());
                        $size = @$value->getSize();
                    } catch (\Throwable $e) {
                        return; // file lỗi, bỏ qua
                    }
                    if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $fail('Hình ảnh phải có định dạng: jpg, jpeg, png, gif, webp.');
                    }
                    if ($size !== false && $size > 2 * 1024 * 1024) {
                        $fail('Kích thước hình ảnh không được vượt quá 2MB (2048 KB).');
                    }
                    // Bỏ rule dimensions gắt gao gây lỗi PHP 8.2 ValueError
                },
            ],
            'is_active' => 'boolean',
            'vton_model_id' => 'nullable|exists:vton_models,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 50 ký tự.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
        ];
    }
}
