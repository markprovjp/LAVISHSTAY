<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRoomPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function prepareForValidation()
    {
        $rooms = $this->input('rooms', []);

        foreach ($rooms as &$room) {
            if (!empty($room['childrenAges'])) {
                $room['childrenAges'] = array_map(function ($child) {
                    return is_array($child) && isset($child['age']) ? (int) $child['age'] : (int) $child;
                }, $room['childrenAges']);
            }
        }

        $this->merge([
            'rooms' => $rooms
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'rooms' => 'required|array|min:1',
            'rooms.*.adults' => 'required|integer|min:1',
            'rooms.*.children' => 'nullable|integer|min:0',
            'rooms.*.childrenAges' => 'nullable|array',
            'rooms.*.childrenAges.*' => 'integer|min:0|max:17',
        ];
    }
}
