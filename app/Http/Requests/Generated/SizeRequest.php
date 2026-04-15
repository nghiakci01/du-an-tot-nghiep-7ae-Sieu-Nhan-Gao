<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class SizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $size = $this->route('size');
        $id = null;
        if ($size) {
            $id = is_object($size) ? ($size->id ?? null) : $size;
        }

        $nameRule = 'required|string|max:255' . ($id ? '|unique:sizes,name,' . $id : '|unique:sizes,name');

        return [
            'name' => $nameRule,
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }
}
