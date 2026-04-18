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
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0|max:99999999',
            'sale_price' => 'nullable|numeric|min:0|max:99999999',

            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:10240',
                function ($attribute, $value, $fail) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
                        $path = $value->getRealPath() ?: $value->getPathname();
                        if (empty($path)) {
                            return;
                        }

                        $dimensions = @getimagesize($path);
                        if (! $dimensions) {
                            $fail('Không thể đọc định dạng hình ảnh chính.');

                            return;
                        }

                        [$width, $height] = $dimensions;
                        if ($width < 400 || $height < 400) {
                            $fail('Hình ảnh chính phải có kích thước tối thiểu 400x400px.');
                        }
                    }
                },
            ],
            'gallery_images' => 'nullable|array|max:6',
            'gallery_images.*' => [
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:10240',
                function ($attribute, $value, $fail) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid()) {
                        $path = $value->getRealPath() ?: $value->getPathname();
                        if (empty($path)) {
                            return;
                        }

                        $dimensions = @getimagesize($path);
                        if (! $dimensions) {
                            $fail('Không thể đọc định dạng hình ảnh gallery.');

                            return;
                        }

                        [$width, $height] = $dimensions;
                        if ($width < 400 || $height < 400) {
                            $fail('Hình ảnh gallery phải có kích thước tối thiểu 400x400px.');
                        }
                    }
                },
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
                                $fail('Sản phẩm không được có các biến thể trùng lặp về Kích thước và Màu sắc.');

                                return;
                            }
                            $combinations[] = $key;
                        }
                    }
                },
            ],
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.price' => 'required|numeric|min:0|max:99999999',
            'variants.*.sale_price' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $price = request()->input("variants.{$index}.price");
                    if ($price && $value > $price) {
                        $fail('Giá khuyến mãi không được lớn hơn giá gốc.');
                    }
                },
            ],
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Tên sản phẩm',
            'category_id' => 'Danh mục',
            'price' => 'Giá',
            'sale_price' => 'Giá khuyến mãi',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả',
            'image' => 'Hình ảnh chính',
            'gallery_images' => 'Ảnh bộ sưu tập',

            'variants' => 'Biến thể',
            'variants.*.size_id' => 'Kích thước',
            'variants.*.color_id' => 'Màu sắc',
            'variants.*.price' => 'Giá',
            'variants.*.sale_price' => 'Giá khuyến mãi biến thể',
            'variants.*.stock_quantity' => 'Số lượng tồn kho',
            'variants.*.sku' => 'Mã SKU',
        ];
    }

    public function messages(): array
    {
        return [
            'sale_price.lt' => 'Giá khuyến mãi không được lớn hơn giá gốc.',
        ];
    }
}
