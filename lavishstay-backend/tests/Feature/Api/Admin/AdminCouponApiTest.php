<?php

namespace Tests\Feature\Api\Admin;

use Tests\TestCase;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class AdminCouponApiTest extends TestCase
{
    use RefreshDatabase;

    private $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminUser = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($this->adminUser);
    }

    /** @test */
    public function it_lists_all_coupons_with_pagination()
    {
        // Arrange
        Coupon::factory()->count(15)->create();

        // Act
        $response = $this->getJson('/api/admin/coupons?per_page=10');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'code',
                        'type',
                        'value',
                        'currency',
                        'description',
                        'start_at',
                        'end_at',
                        'usage_limit',
                        'per_user_limit',
                        'min_booking_amount_vnd',
                        'applicable_room_type_ids',
                        'stackable',
                        'combinable_with',
                        'active',
                        'created_by',
                        'used_count',
                        'remaining_uses',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'current_page',
                'per_page',
                'total',
                'last_page',
            ]);

        $responseData = $response->json();
        $this->assertEquals(10, count($responseData['data']));
        $this->assertEquals(15, $responseData['total']);
    }

    /** @test */
    public function it_shows_specific_coupon_with_statistics()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'SHOW_TEST',
            'type' => 'percent',
            'value' => 15,
        ]);

        // Create some redemptions for statistics
        CouponRedemption::factory()->count(5)->create([
            'coupon_id' => $coupon->id,
            'amount_saved_vnd' => 100000,
        ]);

        // Act
        $response = $this->getJson("/api/admin/coupons/{$coupon->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'code' => 'SHOW_TEST',
                    'type' => 'percent',
                    'value' => 15,
                    'used_count' => 5,
                    'total_savings_vnd' => 500000,
                ]
            ]);
    }

    /** @test */
    public function it_creates_new_coupon_successfully()
    {
        // Arrange
        $couponData = [
            'code' => 'NEW_COUPON_2024',
            'type' => 'percent',
            'value' => 20,
            'currency' => 'VND',
            'description' => 'New Year discount',
            'start_at' => Carbon::now()->toDateTimeString(),
            'end_at' => Carbon::now()->addMonth()->toDateTimeString(),
            'usage_limit' => 1000,
            'per_user_limit' => 1,
            'min_booking_amount_vnd' => 500000,
            'applicable_room_type_ids' => [1, 2, 3],
            'stackable' => false,
            'combinable_with' => null,
            'active' => true,
        ];

        // Act
        $response = $this->postJson('/api/admin/coupons', $couponData);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'code' => 'NEW_COUPON_2024',
                    'type' => 'percent',
                    'value' => 20,
                    'description' => 'New Year discount',
                    'created_by' => $this->adminUser->id,
                ]
            ]);

        $this->assertDatabaseHas('coupons', [
            'code' => 'NEW_COUPON_2024',
            'created_by' => $this->adminUser->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_coupon()
    {
        // Act
        $response = $this->postJson('/api/admin/coupons', []);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code',
                'type',
                'value',
                'start_at',
                'end_at',
            ]);
    }

    /** @test */
    public function it_validates_unique_coupon_code()
    {
        // Arrange
        Coupon::factory()->create(['code' => 'EXISTING_CODE']);

        $couponData = [
            'code' => 'EXISTING_CODE',
            'type' => 'fixed',
            'value' => 100000,
            'start_at' => Carbon::now()->toDateTimeString(),
            'end_at' => Carbon::now()->addMonth()->toDateTimeString(),
        ];

        // Act
        $response = $this->postJson('/api/admin/coupons', $couponData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    /** @test */
    public function it_updates_coupon_successfully()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'UPDATE_TEST',
            'value' => 10,
            'description' => 'Old description',
        ]);

        $updateData = [
            'description' => 'Updated description',
            'value' => 15,
            'active' => false,
        ];

        // Act
        $response = $this->putJson("/api/admin/coupons/{$coupon->id}", $updateData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'code' => 'UPDATE_TEST',
                    'value' => 15,
                    'description' => 'Updated description',
                    'active' => false,
                ]
            ]);

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'description' => 'Updated description',
            'value' => 15,
            'active' => false,
        ]);
    }

    /** @test */
    public function it_soft_deletes_coupon()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'DELETE_TEST',
        ]);

        // Act
        $response = $this->deleteJson("/api/admin/coupons/{$coupon->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Mã giảm giá đã được xóa thành công'
            ]);

        $this->assertSoftDeleted('coupons', [
            'id' => $coupon->id,
        ]);
    }

    /** @test */
    public function it_restores_soft_deleted_coupon()
    {
        // Arrange
        $coupon = Coupon::factory()->create([
            'code' => 'RESTORE_TEST',
            'deleted_at' => Carbon::now(),
        ]);

        // Act
        $response = $this->postJson("/api/admin/coupons/{$coupon->id}/restore");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Mã giảm giá đã được khôi phục thành công'
            ]);

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_returns_coupon_statistics()
    {
        // Arrange
        $coupon1 = Coupon::factory()->create(['value' => 100000, 'type' => 'fixed']);
        $coupon2 = Coupon::factory()->create(['value' => 50000, 'type' => 'fixed']);

        // Create redemptions
        CouponRedemption::factory()->create([
            'coupon_id' => $coupon1->id,
            'amount_saved_vnd' => 100000,
        ]);
        CouponRedemption::factory()->count(2)->create([
            'coupon_id' => $coupon2->id,
            'amount_saved_vnd' => 50000,
        ]);

        // Act
        $response = $this->getJson('/api/admin/coupons/statistics');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_coupons',
                'active_coupons',
                'total_redemptions',
                'total_savings_vnd',
                'average_discount_vnd',
                'most_used_coupons',
                'recent_activity',
            ]);

        $stats = $response->json();
        $this->assertEquals(2, $stats['total_coupons']);
        $this->assertEquals(3, $stats['total_redemptions']);
        $this->assertEquals(200000, $stats['total_savings_vnd']);
    }

    /** @test */
    public function it_filters_coupons_by_status()
    {
        // Arrange
        Coupon::factory()->count(3)->create(['active' => true]);
        Coupon::factory()->count(2)->create(['active' => false]);

        // Act - Filter active coupons
        $response = $this->getJson('/api/admin/coupons?status=active');

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(3, count($response->json('data')));

        // Act - Filter inactive coupons
        $response = $this->getJson('/api/admin/coupons?status=inactive');

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(2, count($response->json('data')));
    }

    /** @test */
    public function it_filters_coupons_by_type()
    {
        // Arrange
        Coupon::factory()->count(4)->create(['type' => 'percent']);
        Coupon::factory()->count(2)->create(['type' => 'fixed']);

        // Act
        $response = $this->getJson('/api/admin/coupons?type=percent');

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(4, count($response->json('data')));
    }

    /** @test */
    public function it_searches_coupons_by_code()
    {
        // Arrange
        Coupon::factory()->create(['code' => 'SEARCH_ME_123']);
        Coupon::factory()->create(['code' => 'DIFFERENT_CODE']);

        // Act
        $response = $this->getJson('/api/admin/coupons?search=SEARCH_ME');

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals(1, count($data));
        $this->assertEquals('SEARCH_ME_123', $data[0]['code']);
    }

    /** @test */
    public function it_shows_coupon_redemption_history()
    {
        // Arrange
        $coupon = Coupon::factory()->create();
        $user = User::factory()->create();
        
        CouponRedemption::factory()->count(5)->create([
            'coupon_id' => $coupon->id,
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->getJson("/api/admin/coupons/{$coupon->id}/redemptions");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'user',
                        'booking',
                        'amount_saved_vnd',
                        'redeemed_at',
                    ]
                ],
                'current_page',
                'per_page',
                'total',
            ]);

        $this->assertEquals(5, $response->json('total'));
    }

    /** @test */
    public function it_requires_admin_authorization()
    {
        // Arrange - Create regular user
        $regularUser = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($regularUser);

        // Act
        $response = $this->getJson('/api/admin/coupons');

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function it_handles_not_found_coupon()
    {
        // Act
        $response = $this->getJson('/api/admin/coupons/999999');

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Không tìm thấy mã giảm giá'
            ]);
    }

    /** @test */
    public function it_validates_date_ranges_when_creating()
    {
        // Arrange
        $couponData = [
            'code' => 'DATE_VALIDATION_TEST',
            'type' => 'percent',
            'value' => 10,
            'start_at' => Carbon::now()->addDay()->toDateTimeString(),
            'end_at' => Carbon::now()->toDateTimeString(), // End before start
        ];

        // Act
        $response = $this->postJson('/api/admin/coupons', $couponData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['end_at']);
    }

    /** @test */
    public function it_validates_percentage_values()
    {
        // Arrange
        $couponData = [
            'code' => 'PERCENT_VALIDATION',
            'type' => 'percent',
            'value' => 150, // Invalid percentage > 100
            'start_at' => Carbon::now()->toDateTimeString(),
            'end_at' => Carbon::now()->addMonth()->toDateTimeString(),
        ];

        // Act
        $response = $this->postJson('/api/admin/coupons', $couponData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['value']);
    }
}
