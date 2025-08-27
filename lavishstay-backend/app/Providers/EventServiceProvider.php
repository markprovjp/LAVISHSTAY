<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\BookingModified;
use App\Events\BookingCheckedIn;
use App\Events\BookingCheckedOut;
use App\Events\PaymentSuccessful;
use App\Events\PaymentFailed;
use App\Events\RefundRequested;
use App\Events\RoomMaintenanceRequired;
use App\Events\RoomCleaningRequired;
use App\Events\RoomStatusChanged;
use App\Listeners\NotificationEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

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

        // Booking Events - Using full class names
        BookingCreated::class => [
            NotificationEventListener::class,
        ],
        BookingCancelled::class => [
            NotificationEventListener::class,
        ],
        BookingModified::class => [
            NotificationEventListener::class,
        ],
        BookingCheckedIn::class => [
            NotificationEventListener::class,
        ],
        BookingCheckedOut::class => [
            NotificationEventListener::class,
        ],

        // Payment Events
        PaymentSuccessful::class => [
            NotificationEventListener::class,
        ],
        PaymentFailed::class => [
            NotificationEventListener::class,
        ],
        RefundRequested::class => [
            NotificationEventListener::class,
        ],

        // Room Events
        RoomMaintenanceRequired::class => [
            NotificationEventListener::class,
        ],
        RoomCleaningRequired::class => [
            NotificationEventListener::class,
        ],
        RoomStatusChanged::class => [
            NotificationEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Log::info('EventServiceProvider booting', [
            'registered_events' => array_keys($this->listen)
        ]);

        // Register a wildcard listener to debug all events
        Event::listen('*', function ($eventName, $data) {
            if (str_contains($eventName, 'Booking') || str_contains($eventName, 'Payment') || str_contains($eventName, 'Room')) {
                Log::info('Event fired', [
                    'event' => $eventName,
                    'data_count' => count($data),
                    'timestamp' => now()
                ]);
            }
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}