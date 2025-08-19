<?php

namespace App\Helpers;

use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\BookingModified;
use App\Events\CheckinReminder;
use App\Events\CheckoutCompleted;
use App\Events\PaymentSuccessful;
use App\Events\PaymentFailed;
use App\Events\RefundRequested;
use App\Events\RoomMaintenanceRequired;
use App\Events\UrgentCleaningRequired;
use App\Events\ReviewSubmitted;
use App\Events\NegativeReviewReceived;
use App\Events\SystemErrorOccurred;
use App\Events\SystemMaintenanceScheduled;
use App\Events\StaffShiftReminder;

class NotificationHelper
{
    /**
     * Trigger booking created notification
     */
    public static function bookingCreated($booking, $customer)
    {
        event(new BookingCreated($booking, $customer));
    }

    /**
     * Trigger booking cancelled notification
     */
    public static function bookingCancelled($booking, $customer, $reason = null)
    {
        event(new BookingCancelled($booking, $customer, $reason));
    }

    /**
     * Trigger booking modified notification
     */
    public static function bookingModified($booking, $customer, $changes = [])
    {
        event(new BookingModified($booking, $customer, $changes));
    }

    /**
     * Trigger checkin reminder notification
     */
    public static function checkinReminder($booking, $customer)
    {
        event(new CheckinReminder($booking, $customer));
    }

    /**
     * Trigger checkout completed notification
     */
    public static function checkoutCompleted($booking, $customer, $room)
    {
        event(new CheckoutCompleted($booking, $customer, $room));
    }

    /**
     * Trigger payment successful notification
     */
    public static function paymentSuccessful($payment, $booking, $amount)
    {
        event(new PaymentSuccessful($payment, $booking, $amount));
    }

    /**
     * Trigger payment failed notification
     */
    public static function paymentFailed($payment, $booking, $amount, $reason = null)
    {
        event(new PaymentFailed($payment, $booking, $amount, $reason));
    }

    /**
     * Trigger refund requested notification
     */
    public static function refundRequested($refund, $booking, $customer, $amount, $reason = null)
    {
        event(new RefundRequested($refund, $booking, $customer, $amount, $reason));
    }

    /**
     * Trigger room maintenance required notification
     */
    public static function roomMaintenanceRequired($room, $issue, $priority = 'normal', $reportedBy = null)
    {
        event(new RoomMaintenanceRequired($room, $issue, $priority, $reportedBy));
    }

    /**
     * Trigger urgent cleaning required notification
     */
    public static function urgentCleaningRequired($room, $deadline, $reason = null, $requestedBy = null)
    {
        event(new UrgentCleaningRequired($room, $deadline, $reason, $requestedBy));
    }

    /**
     * Trigger review submitted notification
     */
    public static function reviewSubmitted($review, $customer, $booking, $rating)
    {
        event(new ReviewSubmitted($review, $customer, $booking, $rating));
        
        // Also trigger negative review if rating is low
        if ($rating <= 2) {
            event(new NegativeReviewReceived($review, $customer, $booking, $rating, $review->comment ?? null));
        }
    }

    /**
     * Trigger negative review received notification
     */
    public static function negativeReviewReceived($review, $customer, $booking, $rating, $comment = null)
    {
        event(new NegativeReviewReceived($review, $customer, $booking, $rating, $comment));
    }

    /**
     * Trigger system error occurred notification
     */
    public static function systemErrorOccurred($errorMessage, $errorCode = null, $context = [], $severity = 'high')
    {
        event(new SystemErrorOccurred($errorMessage, $errorCode, $context, $severity));
    }

    /**
     * Trigger system maintenance scheduled notification
     */
    public static function systemMaintenanceScheduled($startTime, $endTime, $description = null, $affectedServices = [])
    {
        event(new SystemMaintenanceScheduled($startTime, $endTime, $description, $affectedServices));
    }

    /**
     * Trigger staff shift reminder notification
     */
    public static function staffShiftReminder($staff, $shift, $minutesUntilStart)
    {
        event(new StaffShiftReminder($staff, $shift, $minutesUntilStart));
    }

    /**
     * Quick method to send custom notification
     */
    public static function sendCustomNotification($typeName, $data = [], $userIds = [])
    {
        $notificationService = app(\App\Services\NotificationService::class);
        return $notificationService->sendByType($typeName, $data, $userIds);
    }
}