<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BookingCleanupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingCleanupServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $cleanupService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupService = new BookingCleanupService();
    }

    public function test_expire_pending_bookings_dry_run()
    {
        // Create test data
        $expiredBooking = DB::table('booking')->insertGetId([
            'booking_code' => 'TEST001',
            'status' => 'Pending',
            'guest_name' => 'Test User',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_price_vnd' => 1000000,
            'created_at' => now()->subMinutes(20), // Expired
            'updated_at' => now()
        ]);

        $validBooking = DB::table('booking')->insertGetId([
            'booking_code' => 'TEST002',
            'status' => 'Pending',
            'guest_name' => 'Test User 2',
            'guest_email' => 'test2@example.com',
            'guest_phone' => '1234567891',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_price_vnd' => 1000000,
            'created_at' => now()->subMinutes(10), // Not expired
            'updated_at' => now()
        ]);

        // Test dry run
        $result = $this->cleanupService->expirePendingBookings(true);

        $this->assertEquals(1, $result['deleted']);
        $this->assertEquals(0, $result['deletedRooms']);
        $this->assertEquals(0, $result['deletedReps']);
        $this->assertCount(1, $result['sample']);

        // Verify nothing was actually deleted
        $this->assertDatabaseHas('booking', ['booking_id' => $expiredBooking]);
        $this->assertDatabaseHas('booking', ['booking_id' => $validBooking]);
    }

    public function test_expire_pending_bookings_execute()
    {
        // Create test data
        $expiredBooking = DB::table('booking')->insertGetId([
            'booking_code' => 'TEST001',
            'status' => 'Pending',
            'guest_name' => 'Test User',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_price_vnd' => 1000000,
            'created_at' => now()->subMinutes(20), // Expired
            'updated_at' => now()
        ]);

        // Execute
        $result = $this->cleanupService->expirePendingBookings(false);

        $this->assertEquals(1, $result['deleted']);
        $this->assertArrayHasKey('run_id', $result);

        // Verify booking was deleted
        $this->assertDatabaseMissing('booking', ['booking_id' => $expiredBooking]);
    }

    public function test_complete_past_checkouts_dry_run()
    {
        // Create test data
        $pastBooking = DB::table('booking')->insertGetId([
            'booking_code' => 'TEST003',
            'status' => 'Operational',
            'guest_name' => 'Test User',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->subDays(2)->toDateString(),
            'check_out_date' => now()->subDay()->toDateString(), // Past checkout
            'total_price_vnd' => 1000000,
            'created_at' => now()->subDays(3),
            'updated_at' => now()
        ]);

        $currentBooking = DB::table('booking')->insertGetId([
            'booking_code' => 'TEST004',
            'status' => 'Operational',
            'guest_name' => 'Test User 2',
            'guest_email' => 'test2@example.com',
            'guest_phone' => '1234567891',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(), // Future checkout
            'total_price_vnd' => 1000000,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Test dry run
        $result = $this->cleanupService->completePastCheckouts(true);

        $this->assertEquals(1, $result['updated']);
        $this->assertCount(1, $result['sample']);

        // Verify nothing was actually updated
        $this->assertDatabaseHas('booking', [
            'booking_id' => $pastBooking,
            'status' => 'Operational'
        ]);
    }

    public function test_complete_cleaning_bookings_dry_run()
    {
        // Create room_type first
        $roomTypeId = DB::table('room_types')->insertGetId([
            'room_code' => 'test',
            'name' => 'Test Room Type',
            'description' => 'Test',
            'total_room' => 1,
            'base_price' => 1000000,
            'room_area' => 30,
            'max_guests' => 2
        ]);

        // Create room
        $roomId = DB::table('room')->insertGetId([
            'name' => 'Test Room 101',
            'room_type_id' => $roomTypeId,
            'status' => 'available',
            'cleaning_started_at' => now()->subHours(3),
            'cleaning_ends_at' => now()->subHour(), // Cleaning finished
            'cleaning_by' => 1,
            'cleaning_note' => 'Test cleaning',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create booking
        $bookingId = DB::table('booking')->insertGetId([
            'booking_code' => 'TEST005',
            'status' => 'Cleaning',
            'guest_name' => 'Test User',
            'guest_email' => 'test@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_price_vnd' => 1000000,
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

        // Test dry run
        $result = $this->cleanupService->completeCleaningBookings(true);

        $this->assertEquals(1, $result['cleaningUpdated']);
        $this->assertEquals(1, $result['roomsCleared']);
        $this->assertCount(1, $result['sampleBookings']);

        // Verify nothing was actually updated
        $this->assertDatabaseHas('booking', [
            'booking_id' => $bookingId,
            'status' => 'Cleaning'
        ]);
        $this->assertDatabaseHas('room', [
            'room_id' => $roomId
        ]);
    }

    public function test_run_all_dry_run()
    {
        // Create various test bookings
        $expiredPending = DB::table('booking')->insertGetId([
            'booking_code' => 'EXPIRED001',
            'status' => 'Pending',
            'guest_name' => 'Expired User',
            'guest_email' => 'expired@example.com',
            'guest_phone' => '1234567890',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDay()->toDateString(),
            'total_price_vnd' => 1000000,
            'created_at' => now()->subMinutes(20),
            'updated_at' => now()
        ]);

        $pastOperational = DB::table('booking')->insertGetId([
            'booking_code' => 'PAST001',
            'status' => 'Operational',
            'guest_name' => 'Past User',
            'guest_email' => 'past@example.com',
            'guest_phone' => '1234567891',
            'check_in_date' => now()->subDays(2)->toDateString(),
            'check_out_date' => now()->subDay()->toDateString(),
            'total_price_vnd' => 1000000,
            'created_at' => now()->subDays(3),
            'updated_at' => now()
        ]);

        // Test run all dry run
        $result = $this->cleanupService->runAll(true);

        $this->assertArrayHasKey('expire_pending', $result);
        $this->assertArrayHasKey('complete_checkouts', $result);
        $this->assertArrayHasKey('complete_cleaning', $result);
        $this->assertArrayHasKey('summary', $result);

        $this->assertEquals(1, $result['expire_pending']['deleted']);
        $this->assertEquals(1, $result['complete_checkouts']['updated']);
        $this->assertEquals(2, $result['summary']['total_bookings_affected']);
    }

    public function test_mysql_advisory_locks()
    {
        // Test acquiring lock
        $lockAcquired = $this->cleanupService->acquireLock('test_lock', 5);
        $this->assertTrue($lockAcquired);

        // Test releasing lock
        $lockReleased = $this->cleanupService->releaseLock('test_lock');
        $this->assertTrue($lockReleased);
    }
}
