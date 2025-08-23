<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingReminderNotification;
use App\Notifications\CheckoutReminderNotification;
use App\Console\Commands\NotifyBookingReminders;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class NotifyBookingRemindersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_sends_checkin_reminders_for_confirmed_bookings()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a booking with check-in in 23 hours (within 24h window)
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'confirmed',
            'check_in_date' => Carbon::now()->addHours(23)->toDateString(),
            'booking_code' => 'TEST001'
        ]);

        // Run the command
        $command = new NotifyBookingReminders();
        $command->handle();

        // Assert notification was sent
        Notification::assertSentTo(
            $user,
            BookingReminderNotification::class,
            function ($notification) use ($booking) {
                $data = $notification->toDatabase($booking->user);
                return $data['booking_id'] === $booking->booking_id;
            }
        );
    }

    public function test_sends_checkout_reminders_for_checked_in_bookings()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a booking with check-out in 23 hours (within 24h window)
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'checked_in',
            'check_out_date' => Carbon::now()->addHours(23)->toDateString(),
            'booking_code' => 'TEST002'
        ]);

        // Run the command
        $command = new NotifyBookingReminders();
        $command->handle();

        // Assert notification was sent
        Notification::assertSentTo(
            $user,
            CheckoutReminderNotification::class,
            function ($notification) use ($booking) {
                $data = $notification->toDatabase($booking->user);
                return $data['booking_id'] === $booking->booking_id;
            }
        );
    }

    public function test_does_not_send_duplicate_notifications()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a booking
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'confirmed',
            'check_in_date' => Carbon::now()->addHours(23)->toDateString(),
            'booking_code' => 'TEST003'
        ]);

        // Send initial notification
        $user->notify(new BookingReminderNotification($booking));

        // Clear notification fake to reset
        Notification::fake();

        // Run the command again
        $command = new NotifyBookingReminders();
        $command->handle();

        // Assert no new notification was sent
        Notification::assertNothingSent();
    }

    public function test_does_not_send_reminders_outside_time_window()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a booking with check-in in 25 hours (outside 24h window)
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'confirmed',
            'check_in_date' => Carbon::now()->addHours(25)->toDateString(),
            'booking_code' => 'TEST004'
        ]);

        // Run the command
        $command = new NotifyBookingReminders();
        $command->handle();

        // Assert no notification was sent
        Notification::assertNothingSent();
    }

    public function test_handles_bookings_without_users_gracefully()
    {
        // Create a booking without a user
        $booking = Booking::factory()->create([
            'user_id' => null,
            'status' => 'confirmed',
            'check_in_date' => Carbon::now()->addHours(23)->toDateString(),
            'booking_code' => 'TEST005'
        ]);

        // Run the command - should not throw exception
        $command = new NotifyBookingReminders();
        $result = $command->handle();

        // Assert command completed successfully
        $this->assertEquals(0, $result);
        
        // Assert no notifications were sent
        Notification::assertNothingSent();
    }
}
