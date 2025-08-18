<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckoutCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Cảm ơn bạn đã lưu trú — vui lòng đánh giá trải nghiệm',
            'url' => '/review-booking?booking=' . $this->booking->id,
            'booking_code' => $this->booking->booking_code ?? '',
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'booking_id' => $this->booking->id,
            'message' => 'Cảm ơn bạn đã lưu trú — vui lòng đánh giá trải nghiệm',
            'url' => '/review-booking?booking=' . $this->booking->id,
            'booking_code' => $this->booking->booking_code ?? '',
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Cảm ơn bạn đã lưu trú — vui lòng đánh giá trải nghiệm',
            'url' => '/review-booking?booking=' . $this->booking->id,
            'booking_code' => $this->booking->booking_code ?? '',
        ];
    }
}
