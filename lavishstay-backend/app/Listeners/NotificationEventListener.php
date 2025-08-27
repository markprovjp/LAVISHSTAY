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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class NotificationEventListener
{
    protected $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle($event)
    {
        Log::info('NotificationEventListener handling event', [
            'event_class' => get_class($event),
            'timestamp' => now()
        ]);

        try {
            switch (get_class($event)) {
                case BookingCreated::class:
                    $this->handleBookingCreated($event);
                    break;
                case BookingCancelled::class:
                    $this->handleBookingCancelled($event);
                    break;
                case BookingModified::class:
                    $this->handleBookingModified($event);
                    break;
                case BookingCheckedIn::class:
                    $this->handleBookingCheckedIn($event);
                    break;
                case BookingCheckedOut::class:
                    $this->handleBookingCheckedOut($event);
                    break;
                case PaymentSuccessful::class:
                    $this->handlePaymentSuccessful($event);
                    break;
                case PaymentFailed::class:
                    $this->handlePaymentFailed($event);
                    break;
                case RefundRequested::class:
                    $this->handleRefundRequested($event);
                    break;
                case RoomMaintenanceRequired::class:
                    $this->handleRoomMaintenanceRequired($event);
                    break;
                case RoomCleaningRequired::class:
                    $this->handleRoomCleaningRequired($event);
                    break;
                case RoomStatusChanged::class:
                    $this->handleRoomStatusChanged($event);
                    break;
                default:
                    Log::warning('Unknown event type in NotificationEventListener', [
                        'event_class' => get_class($event)
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in NotificationEventListener', [
                'event_class' => get_class($event),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle booking created event
     */
    public function handleBookingCreated(BookingCreated $event)
    {
        try {
            $booking = $event->booking;
            
            Log::info('Handling BookingCreated event', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'has_room_relation' => isset($booking->room),
                'has_room_type_relation' => isset($booking->roomType)
            ]);

            // Resolve room information safely (handle model or collection)
            $roomNumber = $this->resolveRoomNumber($booking);

            $notificationData = [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $roomNumber,
                'check_in' => $booking->check_in_date ? 
                    (is_string($booking->check_in_date) ? $booking->check_in_date : $booking->check_in_date->format('d/m/Y')) : 'N/A',
                'check_out' => $booking->check_out_date ? 
                    (is_string($booking->check_out_date) ? $booking->check_out_date : $booking->check_out_date->format('d/m/Y')) : 'N/A',
                'total_amount' => number_format($booking->total_price_vnd ?? 0),
                'url' => '#booking-' . ($booking->booking_id ?? $booking->id)
            ];

            Log::info('Sending booking_new notification with data', $notificationData);

            $result = $this->notificationService->sendByType('booking_new', $notificationData);

            Log::info('Booking created notification result', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'result' => $result,
                'notification_data' => $notificationData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send booking created notification: ' . $e->getMessage(), [
                'booking_id' => $event->booking->booking_id ?? $event->booking->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle booking cancelled event
     */
    public function handleBookingCancelled(BookingCancelled $event)
    {
        try {
            $booking = $event->booking;
            
            $roomNumber = $this->resolveRoomNumber($booking);
            
            $result = $this->notificationService->sendByType('booking_cancelled', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $roomNumber,
                'reason' => $event->reason ?? 'Không có lý do',
                'cancelled_at' => now()->format('d/m/Y H:i'),
                'url' => '#booking-' . ($booking->booking_id ?? $booking->id)
            ]);

            Log::info('Booking cancelled notification sent', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'result' => $result
            ]);

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

            $roomNumber = $this->resolveRoomNumber($booking);
            
            $result = $this->notificationService->sendByType('booking_modified', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $roomNumber,
                'changes' => implode(', ', $changesList),
                'modified_at' => now()->format('d/m/Y H:i'),
                'url' => '#booking-' . ($booking->booking_id ?? $booking->id)
            ]);

            Log::info('Booking modified notification sent', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'result' => $result
            ]);

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

            $roomNumber = $this->resolveRoomNumber($booking);
            
            $result = $this->notificationService->sendByType('checkin_reminder', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $roomNumber,
                'checked_in_at' => now()->format('d/m/Y H:i'),
                'url' => '#booking-' . ($booking->booking_id ?? $booking->id)
            ]);

            Log::info('Booking check-in notification sent', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'result' => $result
            ]);

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

            $roomNumber = $this->resolveRoomNumber($booking);
            
            $result = $this->notificationService->sendByType('checkout_completed', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'guest_name' => $booking->guest_name ?? 'N/A',
                'room_number' => $roomNumber,
                'checked_out_at' => now()->format('d/m/Y H:i'),
                'url' => '#booking-' . ($booking->booking_id ?? $booking->id)
            ]);

            Log::info('Booking check-out notification sent', [
                'booking_id' => $booking->booking_id ?? $booking->id,
                'result' => $result
            ]);

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
            
            $result = $this->notificationService->sendByType('payment_success', [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id ?? 'N/A',
                'amount' => number_format($payment->amount ?? 0),
                'method' => $payment->payment_method ?? 'N/A',
                'processed_at' => now()->format('d/m/Y H:i'),
                'url' => '#payment-' . $payment->id
            ]);

            Log::info('Payment successful notification sent', [
                'payment_id' => $payment->id,
                'result' => $result
            ]);

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
            
            $result = $this->notificationService->sendByType('payment_failed', [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id ?? 'N/A',
                'amount' => number_format($payment->amount ?? 0),
                'error' => $event->error ?? 'Không xác định',
                'failed_at' => now()->format('d/m/Y H:i'),
                'url' => '#payment-' . $payment->id
            ]);

            Log::info('Payment failed notification sent', [
                'payment_id' => $payment->id,
                'result' => $result
            ]);

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
            
            $result = $this->notificationService->sendByType('refund_requested', [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id ?? 'N/A',
                'refund_amount' => number_format($event->amount),
                'reason' => $event->reason ?? 'Không có lý do',
                'requested_at' => now()->format('d/m/Y H:i'),
                'url' => '#payment-' . $payment->id
            ]);

            Log::info('Refund requested notification sent', [
                'payment_id' => $payment->id,
                'result' => $result
            ]);

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

            $roomId = $this->resolveRoomIdFrom($room);
            $roomNumber = $this->resolveRoomNumberFrom($room);

            $result = $this->notificationService->sendByType('room_maintenance', [
                'room_id' => $roomId,
                'room_number' => $roomNumber,
                'issue' => $event->issue,
                'priority' => $event->priority,
                'reported_at' => now()->format('d/m/Y H:i'),
                'url' => '#room-' . ($roomId ?? 'unknown')
            ]);

            Log::info('Room maintenance notification sent', [
                'room_id' => $roomId,
                'result' => $result
            ]);

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

            $roomId = $this->resolveRoomIdFrom($room);
            $roomNumber = $this->resolveRoomNumberFrom($room);

            $typeName = $event->urgency === 'urgent' ? 'room_cleaning_urgent' : 'room_maintenance';

            $result = $this->notificationService->sendByType($typeName, [
                'room_id' => $roomId,
                'room_number' => $roomNumber,
                'urgency' => $event->urgency,
                'requested_at' => now()->format('d/m/Y H:i'),
                'url' => '#room-' . ($roomId ?? 'unknown')
            ]);

            Log::info('Room cleaning notification sent', [
                'room_id' => $roomId,
                'result' => $result
            ]);

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

            $roomId = $this->resolveRoomIdFrom($room);
            $roomNumber = $this->resolveRoomNumberFrom($room);

            $result = $this->notificationService->sendByType('room_maintenance', [
                'room_id' => $roomId,
                'room_number' => $roomNumber,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'changed_at' => now()->format('d/m/Y H:i'),
                'url' => '#room-' . ($roomId ?? 'unknown')
            ]);

            Log::info('Room status changed notification sent', [
                'room_id' => $roomId,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send room status changed notification: ' . $e->getMessage());
        }
    }

    /**
     * Safely resolve room number from a booking instance.
     * Handles cases where booking->room may be a Collection, array, or model.
     */
    protected function resolveRoomNumber($booking)
    {
        try {
            // If room is a Collection, take first
            if (isset($booking->room) && $booking->room instanceof Collection) {
                $first = $booking->room->first();
                if ($first && (is_object($first) || is_array($first))) {
                    return $first->room_number ?? ($first['room_number'] ?? 'N/A');
                }
                return 'N/A';
            }

            // If room is an array
            if (isset($booking->room) && is_array($booking->room)) {
                $first = reset($booking->room);
                return $first['room_number'] ?? 'N/A';
            }

            // If room is a model/object
            if (isset($booking->room) && is_object($booking->room)) {
                return $booking->room->room_number ?? 'N/A';
            }

            // Fallback to roomType name if present
            if (method_exists($booking, 'roomType') && $booking->roomType) {
                return $booking->roomType->name ?? 'N/A';
            }

            return 'N/A';
        } catch (\Exception $e) {
            Log::warning('resolveRoomNumber failed: ' . $e->getMessage());
            return 'N/A';
        }
    }

    /**
     * Resolve a room id from various room representations (model, collection, array).
     */
    protected function resolveRoomIdFrom($room)
    {
        try {
            if ($room instanceof Collection) {
                $first = $room->first();
                return $first->id ?? ($first['id'] ?? null);
            }

            if (is_array($room)) {
                $first = reset($room);
                return $first['id'] ?? null;
            }

            if (is_object($room)) {
                return $room->id ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('resolveRoomIdFrom failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Resolve room number from various room representations (model, collection, array).
     */
    protected function resolveRoomNumberFrom($room)
    {
        try {
            if ($room instanceof Collection) {
                $first = $room->first();
                return $first->room_number ?? ($first['room_number'] ?? 'N/A');
            }

            if (is_array($room)) {
                $first = reset($room);
                return $first['room_number'] ?? 'N/A';
            }

            if (is_object($room)) {
                return $room->room_number ?? 'N/A';
            }

            return 'N/A';
        } catch (\Exception $e) {
            Log::warning('resolveRoomNumberFrom failed: ' . $e->getMessage());
            return 'N/A';
        }
    }
}