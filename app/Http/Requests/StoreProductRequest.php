<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240|dimensions:min_width=400,min_height=400',
            'gallery_images' => 'nullable|array|max:6',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240|dimensions:min_width=400,min_height=400',
            'is_active' => 'boolean',
            
            // Variants validation
            'variants' => 'required|array|min:1',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0|lt:variants.*.price',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100|distinct', // We will generate if empty, but check distinct in array
        ];
    }
}
