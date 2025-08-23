<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingReminderNotification extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        $bookingId = $this->booking->booking_id ?? $this->booking->id ?? ($this->booking->getKey() ?? null);
        $bookingCode = $this->booking->booking_code ?? '';
        return [
            'booking_id' => $bookingId,
            'booking_code' => $bookingCode,
            'message' => 'Nhắc nhở: Mã đặt phòng ' . ($bookingCode ?: ($bookingId ?? '')) . ' — bạn sẽ nhận phòng vào ' . ($this->booking->check_in_date ? $this->booking->check_in_date->format('d/m/Y') : '') . '.',
            'url' => '/booking/' . ($bookingCode ?: ($bookingId ?? '')),
        ];
    }
}
