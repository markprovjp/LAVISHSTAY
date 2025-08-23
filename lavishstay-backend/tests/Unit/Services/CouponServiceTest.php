<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CouponService;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class CouponServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CouponService $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->couponService = new CouponService();
    }

    /** @test */
    public function it_validates_percentage_coupon_successfully()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'TEST10',
            'type' => 'percent',
            'value' => 10,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'usage_limit' => 100,
            'per_user_limit' => 2,
            'min_booking_amount_vnd' => 500000,
            'active' => true,
        ]);

        $bookingData = [
            'base_price_vnd' => 1000000,
            'taxes_vnd' => 100000,
            'fees_vnd' => 50000,
            'room_type_id' => 1,
        ];

        // Act
        $result = $this->couponService->validateCode('TEST10', $bookingData);

        // Assert
        $this->assertTrue($result['valid']);
        $this->assertEquals(100000, $result['discount_vnd']); // 10% of 1M
        $this->assertEquals(1050000, $result['new_total_vnd']); // 900k + 100k tax + 50k fee
        $this->assertEquals('TEST10', $result['coupon']['code']);
    }

    /** @test */
    public function it_validates_fixed_coupon_successfully()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'SAVE50K',
            'type' => 'fixed',
            'value' => 50000,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        $bookingData = [
            'base_price_vnd' => 500000,
            'taxes_vnd' => 0,
            'fees_vnd' => 0,
        ];

        // Act
        $result = $this->couponService->validateCode('SAVE50K', $bookingData);

        // Assert
        $this->assertTrue($result['valid']);
        $this->assertEquals(50000, $result['discount_vnd']);
        $this->assertEquals(450000, $result['new_total_vnd']);
    }

    /** @test */
    public function it_rejects_expired_coupon()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'EXPIRED',
            'type' => 'percent',
            'value' => 10,
            'start_at' => Carbon::now()->subWeek(),
            'end_at' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $bookingData = ['base_price_vnd' => 1000000];

        // Act
        $result = $this->couponService->validateCode('EXPIRED', $bookingData);

        // Assert
        $this->assertFalse($result['valid']);
        $this->assertEquals('expired', $result['reason']);
    }

    /** @test */
    public function it_rejects_coupon_below_minimum_amount()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'HIGH_MIN',
            'type' => 'percent',
            'value' => 10,
            'min_booking_amount_vnd' => 1000000,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        $booking = Booking::factory()->create([
            'total_price_vnd' => 500000,
        ]);

        // Act
        $result = $this->couponService->validateCode('HIGH_MIN', $booking);

        // Assert
        $this->assertFalse($result['valid']);
        $this->assertEquals('min_amount', $result['reason']);
    }

    /** @test */
    public function it_rejects_coupon_when_usage_limit_exceeded()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'LIMITED',
            'type' => 'percent',
            'value' => 10,
            'usage_limit' => 1,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Create one redemption to reach limit
        CouponRedemption::factory()->create(['coupon_id' => $coupon->id]);

        $booking = Booking::factory()->create(['total_price_vnd' => 1000000]);

        // Act
        $result = $this->couponService->validateCode('LIMITED', $booking);

        // Assert
        $this->assertFalse($result['valid']);
        $this->assertEquals('usage_limit', $result['reason']);
    }

    /** @test */
    public function it_rejects_coupon_when_user_limit_exceeded()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon = Coupon::factory()->create([
            'code' => 'USER_LIMITED',
            'type' => 'percent',
            'value' => 10,
            'per_user_limit' => 1,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Create one redemption for this user
        CouponRedemption::factory()->create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
        ]);

        $booking = Booking::factory()->create(['total_price_vnd' => 1000000]);

        // Act
        $result = $this->couponService->validateCode('USER_LIMITED', $booking, $user);

        // Assert
        $this->assertFalse($result['valid']);
        $this->assertEquals('user_limit', $result['reason']);
    }

    /** @test */
    public function it_rejects_coupon_for_wrong_room_type()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'DELUXE_ONLY',
            'type' => 'percent',
            'value' => 10,
            'applicable_room_type_ids' => [1, 2], // Only for room types 1 and 2
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        $booking = Booking::factory()->create([
            'total_price_vnd' => 1000000,
            'room_type_id' => 3, // Different room type
        ]);

        // Act
        $result = $this->couponService->validateCode('DELUXE_ONLY', $booking);

        // Assert
        $this->assertFalse($result['valid']);
        $this->assertEquals('room_type', $result['reason']);
    }

    /** @test */
    public function it_applies_coupon_to_booking_successfully()
    {
        // Arrange
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'total_price_vnd' => 1000000,
            'user_id' => $user->id,
        ]);
        
        $coupon = Coupon::factory()->create([
            'code' => 'APPLY_TEST',
            'type' => 'percent',
            'value' => 15,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Act
        $result = $this->couponService->applyCouponToBooking($booking, 'APPLY_TEST', $user);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(150000, $result['discount_amount']); // 15% of 1M
        $this->assertEquals(850000, $result['new_total']);
        
        // Check database
        $booking->refresh();
        $this->assertEquals(850000, $booking->total_price_vnd);
        
        $redemption = CouponRedemption::where('coupon_id', $coupon->id)
            ->where('booking_id', $booking->booking_id)
            ->first();
        $this->assertNotNull($redemption);
        $this->assertEquals(150000, $redemption->amount_saved_vnd);
    }

    /** @test */
    public function it_handles_idempotency_when_coupon_already_applied()
    {
        // Arrange
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'total_price_vnd' => 1000000,
            'user_id' => $user->id,
        ]);
        
        $coupon = Coupon::factory()->create([
            'code' => 'ALREADY_APPLIED',
            'type' => 'fixed',
            'value' => 100000,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Create existing redemption
        $existingRedemption = CouponRedemption::factory()->create([
            'coupon_id' => $coupon->id,
            'booking_id' => $booking->booking_id,
            'user_id' => $user->id,
            'amount_saved_vnd' => 100000,
        ]);

        // Act
        $result = $this->couponService->applyCouponToBooking($booking, 'ALREADY_APPLIED', $user);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertTrue($result['existing']);
        $this->assertEquals($existingRedemption->id, $result['redemption']->id);
    }

    /** @test */
    public function it_handles_concurrent_usage_limit_correctly()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'CONCURRENT_TEST',
            'type' => 'fixed',
            'value' => 50000,
            'usage_limit' => 1,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        $booking1 = Booking::factory()->create(['total_price_vnd' => 500000]);
        $booking2 = Booking::factory()->create(['total_price_vnd' => 500000]);

        // Act - Simulate concurrent requests
        DB::transaction(function () use ($coupon, $booking1) {
            $result1 = $this->couponService->applyCouponToBooking($booking1, 'CONCURRENT_TEST');
            $this->assertTrue($result1['success']);
        });

        $result2 = $this->couponService->applyCouponToBooking($booking2, 'CONCURRENT_TEST');

        // Assert
        $this->assertFalse($result2['success']);
        $this->assertEquals('usage_limit', $result2['reason']);
    }

    /** @test */
    public function it_returns_correct_usage_stats()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'usage_limit' => 10,
        ]);

        // Create 3 redemptions
        CouponRedemption::factory()->count(3)->create([
            'coupon_id' => $coupon->id,
            'amount_saved_vnd' => 50000,
        ]);

        // Act
        $stats = $this->couponService->getCouponUsageStats($coupon);

        // Assert
        $this->assertEquals(3, $stats['total_redemptions']);
        $this->assertEquals(7, $stats['remaining_uses']);
        $this->assertEquals(150000, $stats['total_amount_saved']);
        $this->assertEquals(30.0, $stats['usage_percentage']);
    }

    /** @test */
    public function it_returns_user_redemption_history()
    {
        // Arrange
        $user = User::factory()->create();
        $coupon = Coupon::factory()->create(['code' => 'HISTORY_TEST']);
        $booking = Booking::factory()->create(['booking_code' => 'BK123']);

        CouponRedemption::factory()->create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'booking_id' => $booking->booking_id,
            'amount_saved_vnd' => 75000,
        ]);

        // Act
        $history = $this->couponService->getUserRedemptions($user);

        // Assert
        $this->assertCount(1, $history);
        $this->assertEquals('HISTORY_TEST', $history[0]['coupon_code']);
        $this->assertEquals(75000, $history[0]['amount_saved']);
        $this->assertEquals('BK123', $history[0]['booking_code']);
    }
}
