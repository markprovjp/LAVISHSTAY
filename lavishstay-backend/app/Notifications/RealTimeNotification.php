<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class RealTimeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $message;
    public $icon;
    public $url;
    public $priority;
    public $color;
    public $notificationId;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        string $title, 
        string $message, 
        string $icon = '📣', 
        string $url = '#',
        string $priority = 'normal',
        string $color = '#3B82F6',
        string $notificationId = null
    ) {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
        $this->url = $url;
        $this->priority = $priority;
        $this->color = $color;
        $this->notificationId = $notificationId ?? Str::uuid();
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->notificationId,
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'url' => $this->url,
            'priority' => $this->priority,
            'color' => $this->color,
            'created_at' => now()->format('M d, Y H:i'),
            'time_ago' => 'Just now',
            'user_id' => $notifiable->id,
        ]);
    }

    /**
     * Get the type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return 'notification.new';
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            'private-users.' . $this->notifiable->id
        ];
    }

    /**
     * Determine if the notification should be broadcast.
     */
    public function shouldBroadcast(): bool
    {
        return true;
    }

    /**
     * Get the data to store in the database (if using database channel).
     */
    public function toDatabase($notifiable): array
    {
        return [
            'id' => $this->notificationId,
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'url' => $this->url,
            'priority' => $this->priority,
            'color' => $this->color,
            'created_at' => now()->format('M d, Y H:i'),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'id' => $this->notificationId,
            'title' => $this->title,
            'message' => $this->message,
            'icon' => $this->icon,
            'url' => $this->url,
            'priority' => $this->priority,
            'color' => $this->color,
        ];
    }
}