<?php

namespace Tests\Feature\Console;

use App\Console\Commands\ExpirePendingBookings;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ExpirePendingBookingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set hotel config for testing
        Config::set('hotel.pending_ttl_minutes', 15);
        Config::set('hotel.max_expired_per_run', 50);
        Config::set('hotel.enable_audit_logging', true);
    }

    /** @test */
    public function it_deletes_expired_pending_bookings()
    {
        // Create test user
        $user = User::factory()->create();

        // Create a fresh pending booking (should not be deleted)
        $freshBooking = $this->createTestBooking([
            'status' => 'Pending',
            'created_at' => Carbon::now()->subMinutes(10), // Within TTL
        ]);

        // Create an expired pending booking (should be deleted)
        $expiredBooking = $this->createTestBooking([
            'status' => 'Pending',
            'created_at' => Carbon::now()->subMinutes(20), // Beyond TTL
        ]);

        // Create a confirmed booking that is old (should not be deleted)
        $confirmedBooking = $this->createTestBooking([
            'status' => 'Confirmed',
            'created_at' => Carbon::now()->subMinutes(30), // Old but confirmed
        ]);

        // Add related data to expired booking
        DB::table('booking_rooms')->insert([
            'booking_id' => $expiredBooking['booking_id'],
            'room_id' => 1,
            'check_in_date' => Carbon::tomorrow(),
            'check_out_date' => Carbon::tomorrow()->addDay(),
            'price_per_night' => 1000000,
            'nights' => 1,
            'total_price' => 1000000,
            'adults' => 2,
            'children' => 0,
        ]);

        // Verify initial state
        $this->assertDatabaseHas('booking', ['booking_id' => $freshBooking['booking_id']]);
        $this->assertDatabaseHas('booking', ['booking_id' => $expiredBooking['booking_id']]);
        $this->assertDatabaseHas('booking', ['booking_id' => $confirmedBooking['booking_id']]);
        $this->assertDatabaseHas('booking_rooms', ['booking_id' => $expiredBooking['booking_id']]);

        // Run the command
        $exitCode = Artisan::call('expire:pending-bookings');

        // Verify command succeeded
        $this->assertEquals(0, $exitCode);

        // Verify results
        $this->assertDatabaseHas('booking', ['booking_id' => $freshBooking['booking_id']]);
        $this->assertDatabaseMissing('booking', ['booking_id' => $expiredBooking['booking_id']]);
        $this->assertDatabaseHas('booking', ['booking_id' => $confirmedBooking['booking_id']]);
        $this->assertDatabaseMissing('booking_rooms', ['booking_id' => $expiredBooking['booking_id']]);
    }

    /** @test */
    public function it_respects_the_limit_parameter()
    {
        // Create multiple expired bookings
        $expiredBookings = [];
        for ($i = 0; $i < 5; $i++) {
            $expiredBookings[] = $this->createTestBooking([
                'status' => 'Pending',
                'created_at' => Carbon::now()->subMinutes(20),
            ]);
        }

        // Run command with limit of 3
        $exitCode = Artisan::call('expire:pending-bookings', ['--limit' => 3]);

        $this->assertEquals(0, $exitCode);

        // Verify only 3 bookings were deleted
        $remainingBookings = DB::table('booking')
            ->whereIn('booking_id', array_column($expiredBookings, 'booking_id'))
            ->count();

        $this->assertEquals(2, $remainingBookings);
    }

    /** @test */
    public function it_works_in_dry_run_mode()
    {
        // Create expired booking
        $expiredBooking = $this->createTestBooking([
            'status' => 'Pending',
            'created_at' => Carbon::now()->subMinutes(20),
        ]);

        // Run in dry-run mode
        $exitCode = Artisan::call('expire:pending-bookings', ['--dry-run' => true]);

        $this->assertEquals(0, $exitCode);

        // Verify booking was not actually deleted
        $this->assertDatabaseHas('booking', ['booking_id' => $expiredBooking['booking_id']]);
    }

    /** @test */
    public function it_handles_empty_result_gracefully()
    {
        // Create only fresh bookings
        $freshBooking = $this->createTestBooking([
            'status' => 'Pending',
            'created_at' => Carbon::now()->subMinutes(10),
        ]);

        // Run command
        $exitCode = Artisan::call('expire:pending-bookings');

        $this->assertEquals(0, $exitCode);

        // Verify booking still exists
        $this->assertDatabaseHas('booking', ['booking_id' => $freshBooking['booking_id']]);
    }

    /** @test */
    public function it_logs_deletion_events()
    {
        Log::shouldReceive('info')
            ->with(
                'ExpirePendingBookings: Auto-deleted expired booking',
                \Mockery::type('array')
            )
            ->once();

        Log::shouldReceive('info')
            ->with(
                'ExpirePendingBookings: Successfully processed 1 expired bookings',
                \Mockery::type('array')
            )
            ->once();

        // Allow other log calls
        Log::shouldReceive('info')->andReturn(null);

        // Create expired booking
        $expiredBooking = $this->createTestBooking([
            'status' => 'Pending',
            'created_at' => Carbon::now()->subMinutes(20),
        ]);

        // Run command
        Artisan::call('expire:pending-bookings');
    }

    /** @test */
    public function it_handles_database_transaction_rollback()
    {
        // Create expired booking
        $expiredBooking = $this->createTestBooking([
            'status' => 'Pending',
            'created_at' => Carbon::now()->subMinutes(20),
        ]);

        // Mock DB to simulate a failure
        DB::shouldReceive('transaction')
            ->andThrow(new \Exception('Database error'));

        // Allow other DB calls
        DB::shouldReceive('table')->andReturn(\Mockery::mock([
            'where' => \Mockery::mock([
                'where' => \Mockery::mock([
                    'limit' => \Mockery::mock([
                        'get' => collect([
                            (object) [
                                'booking_id' => $expiredBooking['booking_id'],
                                'booking_code' => $expiredBooking['booking_code'],
                                'user_id' => null,
                                'guest_name' => 'Test Guest',
                                'guest_email' => 'test@example.com',
                                'total_price_vnd' => 1000000,
                                'created_at' => Carbon::now()->subMinutes(20)->toDateTimeString()
                            ]
                        ])
                    ])
                ])
            ])
        ]));

        // Run command - should handle error gracefully
        $exitCode = Artisan::call('expire:pending-bookings');

        // Command should still return success for overall process
        $this->assertEquals(0, $exitCode);
    }

    /**
     * Helper method to create a test booking
     */
    private function createTestBooking(array $attributes = []): array
    {
        $defaultAttributes = [
            'booking_code' => 'TEST_' . time() . '_' . rand(1000, 9999),
            'user_id' => null,
            'check_in_date' => Carbon::tomorrow(),
            'check_out_date' => Carbon::tomorrow()->addDay(),
            'total_price_vnd' => 1000000,
            'guest_count' => 2,
            'status' => 'Pending',
            'guest_name' => 'Test Guest',
            'guest_email' => 'test@example.com',
            'guest_phone' => '0123456789',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $bookingData = array_merge($defaultAttributes, $attributes);
        
        $bookingId = DB::table('booking')->insertGetId($bookingData);
        $bookingData['booking_id'] = $bookingId;

        return $bookingData;
    }
}
