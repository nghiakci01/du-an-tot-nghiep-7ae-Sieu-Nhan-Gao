<?php

namespace App\Http\Requests\Admin;

class UpdatePostRequest extends BaseAdminFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('post');
        return [
            'post_category_id' => 'required|exists:post_categories,id',
            'title' => 'required|string|max:255|unique:posts,title,' . $id,
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! $value instanceof \Illuminate\Http\UploadedFile) {
                        return;
                    }
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $fail('Ảnh không đúng định dạng (jpg, jpeg, png, webp, gif).');
                    }
                    if ($value->getSize() > 2 * 1024 * 1024) {
                        $fail('Ảnh không được vượt quá 2MB.');
                    }
                },
            ],
            'is_active' => 'required|boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
