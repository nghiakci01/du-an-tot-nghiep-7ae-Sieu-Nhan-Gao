<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Loại bỏ file upload không hợp lệ (getRealPath rỗng) trước khi validate
     * để tránh ValueError: Path cannot be empty trong ValidatesAttributes
     */
    protected function prepareForValidation(): void
    {
        if ($this->hasFile('image')) {
            $file = $this->file('image');
            if (! $file->isValid() || $file->getRealPath() === false || $file->getRealPath() === '') {
                Log::warning('UpdateCategoryRequest: file image có path rỗng, bỏ qua.', [
                    'error_code' => $file->getError(),
                    'name' => $file->getClientOriginalName(),
                ]);
                $this->files->remove('image');
            }
        }
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:categories,name,' . $this->category->id,
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->category->id) {
                        $fail('Danh mục cha không thể là chính nó.');
                    }
                },
            ],
            'image'     => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif|max:2048',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Tên danh mục là bắt buộc.',
            'name.unique'       => 'Tên danh mục đã tồn tại.',
            'name.max'          => 'Tên danh mục không được vượt quá 255 ký tự.',
            'parent_id.exists'  => 'Danh mục cha không hợp lệ.',
            'image.mimetypes'   => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max'         => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ];
    }
}
