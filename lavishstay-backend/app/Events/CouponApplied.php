<?php

namespace App\Events;

use App\Models\Coupon;
use App\Models\Booking;
use App\Models\User;
use App\Models\CouponRedemption;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CouponApplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $coupon;
    public $booking;
    public $user;
    public $redemption;

    /**
     * Create a new event instance.
     */
    public function __construct(Coupon $coupon, Booking $booking, ?User $user, CouponRedemption $redemption)
    {
        $this->coupon = $coupon;
        $this->booking = $booking;
        $this->user = $user;
        $this->redemption = $redemption;
    }
}
