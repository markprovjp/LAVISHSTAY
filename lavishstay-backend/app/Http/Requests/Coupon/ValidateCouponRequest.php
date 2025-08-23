<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCouponRequest extends FormRequest
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
        return [
            'code' => ['required', 'string', 'max:50'],
            'booking_preview' => ['required', 'array'],
            'booking_preview.room_type_id' => ['sometimes', 'integer'],
            'booking_preview.nights' => ['sometimes', 'integer', 'min:1'],
            'booking_preview.base_price_vnd' => ['required', 'numeric', 'min:0'],
            'booking_preview.taxes_vnd' => ['sometimes', 'numeric', 'min:0'],
            'booking_preview.fees_vnd' => ['sometimes', 'numeric', 'min:0'],
            'booking_preview.user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'booking_preview.total_price_vnd' => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Mã giảm giá là bắt buộc',
            'booking_preview.required' => 'Thông tin booking preview là bắt buộc',
            'booking_preview.base_price_vnd.required' => 'Giá cơ bản là bắt buộc',
            'booking_preview.base_price_vnd.min' => 'Giá cơ bản phải lớn hơn 0',
            'booking_preview.nights.min' => 'Số đêm phải lớn hơn 0',
            'booking_preview.user_id.exists' => 'User không tồn tại',
        ];
    }
}
