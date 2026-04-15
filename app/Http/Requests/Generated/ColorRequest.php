<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $color = $this->route('color');
        $colorId = null;
        if ($color) {
            $colorId = is_object($color) ? ($color->id ?? null) : $color;
        }

        $nameRule = 'required|string|max:25' . ($colorId ? '|unique:colors,name,' . $colorId : '|unique:colors,name');

        return [
            'name' => $nameRule,
            'hex_code' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }
}
