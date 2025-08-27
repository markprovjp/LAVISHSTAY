<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CleanupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user for testing
        $this->user = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin'
        ]);
    }

    public function test_preview_expire_pending_requires_authentication()
    {
        $response = $this->getJson('/api/admin/cleanup/expire-pending/preview');
        $response->assertStatus(401);
    }

    public function test_preview_expire_pending_returns_correct_structure()
    {
        // Create test data
        DB::table('booking')->insert([
            'booking_code' => 'TEST001',
            'status' => 'Pending',
            'guest_name' => 'Test User',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_amount' => 1000000,
            'created_at' => now()->subMinutes(20), // Expired
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/cleanup/expire-pending/preview');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'deleted',
                    'deletedRooms',
                    'deletedReps',
                    'sample'
                ],
                'requiresConfirmation',
                'message'
            ]);
    }

    public function test_execute_expire_pending_with_small_count()
    {
        // Create test data (small count, no confirmation needed)
        DB::table('booking')->insert([
            'booking_code' => 'TEST001',
            'status' => 'Pending',
            'guest_name' => 'Test User',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_amount' => 1000000,
            'created_at' => now()->subMinutes(20), // Expired
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/cleanup/expire-pending/execute');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'deleted',
                    'deletedRooms',
                    'deletedReps',
                    'sample',
                    'run_id'
                ],
                'message'
            ]);

        // Verify booking was deleted
        $this->assertDatabaseMissing('booking', [
            'booking_code' => 'TEST001',
            'status' => 'Pending'
        ]);
    }

    public function test_execute_with_confirmation_code_required()
    {
        // Create many test bookings to trigger confirmation requirement
        for ($i = 1; $i <= 15; $i++) {
            DB::table('booking')->insert([
                'booking_code' => "TEST{$i:03d}",
                'status' => 'Pending',
                'guest_name' => "Test User {$i}",
                'guest_email' => "test{$i}@example.com",
                'guest_phone' => '1234567890',
                'check_in_date' => now()->toDateString(),
                'check_out_date' => now()->addDay()->toDateString(),
                'total_amount' => 1000000,
                'created_at' => now()->subMinutes(20), // Expired
                'updated_at' => now()
            ]);
        }

        // First, try without confirmation code (should fail)
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/cleanup/expire-pending/execute');

        $response->assertStatus(422);

        // Get confirmation code
        $codeResponse = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/cleanup/confirmation-code', [
                'operation' => 'expire_pending',
                'count' => 15
            ]);

        $codeResponse->assertStatus(200);
        $confirmationCode = $codeResponse->json('data.confirmation_code');

        // Try with correct confirmation code
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/cleanup/expire-pending/execute', [
                'confirmation_code' => $confirmationCode
            ]);

        $response->assertStatus(200);
        $this->assertEquals(15, $response->json('data.deleted'));
    }

    public function test_preview_complete_past_checkouts()
    {
        // Create past operational booking
        DB::table('booking')->insert([
            'booking_code' => 'PAST001',
            'status' => 'Operational',
            'guest_name' => 'Past User',
            'guest_email' => 'past@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->subDays(2)->toDateString(),
            'check_out_date' => now()->subDay()->toDateString(), // Past checkout
            'total_amount' => 1000000,
            'created_at' => now()->subDays(3),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/cleanup/complete-checkouts/preview');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'updated',
                    'sample'
                ],
                'message'
            ]);

        $this->assertEquals(1, $response->json('data.updated'));
    }

    public function test_preview_complete_cleaning_bookings()
    {
        // Create room with finished cleaning
        $roomId = DB::table('room')->insertGetId([
            'name' => 'Test Room 101',
            'room_type_id' => 1,
            'status' => 'available',
            'cleaning_started_at' => now()->subHours(3),
            'cleaning_ends_at' => now()->subHour(), // Cleaning finished
            'cleaning_by' => $this->user->id,
            'cleaning_note' => 'Test cleaning',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create cleaning booking
        $bookingId = DB::table('booking')->insertGetId([
            'booking_code' => 'CLEAN001',
            'status' => 'Cleaning',
            'guest_name' => 'Cleaning User',
            'guest_email' => 'clean@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_amount' => 1000000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Link booking to room
        DB::table('booking_rooms')->insert([
            'booking_id' => $bookingId,
            'room_id' => $roomId,
            'guest_count' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/cleanup/complete-cleaning/preview');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'cleaningUpdated',
                    'roomsCleared',
                    'sampleBookings',
                    'sampleRooms'
                ],
                'message'
            ]);

        $this->assertEquals(1, $response->json('data.cleaningUpdated'));
        $this->assertEquals(1, $response->json('data.roomsCleared'));
    }

    public function test_preview_run_all()
    {
        // Create test data for all operations
        DB::table('booking')->insert([
            [
                'booking_code' => 'EXPIRED001',
                'status' => 'Pending',
                'guest_name' => 'Expired User',
                'guest_email' => 'expired@example.com',
                'guest_phone' => '1234567890',
                'check_in_date' => now()->toDateString(),
                'check_out_date' => now()->addDay()->toDateString(),
                'total_amount' => 1000000,
                'created_at' => now()->subMinutes(20),
                'updated_at' => now()
            ],
            [
                'booking_code' => 'PAST001',
                'status' => 'Operational',
                'guest_name' => 'Past User',
                'guest_email' => 'past@example.com',
                'guest_phone' => '1234567891',
                'check_in_date' => now()->subDays(2)->toDateString(),
                'check_out_date' => now()->subDay()->toDateString(),
                'total_amount' => 1000000,
                'created_at' => now()->subDays(3),
                'updated_at' => now()
            ]
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/admin/cleanup/run-all/preview');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'run_id',
                    'expire_pending',
                    'complete_checkouts',
                    'complete_cleaning',
                    'summary' => [
                        'total_bookings_affected',
                        'total_rooms_affected'
                    ]
                ],
                'message'
            ]);

        $this->assertEquals(2, $response->json('data.summary.total_bookings_affected'));
    }

    public function test_get_confirmation_code()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/cleanup/confirmation-code', [
                'operation' => 'expire_pending',
                'count' => 25
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'confirmation_code',
                    'expires_at'
                ],
                'message'
            ]);

        $this->assertEquals(6, strlen($response->json('data.confirmation_code')));
    }

    public function test_invalid_confirmation_code_fails()
    {
        // Create many test bookings to trigger confirmation requirement
        for ($i = 1; $i <= 15; $i++) {
            DB::table('booking')->insert([
                'booking_code' => "TEST{$i:03d}",
                'status' => 'Pending',
                'guest_name' => "Test User {$i}",
                'guest_email' => "test{$i}@example.com",
                'guest_phone' => '1234567890',
                'check_in_date' => now()->toDateString(),
                'check_out_date' => now()->addDay()->toDateString(),
                'total_amount' => 1000000,
                'created_at' => now()->subMinutes(20), // Expired
                'updated_at' => now()
            ]);
        }

        // Try with invalid confirmation code
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/admin/cleanup/expire-pending/execute', [
                'confirmation_code' => 'invalid'
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Mã xác nhận không đúng'
            ]);
    }
}
