<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class PostCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('id') ?? $this->route('category');
        $id = null;
        if ($category) {
            $id = is_object($category) ? ($category->id ?? null) : $category;
        }

        $nameRule = 'required|string|max:255' . ($id ? '|unique:post_categories,name,' . $id : '|unique:post_categories');

        return [
            'name' => $nameRule,
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }
}
