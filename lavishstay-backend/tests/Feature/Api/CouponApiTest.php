<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class CouponApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_coupon_successfully()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'VALID_TEST',
            'type' => 'percent',
            'value' => 10,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'min_booking_amount_vnd' => 500000,
            'active' => true,
        ]);

        $requestData = [
            'code' => 'VALID_TEST',
            'booking_preview' => [
                'base_price_vnd' => 1000000,
                'taxes_vnd' => 100000,
                'fees_vnd' => 50000,
                'room_type_id' => 1,
            ]
        ];

        // Act
        $response = $this->postJson('/api/coupons/validate', $requestData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'coupon' => [
                    'code' => 'VALID_TEST',
                    'type' => 'percent',
                    'value' => 10,
                ],
                'discount_vnd' => 100000,
                'new_total_vnd' => 1050000,
            ]);
    }

    /** @test */
    public function it_rejects_invalid_coupon()
    {
        // Arrange
        $requestData = [
            'code' => 'INVALID_CODE',
            'booking_preview' => [
                'base_price_vnd' => 1000000,
                'taxes_vnd' => 100000,
                'fees_vnd' => 50000,
            ]
        ];

        // Act
        $response = $this->postJson('/api/coupons/validate', $requestData);

        // Assert
        $response->assertStatus(422)
            ->assertJson([
                'valid' => false,
                'reason' => 'not_found',
                'message' => 'Mã giảm giá không tồn tại'
            ]);
    }

    /** @test */
    public function it_applies_coupon_to_booking_successfully()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'total_price_vnd' => 1000000,
        ]);

        $coupon = Coupon::factory()->create([
            'code' => 'APPLY_TEST',
            'type' => 'fixed',
            'value' => 150000,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Act
        $response = $this->postJson("/api/bookings/{$booking->booking_id}/apply-coupon", [
            'code' => 'APPLY_TEST'
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'discount_amount' => 150000,
                'new_total' => 850000,
            ]);

        // Verify database
        $booking->refresh();
        $this->assertEquals(850000, $booking->total_price_vnd);

        $this->assertDatabaseHas('coupon_redemptions', [
            'coupon_id' => $coupon->id,
            'booking_id' => $booking->booking_id,
            'user_id' => $user->id,
            'amount_saved_vnd' => 150000,
        ]);
    }

    /** @test */
    public function it_prevents_unauthorized_access_to_booking()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $booking = Booking::factory()->create([
            'user_id' => $user2->id, // Different user
            'total_price_vnd' => 1000000,
        ]);

        // Act
        $response = $this->postJson("/api/bookings/{$booking->booking_id}/apply-coupon", [
            'code' => 'ANY_CODE'
        ]);

        // Assert
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Không có quyền truy cập booking này'
            ]);
    }

    /** @test */
    public function it_returns_user_redemption_history()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $coupon = Coupon::factory()->create(['code' => 'HISTORY_COUPON']);
        $booking = Booking::factory()->create(['booking_code' => 'BK12345']);

        CouponRedemption::factory()->create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
            'booking_id' => $booking->booking_id,
            'amount_saved_vnd' => 75000,
        ]);

        // Act
        $response = $this->getJson('/api/coupons/my-redemptions');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'coupon_code' => 'HISTORY_COUPON',
                        'amount_saved' => 75000,
                        'booking_code' => 'BK12345',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_checks_coupon_code_existence()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'EXISTS_CODE',
            'type' => 'percent',
            'value' => 20,
            'description' => 'Test coupon description',
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Act
        $response = $this->postJson('/api/coupons/check-code', [
            'code' => 'EXISTS_CODE'
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'exists' => true,
                'coupon' => [
                    'code' => 'EXISTS_CODE',
                    'type' => 'percent',
                    'value' => 20,
                    'description' => 'Test coupon description',
                ],
                'message' => 'Mã giảm giá hợp lệ'
            ]);
    }

    /** @test */
    public function it_returns_not_found_for_non_existent_coupon()
    {
        // Act
        $response = $this->postJson('/api/coupons/check-code', [
            'code' => 'NON_EXISTENT'
        ]);

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'exists' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc không có hiệu lực'
            ]);
    }

    /** @test */
    public function it_validates_request_data_properly()
    {
        // Test missing required fields
        $response = $this->postJson('/api/coupons/validate', []);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code', 'booking_preview']);

        // Test invalid booking preview data
        $response = $this->postJson('/api/coupons/validate', [
            'code' => 'TEST',
            'booking_preview' => [
                'base_price_vnd' => 'invalid_number',
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['booking_preview.base_price_vnd']);
    }

    /** @test */
    public function it_handles_coupon_with_usage_limits()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $coupon = Coupon::factory()->create([
            'code' => 'LIMITED_USE',
            'type' => 'fixed',
            'value' => 50000,
            'usage_limit' => 1,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Create existing redemption
        CouponRedemption::factory()->create(['coupon_id' => $coupon->id]);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'total_price_vnd' => 1000000,
        ]);

        // Act
        $response = $this->postJson("/api/bookings/{$booking->booking_id}/apply-coupon", [
            'code' => 'LIMITED_USE'
        ]);

        // Assert
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'reason' => 'usage_limit',
            ]);
    }

    /** @test */
    public function it_handles_per_user_limits()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $coupon = Coupon::factory()->create([
            'code' => 'USER_LIMITED',
            'type' => 'fixed',
            'value' => 50000,
            'per_user_limit' => 1,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Create existing redemption for this user
        CouponRedemption::factory()->create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
        ]);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'total_price_vnd' => 1000000,
        ]);

        // Act
        $response = $this->postJson("/api/bookings/{$booking->booking_id}/apply-coupon", [
            'code' => 'USER_LIMITED'
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'reason' => 'user_limit',
            ]);
    }

    /** @test */
    public function it_prevents_applying_coupon_twice_to_same_booking()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'total_price_vnd' => 1000000,
        ]);

        $coupon = Coupon::factory()->create([
            'code' => 'DUPLICATE_TEST',
            'type' => 'fixed',
            'value' => 100000,
            'start_at' => Carbon::now()->subDay(),
            'end_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        // Apply coupon first time
        $this->postJson("/api/bookings/{$booking->booking_id}/apply-coupon", [
            'code' => 'DUPLICATE_TEST'
        ])->assertStatus(200);

        // Act - Apply same coupon again
        $response = $this->postJson("/api/bookings/{$booking->booking_id}/apply-coupon", [
            'code' => 'DUPLICATE_TEST'
        ]);

        // Assert - Should return success but indicate it was already applied
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'existing' => true,
                'message' => 'Mã đã được áp dụng trước đó'
            ]);
    }
}
