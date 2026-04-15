<?php

namespace App\Http\Requests\Generated;

use Illuminate\Foundation\Http\FormRequest;

class CartActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->route() ? $this->route()->getActionMethod() : null;

        switch ($action) {
            case 'addToCart':
                return [
                    'product_id' => 'required|exists:products,id',
                    'variant_id' => 'nullable|exists:product_variants,id',
                    'quantity' => 'required|integer|min:1',
                ];
            case 'changeVariant':
                return [
                    'old_variant_id' => 'required',
                    'product_id' => 'required|exists:products,id',
                    'new_product_id' => 'nullable|exists:products,id',
                    'size_id' => 'nullable',
                    'color_id' => 'nullable',
                    'changed_type' => 'nullable|string',
                ];
            case 'applyCoupon':
                return [
                    'coupon_code' => 'required|string|max:50',
                ];
            case 'updateCart':
                return [
                    'id' => 'required',
                    'quantity' => 'required|integer|min:1',
                ];
            default:
                return [
                    'product_id' => 'nullable|exists:products,id',
                    'variant_id' => 'nullable|exists:product_variants,id',
                    'quantity' => 'nullable|integer|min:1',
                    'old_variant_id' => 'nullable',
                    'new_product_id' => 'nullable|exists:products,id',
                    'size_id' => 'nullable',
                    'color_id' => 'nullable',
                    'changed_type' => 'nullable|string',
                    'coupon_code' => 'nullable|string|max:50',
                ];
        }
    }
}
