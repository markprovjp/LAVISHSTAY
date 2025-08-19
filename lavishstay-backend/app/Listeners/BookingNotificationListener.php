<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\BookingModified;
use App\Events\CheckinReminder;
use App\Events\CheckoutCompleted;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class BookingNotificationListener
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle booking created event
     */
    public function handleBookingCreated(BookingCreated $event)
    {
        try {
            $data = [
                'booking_id' => $event->booking->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Unknown',
                'room_number' => $event->booking->room->number ?? 'N/A',
                'checkin_date' => $event->booking->checkin_date ?? 'N/A',
                'checkout_date' => $event->booking->checkout_date ?? 'N/A',
                'total_amount' => number_format($event->booking->total_amount ?? 0) . ' VND',
            ];

            $this->notificationService->sendBookingNotification('new', $data);

            Log::info('Booking created notification sent', ['booking_id' => $event->booking->id]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking created notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle booking cancelled event
     */
    public function handleBookingCancelled(BookingCancelled $event)
    {
        try {
            $data = [
                'booking_id' => $event->booking->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Unknown',
                'room_number' => $event->booking->room->number ?? 'N/A',
                'reason' => $event->reason ?? 'No reason provided',
                'cancelled_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendBookingNotification('cancelled', $data);

            Log::info('Booking cancelled notification sent', ['booking_id' => $event->booking->id]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking cancelled notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle booking modified event
     */
    public function handleBookingModified(BookingModified $event)
    {
        try {
            $data = [
                'booking_id' => $event->booking->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Unknown',
                'room_number' => $event->booking->room->number ?? 'N/A',
                'changes' => implode(', ', $event->changes),
                'modified_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendBookingNotification('modified', $data);

            Log::info('Booking modified notification sent', ['booking_id' => $event->booking->id]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking modified notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle checkin reminder event
     */
    public function handleCheckinReminder(CheckinReminder $event)
    {
        try {
            $data = [
                'booking_id' => $event->booking->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Unknown',
                'room_number' => $event->booking->room->number ?? 'N/A',
                'checkin_date' => $event->booking->checkin_date ?? 'Today',
                'checkin_time' => $event->booking->checkin_time ?? '14:00',
            ];

            $this->notificationService->sendBookingNotification('checkin_reminder', $data);

            Log::info('Checkin reminder notification sent', ['booking_id' => $event->booking->id]);

        } catch (\Exception $e) {
            Log::error('Failed to send checkin reminder notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle checkout completed event
     */
    public function handleCheckoutCompleted(CheckoutCompleted $event)
    {
        try {
            $data = [
                'booking_id' => $event->booking->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Unknown',
                'room_number' => $event->room->number ?? 'N/A',
                'checkout_time' => now()->format('H:i'),
                'checkout_date' => now()->format('d/m/Y'),
            ];

            $this->notificationService->sendBookingNotification('checkout_completed', $data);

            Log::info('Checkout completed notification sent', ['booking_id' => $event->booking->id]);

        } catch (\Exception $e) {
            Log::error('Failed to send checkout completed notification: ' . $e->getMessage());
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events)
    {
        $events->listen(
            BookingCreated::class,
            [BookingNotificationListener::class, 'handleBookingCreated']
        );

        $events->listen(
            BookingCancelled::class,
            [BookingNotificationListener::class, 'handleBookingCancelled']
        );

        $events->listen(
            BookingModified::class,
            [BookingNotificationListener::class, 'handleBookingModified']
        );

        $events->listen(
            CheckinReminder::class,
            [BookingNotificationListener::class, 'handleCheckinReminder']
        );

        $events->listen(
            CheckoutCompleted::class,
            [BookingNotificationListener::class, 'handleCheckoutCompleted']
        );
    }
}