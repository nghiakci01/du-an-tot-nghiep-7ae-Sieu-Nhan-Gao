<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper($this->code),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $couponId = $this->route('coupon')->id;

        $rules = [
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($couponId)],
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
            'user_id' => 'nullable|exists:users,id',
        ];

        // Additional validation based on type
        if ($this->type === 'percentage') {
            $rules['value'] .= '|max:100';
        } elseif ($this->type === 'fixed') {
            $rules['value'] .= '|min:1000';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'type.required' => 'Loại giảm giá là bắt buộc.',
            'type.in' => 'Loại giảm giá không hợp lệ.',
            'value.required' => 'Giá trị giảm là bắt buộc.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng :min.',
            'value.max' => 'Giá trị phần trăm không được vượt quá 100.',
            'min_order_amount.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
            'max_discount_amount.numeric' => 'Giảm tối đa phải là số.',
            'max_discount_amount.min' => 'Giảm tối đa phải lớn hơn hoặc bằng 0.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn hoặc bằng 1.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
        ];
    }
}
