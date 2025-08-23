<?php

namespace App\Http\Resources\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'currency' => $this->currency,
            'description' => $this->description,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'usage_limit' => $this->usage_limit,
            'per_user_limit' => $this->per_user_limit,
            'min_booking_amount_vnd' => $this->min_booking_amount_vnd,
            'applicable_room_type_ids' => $this->applicable_room_type_ids,
            'stackable' => $this->stackable,
            'combinable_with' => $this->combinable_with,
            'active' => $this->active,
            'is_active' => $this->isActive(),
            'remaining_uses' => $this->remainingUses(),
            'created_by' => $this->whenLoaded('creator', function () {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
