<?php

namespace App\Services\Checkout\Rules;

use App\Models\Booking;
use App\Models\BookingService;
use App\Services\Checkout\Contracts\CheckoutRuleInterface;
use Carbon\Carbon;

/**
 * Rule: Booking must be in "Operational" status
 */
class BookingStatusRule implements CheckoutRuleInterface
{
    public function passes(Booking $booking, array $context = []): bool
    {
        return $booking->status === 'Operational';
    }

    public function getMessage(): string
    {
        return 'Booking phải ở trạng thái "Đang lưu trú" mới có thể check-out';
    }

    public function getPriority(): int
    {
        return 1; // Highest priority
    }

    public function getRuleId(): string
    {
        return 'booking_status';
    }

    public function isBlocking(): bool
    {
        return true;
    }
}

/**
 * Rule: Payment must be sufficient
 */
class PaymentSufficientRule implements CheckoutRuleInterface
{
    public function passes(Booking $booking, array $context = []): bool
    {
        $totalAmount = $context['total_amount'] ?? $booking->total_price_vnd;
        
        $totalPaid = $booking->payments()
            ->where('status', 'completed')
            ->sum('amount_vnd');

        return $totalPaid >= $totalAmount;
    }

    public function getMessage(): string
    {
        return 'Chưa thanh toán đủ để có thể check-out';
    }

    public function getPriority(): int
    {
        return 2;
    }

    public function getRuleId(): string
    {
        return 'payment_sufficient';
    }

    public function isBlocking(): bool
    {
        return true;
    }
}

/**
 * Rule: Check-out time validation
 */
class CheckoutTimeRule implements CheckoutRuleInterface
{
    private $standardCheckoutTime = '12:00';
    private $lateCheckoutTime = '15:00';

    public function passes(Booking $booking, array $context = []): bool
    {
        $currentTime = Carbon::now()->format('H:i');
        $checkoutDate = Carbon::parse($booking->check_out_date);
        $today = Carbon::today();

        // If checkout date is in the future, allow anytime
        if ($checkoutDate->gt($today)) {
            return true;
        }

        // If checkout date is today, check time constraints
        if ($checkoutDate->eq($today)) {
            return $currentTime <= $this->lateCheckoutTime;
        }

        // If checkout date is in the past, it's overdue but still allow
        return true;
    }

    public function getMessage(): string
    {
        return "Check-out muộn quá giờ quy định ({$this->lateCheckoutTime}). Có thể phát sinh phí phụ thu.";
    }

    public function getPriority(): int
    {
        return 5;
    }

    public function getRuleId(): string
    {
        return 'checkout_time';
    }

    public function isBlocking(): bool
    {
        return false; // Warning only
    }
}

/**
 * Rule: Room cleaning status
 */
class RoomAvailabilityRule implements CheckoutRuleInterface
{
    public function passes(Booking $booking, array $context = []): bool
    {
        // This is more of a post-checkout rule
        // Check if rooms can be set to cleaning status
        return true; // Always pass for now
    }

    public function getMessage(): string
    {
        return 'Phòng không thể chuyển sang trạng thái dọn dẹp';
    }

    public function getPriority(): int
    {
        return 10;
    }

    public function getRuleId(): string
    {
        return 'room_availability';
    }

    public function isBlocking(): bool
    {
        return false;
    }
}

/**
 * Rule: Outstanding services validation
 */
class OutstandingServicesRule implements CheckoutRuleInterface
{
    public function passes(Booking $booking, array $context = []): bool
    {
        // Check if there are any pending service charges
        $pendingServices = $booking->bookingServices()
            ->whereHas('service', function($query) {
                $query->where('is_active', true);
            })
            ->where('quantity', '>', 0)
            ->count();

        // Always pass - this is informational
        return true;
    }

    public function getMessage(): string
    {
        return 'Có dịch vụ bổ sung chưa được xác nhận';
    }

    public function getPriority(): int
    {
        return 7;
    }

    public function getRuleId(): string
    {
        return 'outstanding_services';
    }

    public function isBlocking(): bool
    {
        return false;
    }
}

/**
 * Rule: Early checkout validation
 */
class EarlyCheckoutRule implements CheckoutRuleInterface
{
    public function passes(Booking $booking, array $context = []): bool
    {
        $checkoutDate = Carbon::parse($booking->check_out_date);
        $today = Carbon::today();

        return $checkoutDate->lte($today);
    }

    public function getMessage(): string
    {
        return 'Check-out sớm hơn ngày dự kiến. Vui lòng xác nhận với khách hàng.';
    }

    public function getPriority(): int
    {
        return 6;
    }

    public function getRuleId(): string
    {
        return 'early_checkout';
    }

    public function isBlocking(): bool
    {
        return false; // Warning only
    }
}

/**
 * Rule: Guest information completeness
 */
class GuestInformationRule implements CheckoutRuleInterface
{
    public function passes(Booking $booking, array $context = []): bool
    {
        return !empty($booking->guest_name) && 
               !empty($booking->guest_email) && 
               !empty($booking->guest_phone);
    }

    public function getMessage(): string
    {
        return 'Thông tin khách hàng chưa đầy đủ (tên, email, số điện thoại)';
    }

    public function getPriority(): int
    {
        return 8;
    }

    public function getRuleId(): string
    {
        return 'guest_information';
    }

    public function isBlocking(): bool
    {
        return false; // Warning only
    }
}

/**
 * Rule: Damage assessment
 */
class DamageAssessmentRule implements CheckoutRuleInterface
{
    public function passes(Booking $booking, array $context = []): bool
    {
        // Check if there are any damage-related services or notes
        $damageServices = $booking->bookingServices()
            ->whereHas('service', function($query) {
                $query->where('name', 'like', '%damage%')
                      ->orWhere('name', 'like', '%hư hỏng%')
                      ->orWhere('name', 'like', '%phạt%');
            })
            ->count();

        return $damageServices === 0;
    }

    public function getMessage(): string
    {
        return 'Có phí phạt hoặc bồi thường hư hỏng. Vui lòng xác nhận với khách hàng.';
    }

    public function getPriority(): int
    {
        return 4;
    }

    public function getRuleId(): string
    {
        return 'damage_assessment';
    }

    public function isBlocking(): bool
    {
        return false; // Warning only, but important
    }
}