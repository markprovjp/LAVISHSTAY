<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\User;
use App\Observers\BookingObserver;
use App\Notifications\RequestReviewNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class BookingObserverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_sends_review_notification_when_booking_status_changes_to_completed()
    {
        // Create a user and booking
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'checked_in',
            'booking_code' => 'TEST001'
        ]);

        // Update booking status to completed
        $booking->update(['status' => 'completed']);

        // Assert review notification was sent
        Notification::assertSentTo(
            $user,
            RequestReviewNotification::class,
            function ($notification) use ($booking) {
                $data = $notification->toDatabase($booking->user);
                return $data['booking_id'] === $booking->booking_id;
            }
        );
    }

    public function test_sends_review_notification_when_booking_status_changes_to_checked_out()
    {
        // Create a user and booking
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'checked_in',
            'booking_code' => 'TEST002'
        ]);

        // Update booking status to checked_out
        $booking->update(['status' => 'checked_out']);

        // Assert review notification was sent
        Notification::assertSentTo(
            $user,
            RequestReviewNotification::class,
            function ($notification) use ($booking) {
                $data = $notification->toDatabase($booking->user);
                return $data['booking_id'] === $booking->booking_id;
            }
        );
    }

    public function test_does_not_send_review_notification_for_other_status_changes()
    {
        // Create a user and booking
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'booking_code' => 'TEST003'
        ]);

        // Update booking status to confirmed (not completed/checked_out)
        $booking->update(['status' => 'confirmed']);

        // Assert no review notification was sent
        Notification::assertNothingSent();
    }

    public function test_does_not_send_duplicate_review_notifications()
    {
        // Create a user and booking
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'checked_in',
            'booking_code' => 'TEST004'
        ]);

        // Send initial review notification
        $user->notify(new RequestReviewNotification($booking));

        // Clear notification fake to reset
        Notification::fake();

        // Update booking status to completed again
        $booking->update(['status' => 'completed']);

        // Assert no new notification was sent
        Notification::assertNothingSent();
    }

    public function test_handles_booking_without_user_gracefully()
    {
        // Create a booking without a user
        $booking = Booking::factory()->create([
            'user_id' => null,
            'status' => 'checked_in',
            'booking_code' => 'TEST005'
        ]);

        // Update booking status to completed - should not throw exception
        $booking->update(['status' => 'completed']);

        // Assert no notifications were sent
        Notification::assertNothingSent();
    }

    public function test_does_not_send_notification_when_status_unchanged()
    {
        // Create a user and booking
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'booking_code' => 'TEST006'
        ]);

        // Update booking without changing status
        $booking->update(['notes' => 'Updated notes']);

        // Assert no review notification was sent
        Notification::assertNothingSent();
    }
}
