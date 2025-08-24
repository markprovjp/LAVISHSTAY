<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\BookingModified;
use App\Events\BookingCheckedIn;
use App\Events\BookingCheckedOut;
use App\Events\PaymentSuccessful;
use App\Events\PaymentFailed;
use App\Events\RefundRequested;
use App\Events\RefundProcessed;
use App\Events\RoomMaintenanceRequired;
use App\Events\RoomCleaningRequired;
use App\Events\RoomStatusChanged;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotificationEventListener implements ShouldQueue
{
    use InteractsWithQueue;

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
            $booking = $event->booking;
            
            $this->notificationService->sendByType('booking_new', [
                'booking_id' => $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $booking->room->room_number ?? 'N/A',
                'check_in' => $booking->check_in_date,
                'check_out' => $booking->check_out_date,
                'total_amount' => number_format($booking->total_amount ?? 0),
                'url' => route('admin.bookings.show', $booking->id)
            ]);

            Log::info('Booking created notification sent', ['booking_id' => $booking->id]);
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
            $booking = $event->booking;
            
            $this->notificationService->sendByType('booking_cancelled', [
                'booking_id' => $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $booking->room->room_number ?? 'N/A',
                'reason' => $event->reason ?? 'Không có lý do',
                'cancelled_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.bookings.show', $booking->id)
            ]);

            Log::info('Booking cancelled notification sent', ['booking_id' => $booking->id]);
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
            $booking = $event->booking;
            $changes = $event->changes;
            
            $changesList = [];
            foreach ($changes as $field => $change) {
                $changesList[] = "{$field}: {$change['old']} → {$change['new']}";
            }
            
            $this->notificationService->sendByType('booking_modified', [
                'booking_id' => $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $booking->room->room_number ?? 'N/A',
                'changes' => implode(', ', $changesList),
                'modified_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.bookings.show', $booking->id)
            ]);

            Log::info('Booking modified notification sent', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking modified notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle booking check-in event
     */
    public function handleBookingCheckedIn(BookingCheckedIn $event)
    {
        try {
            $booking = $event->booking;
            
            $this->notificationService->sendByType('booking_checkin', [
                'booking_id' => $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $booking->room->room_number ?? 'N/A',
                'checked_in_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.bookings.show', $booking->id)
            ]);

            Log::info('Booking check-in notification sent', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking check-in notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle booking check-out event
     */
    public function handleBookingCheckedOut(BookingCheckedOut $event)
    {
        try {
            $booking = $event->booking;
            
            $this->notificationService->sendByType('booking_checkout', [
                'booking_id' => $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $booking->room->room_number ?? 'N/A',
                'checked_out_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.bookings.show', $booking->id)
            ]);

            // Also trigger room cleaning notification
            if ($booking->room) {
                event(new RoomCleaningRequired($booking->room, 'normal'));
            }

            Log::info('Booking check-out notification sent', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking check-out notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment successful event
     */
    public function handlePaymentSuccessful(PaymentSuccessful $event)
    {
        try {
            $payment = $event->payment;
            
            $this->notificationService->sendByType('payment_success', [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id ?? 'N/A',
                'amount' => number_format($payment->amount ?? 0),
                'method' => $payment->payment_method ?? 'N/A',
                'processed_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.payments.show', $payment->id)
            ]);

            Log::info('Payment successful notification sent', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment successful notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment failed event
     */
    public function handlePaymentFailed(PaymentFailed $event)
    {
        try {
            $payment = $event->payment;
            
            $this->notificationService->sendByType('payment_failed', [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id ?? 'N/A',
                'amount' => number_format($payment->amount ?? 0),
                'error' => $event->error ?? 'Không xác định',
                'failed_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.payments.show', $payment->id)
            ]);

            Log::info('Payment failed notification sent', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment failed notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle refund requested event
     */
    public function handleRefundRequested(RefundRequested $event)
    {
        try {
            $payment = $event->payment;
            
            $this->notificationService->sendByType('refund_requested', [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id ?? 'N/A',
                'refund_amount' => number_format($event->amount),
                'reason' => $event->reason ?? 'Không có lý do',
                'requested_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.payments.show', $payment->id)
            ]);

            Log::info('Refund requested notification sent', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send refund requested notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle room maintenance required event
     */
    public function handleRoomMaintenanceRequired(RoomMaintenanceRequired $event)
    {
        try {
            $room = $event->room;
            
            $this->notificationService->sendByType('room_maintenance', [
                'room_id' => $room->id,
                'room_number' => $room->room_number ?? 'N/A',
                'issue' => $event->issue,
                'priority' => $event->priority,
                'reported_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.rooms.show', $room->id)
            ]);

            Log::info('Room maintenance notification sent', ['room_id' => $room->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send room maintenance notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle room cleaning required event
     */
    public function handleRoomCleaningRequired(RoomCleaningRequired $event)
    {
        try {
            $room = $event->room;
            
            $typeName = $event->urgency === 'urgent' ? 'room_cleaning_urgent' : 'room_cleaning';
            
            $this->notificationService->sendByType($typeName, [
                'room_id' => $room->id,
                'room_number' => $room->room_number ?? 'N/A',
                'urgency' => $event->urgency,
                'requested_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.rooms.show', $room->id)
            ]);

            Log::info('Room cleaning notification sent', ['room_id' => $room->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send room cleaning notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle room status changed event
     */
    public function handleRoomStatusChanged(RoomStatusChanged $event)
    {
        try {
            $room = $event->room;
            
            $this->notificationService->sendByType('room_status_changed', [
                'room_id' => $room->id,
                'room_number' => $room->room_number ?? 'N/A',
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'changed_at' => now()->format('d/m/Y H:i'),
                'url' => route('admin.rooms.show', $room->id)
            ]);

            Log::info('Room status changed notification sent', ['room_id' => $room->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send room status changed notification: ' . $e->getMessage());
        }
    }
}