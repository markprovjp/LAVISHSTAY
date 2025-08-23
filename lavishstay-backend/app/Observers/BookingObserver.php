<?php

namespace App\Observers;

use App\Models\Booking;
use App\Notifications\RequestReviewNotification;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Check if status changed to completed or checked_out
        if ($booking->isDirty('status') && 
            in_array($booking->status, ['completed', 'checked_out'])) {
            
            $this->sendReviewNotification($booking);
        }
    }

    /**
     * Send review notification when booking is completed
     */
    protected function sendReviewNotification(Booking $booking): void
    {
        try {
            if (!$booking->user) {
                Log::warning('No user found for booking when sending review notification', [
                    'booking_id' => $booking->booking_id
                ]);
                return;
            }

            // Check if review notification already exists
            $existingNotification = $booking->user->notifications()
                ->where('type', RequestReviewNotification::class)
                ->where('data->booking_id', $booking->booking_id)
                ->exists();

            if ($existingNotification) {
                Log::info('Review notification already exists for booking', [
                    'booking_id' => $booking->booking_id
                ]);
                return;
            }

            // Send the review notification
            $booking->user->notify(new RequestReviewNotification($booking));

            Log::info('Review notification sent for completed booking', [
                'booking_id' => $booking->booking_id,
                'user_id' => $booking->user->id,
                'booking_code' => $booking->booking_code
            ]);

            // Optional: Schedule follow-up reminder if no review after configured hours
            $this->scheduleFollowUpReminder($booking);

        } catch (\Exception $e) {
            Log::error('Failed to send review notification', [
                'booking_id' => $booking->booking_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Schedule a follow-up review reminder
     */
    protected function scheduleFollowUpReminder(Booking $booking): void
    {
        $followupHours = config('notifications.review_followup_hours', 48);
        
        // Schedule a job to check if review exists and send reminder if not
        dispatch(function () use ($booking) {
            // Check if the booking has any reviews
            if ($booking->reviews()->exists()) {
                return; // Review already exists, no need to remind
            }

            // Check if review notification already sent recently
            $recentNotification = $booking->user->notifications()
                ->where('type', RequestReviewNotification::class)
                ->where('data->booking_id', $booking->booking_id)
                ->where('created_at', '>', now()->subHours(24))
                ->exists();

            if (!$recentNotification) {
                $booking->user->notify(new RequestReviewNotification($booking));
                
                Log::info('Follow-up review notification sent', [
                    'booking_id' => $booking->booking_id,
                    'user_id' => $booking->user->id
                ]);
            }
        })->delay(now()->addHours($followupHours));
    }
}
