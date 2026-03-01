<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
                        $parent = \App\Models\Category::find($value);
                        if ($parent && $parent->parent_id !== null) {
                            $fail('Không thể tạo danh mục cấp 3. Chỉ cho phép 2 cấp danh mục.');
                        }
                    }
                },
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
                'dimensions:min_width=400,min_height=400,max_width=1200,max_height=1200',
            ],
        ];
    }

    /**
     * Kiểm tra xem việc đổi parent có tạo circular reference không
     */
    private function wouldCreateCircularReference($newParentId): bool
    {
        $current = \App\Models\Category::find($newParentId);

        while ($current) {
            if ($current->id == $this->category->id) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'name.max' => 'Tên danh mục không được vượt quá 50 ký tự.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'parent_id.exists' => 'Danh mục cha không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, webp.',
            'image.max' => 'Kích thước file không được vượt quá 2MB.',
            'image.dimensions' => 'Kích thước ảnh phải từ 400x400px đến 1200x1200px.',
        ];
    }
}
