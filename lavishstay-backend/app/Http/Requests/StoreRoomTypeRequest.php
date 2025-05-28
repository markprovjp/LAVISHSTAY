<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_name_en' => 'required|string|max:100',
            'type_name_vi' => 'required|string|max:100',
            'category' => ['required', Rule::in(['standard', 'presidential', 'the_level'])],
            'description_en' => 'nullable|string',
            'description_vi' => 'nullable|string',
            'size_sqm' => 'nullable|integer|min:1',
            'max_adults' => 'nullable|integer|min:1|max:10',
            'max_children' => 'nullable|integer|min:0|max:10',
            'max_occupancy' => 'nullable|integer|min:1|max:20',
            'base_price_usd' => 'required|numeric|min:0',
            'weekend_price_usd' => 'nullable|numeric|min:0',
            'is_sale' => 'boolean',
            'sale_percentage' => 'nullable|numeric|min:0|max:100',
            'sale_price_usd' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'view_type' => ['nullable', Rule::in(['city', 'ocean', 'garden', 'mountain'])],
            'bed_type' => 'nullable|string|max:100',
            'room_features_en' => 'nullable|array',
            'room_features_vi' => 'nullable|array',
            'policies_en' => 'nullable|string',
            'policies_vi' => 'nullable|string',
            'cancellation_policy_en' => 'nullable|string',
            'cancellation_policy_vi' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ];
    }
}
