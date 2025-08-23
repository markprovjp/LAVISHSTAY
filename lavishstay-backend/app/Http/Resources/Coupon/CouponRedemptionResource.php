<?php

namespace App\Http\Resources\Coupon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponRedemptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'coupon' => [
                'id' => $this->coupon->id,
                'code' => $this->coupon->code,
                'type' => $this->coupon->type,
                'value' => $this->coupon->value,
            ],
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'booking' => $this->whenLoaded('booking', function () {
                return [
                    'id' => $this->booking->booking_id,
                    'code' => $this->booking->booking_code,
                ];
            }),
            'amount_saved_vnd' => $this->amount_saved_vnd,
            'applied_amount_vnd' => $this->applied_amount_vnd,
            'meta' => $this->meta,
            'created_at' => $this->created_at,
        ];
    }
}
