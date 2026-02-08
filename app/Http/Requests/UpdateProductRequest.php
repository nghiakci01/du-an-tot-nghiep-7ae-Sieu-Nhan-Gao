<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
                        $path = $value->getRealPath();
                        if (empty($path)) return;

                        $dimensions = @getimagesize($path);
                        if (!$dimensions) {
                            $fail("Không thể đọc định dạng hình ảnh chính.");
                            return;
                        }

                        [$width, $height] = $dimensions;
                        if ($width < 500 || $height < 600) {
                            $fail("Hình ảnh chính phải có kích thước tối thiểu 500x600px.");
                        }

                        $ratio = round($width / $height, 2);
                        if ($ratio != 0.8 && $ratio != 1.0) {
                            $fail("Hình ảnh chính phải có tỷ lệ 4:5 hoặc 1:1.");
                        }
                    }
                }
            ],
            'gallery_images' => 'nullable|array|max:6',
            'gallery_images.*' => [
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
                        $path = $value->getRealPath();
                        if (empty($path)) return;

                        $dimensions = @getimagesize($path);
                        if (!$dimensions) {
                            $fail("Không thể đọc định dạng hình ảnh gallery.");
                            return;
                        }

                        [$width, $height] = $dimensions;
                        if ($width < 500 || $height < 600) {
                            $fail("Hình ảnh gallery phải có kích thước tối thiểu 500x600px.");
                        }

                        $ratio = round($width / $height, 2);
                        if ($ratio != 0.8 && $ratio != 1.0) {
                            $fail("Hình ảnh gallery phải có tỷ lệ 4:5 hoặc 1:1.");
                        }
                    }
                }
            ],
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
            'variants.*.id' => 'nullable|exists:product_variants,id',
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
            'variants.*.sku' => 'nullable|string|max:100',
        ];
    }
}

