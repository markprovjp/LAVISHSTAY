<?php

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Events\NegativeReviewReceived;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class ReviewNotificationListener
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle review submitted event
     */
    public function handleReviewSubmitted(ReviewSubmitted $event)
    {
        try {
            $data = [
                'review_id' => $event->review->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Anonymous',
                'booking_id' => $event->booking->id ?? 'N/A',
                'rating' => $event->rating,
                'room_number' => $event->booking->room->number ?? 'N/A',
                'submitted_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendReviewNotification('new', $data);

            Log::info('New review notification sent', [
                'review_id' => $event->review->id,
                'rating' => $event->rating
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send new review notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle negative review received event
     */
    public function handleNegativeReviewReceived(NegativeReviewReceived $event)
    {
        try {
            $data = [
                'review_id' => $event->review->id ?? 'N/A',
                'customer_name' => $event->customer->name ?? 'Anonymous',
                'booking_id' => $event->booking->id ?? 'N/A',
                'rating' => $event->rating,
                'comment' => $event->comment ? substr($event->comment, 0, 100) . '...' : 'No comment',
                'room_number' => $event->booking->room->number ?? 'N/A',
                'submitted_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendReviewNotification('negative', $data);

            Log::warning('Negative review notification sent', [
                'review_id' => $event->review->id,
                'rating' => $event->rating,
                'customer' => $event->customer->name
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send negative review notification: ' . $e->getMessage());
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events)
    {
        $events->listen(
            ReviewSubmitted::class,
            [ReviewNotificationListener::class, 'handleReviewSubmitted']
        );

        $events->listen(
            NegativeReviewReceived::class,
            [ReviewNotificationListener::class, 'handleNegativeReviewReceived']
        );
    }
}