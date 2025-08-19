<?php

namespace App\Providers;

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

use App\Listeners\BookingNotificationListener;
use App\Listeners\PaymentNotificationListener;
use App\Listeners\RoomNotificationListener;
use App\Listeners\ReviewNotificationListener;
use App\Listeners\SystemNotificationListener;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Booking Events
        BookingCreated::class => [
            'App\Listeners\BookingNotificationListener@handleBookingCreated',
        ],
        BookingCancelled::class => [
            'App\Listeners\BookingNotificationListener@handleBookingCancelled',
        ],
        BookingModified::class => [
            'App\Listeners\BookingNotificationListener@handleBookingModified',
        ],
        CheckinReminder::class => [
            'App\Listeners\BookingNotificationListener@handleCheckinReminder',
        ],
        CheckoutCompleted::class => [
            'App\Listeners\BookingNotificationListener@handleCheckoutCompleted',
        ],

        // Payment Events
        PaymentSuccessful::class => [
            'App\Listeners\PaymentNotificationListener@handlePaymentSuccessful',
        ],
        PaymentFailed::class => [
            'App\Listeners\PaymentNotificationListener@handlePaymentFailed',
        ],
        RefundRequested::class => [
            'App\Listeners\PaymentNotificationListener@handleRefundRequested',
        ],

        // Room Events
        RoomMaintenanceRequired::class => [
            'App\Listeners\RoomNotificationListener@handleRoomMaintenanceRequired',
        ],
        UrgentCleaningRequired::class => [
            'App\Listeners\RoomNotificationListener@handleUrgentCleaningRequired',
        ],

        // Review Events
        ReviewSubmitted::class => [
            'App\Listeners\ReviewNotificationListener@handleReviewSubmitted',
        ],
        NegativeReviewReceived::class => [
            'App\Listeners\ReviewNotificationListener@handleNegativeReviewReceived',
        ],

        // System Events
        SystemErrorOccurred::class => [
            'App\Listeners\SystemNotificationListener@handleSystemErrorOccurred',
        ],
        SystemMaintenanceScheduled::class => [
            'App\Listeners\SystemNotificationListener@handleSystemMaintenanceScheduled',
        ],
        StaffShiftReminder::class => [
            'App\Listeners\SystemNotificationListener@handleStaffShiftReminder',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        BookingNotificationListener::class,
        PaymentNotificationListener::class,
        RoomNotificationListener::class,
        ReviewNotificationListener::class,
        SystemNotificationListener::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Additional event registrations can go here
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}