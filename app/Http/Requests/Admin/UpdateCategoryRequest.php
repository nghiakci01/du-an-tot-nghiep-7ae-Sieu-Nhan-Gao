<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Http\UploadedFile;

class UpdateCategoryRequest extends BaseAdminFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50|unique:categories,name,'.$this->category->id,
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    // Không cho phép tự làm parent của chính mình
                    if ($value == $this->category->id) {
                        $fail('Danh mục không thể là danh mục cha của chính nó.');

                        return;
                    }

                    // Kiểm tra circular reference
                    if ($value && $this->wouldCreateCircularReference($value)) {
                        $fail('Không thể chọn danh mục con làm danh mục cha.');

                        return;
                    }

                    // Kiểm tra 2-level hierarchy
                    if ($value) {
                        $parent = Category::find($value);
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
                        return;
                    }
                    if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $fail('Hình ảnh phải có định dạng: jpg, jpeg, png, gif, webp.');
                    }
                    if ($size !== false && $size > 2 * 1024 * 1024) {
                        $fail('Kích thước hình ảnh không được vượt quá 2MB.');
                    }
                },
            ],
            'is_active' => 'boolean',
            'vton_model_id' => 'nullable|exists:vton_models,id',
        ];
    }

    /**
     * Kiểm tra xem việc đổi parent có tạo circular reference không
     */
    private function wouldCreateCircularReference($newParentId): bool
    {
        $current = Category::find($newParentId);

        while ($current) {
            if ($current->id == $this->category->id) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
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
