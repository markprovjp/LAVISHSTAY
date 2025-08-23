<?php

namespace App\Http\Requests\Coupon;

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
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $couponId = $this->route('coupon');
        
        return [
            'code' => [
                'sometimes',
                'string',
                'max:50',
                'regex:/^[A-Z0-9_-]+$/',
                Rule::unique('coupons', 'code')->ignore($couponId)
            ],
            'type' => ['sometimes', Rule::in(['percent', 'fixed'])],
            'value' => ['sometimes', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'description' => ['nullable', 'string', 'max:1000'],
            'start_at' => ['sometimes', 'date'],
            'end_at' => ['sometimes', 'date', 'after:start_at'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],
            'min_booking_amount_vnd' => ['nullable', 'numeric', 'min:0'],
            'applicable_room_type_ids' => ['nullable', 'array'],
            'applicable_room_type_ids.*' => ['integer', 'exists:room_types,room_type_id'],
            'stackable' => ['sometimes', 'boolean'],
            'combinable_with' => ['nullable', 'array'],
            'combinable_with.*' => ['string', 'exists:coupons,code'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'code.regex' => 'Mã giảm giá chỉ được chứa chữ cái in hoa, số, dấu gạch ngang và gạch dưới',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'type.in' => 'Loại giảm giá phải là percent hoặc fixed',
            'value.min' => 'Giá trị giảm giá phải lớn hơn 0',
            'end_at.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn 0',
            'per_user_limit.min' => 'Giới hạn per user phải lớn hơn 0',
            'applicable_room_type_ids.*.exists' => 'Loại phòng không tồn tại',
            'combinable_with.*.exists' => 'Mã kết hợp không tồn tại',
        ];
    }

    /**
     * Custom validation logic
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('type') && $this->has('value')) {
                if ($this->type === 'percent' && $this->value > 100) {
                    $validator->errors()->add('value', 'Giá trị phần trăm không được vượt quá 100%');
                }

                if ($this->type === 'fixed' && $this->value > 50000000) {
                    $validator->errors()->add('value', 'Giá trị giảm giá cố định quá lớn');
                }
            }
        });
    }
}
