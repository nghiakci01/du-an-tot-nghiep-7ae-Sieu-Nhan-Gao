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
            'variants' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $combinations = [];
                    foreach ($value as $index => $variant) {
                        $sizeId = $variant['size_id'] ?? null;
                        $colorId = $variant['color_id'] ?? null;
                        if ($sizeId && $colorId) {
                            $key = "{$sizeId}-{$colorId}";
                            if (in_array($key, $combinations)) {
                                $fail("Sản phẩm không được có các biến thể trùng lặp về Size và Màu sắc.");
                                return;
                            }
                            $combinations[] = $key;
                        }
                    }
                }
            ],
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $price = request()->input("variants.{$index}.price");
                    if ($price && $value >= $price) {
                        $fail("Giá khuyến mãi phải nhỏ hơn giá gốc.");
                    }
                }
            ],
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100|distinct', // We will generate if empty, but check distinct in array
        ];
    }
}

