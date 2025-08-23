# Booking Notifications Implementation

This implementation provides automated booking reminders and review notifications for the LavishStay system.

## Features

1. **Check-in Reminders**: Automatically notify guests 24 hours before their check-in date
2. **Check-out Reminders**: Automatically notify guests 24 hours before their check-out date
3. **Review Requests**: Immediately notify guests to leave a review when their booking is completed
4. **Follow-up Reminders**: Optional follow-up review reminder after 48 hours if no review is submitted

## Files Added/Modified

### Notification Classes

-   `app/Notifications/BookingReminderNotification.php` - Check-in reminders
-   `app/Notifications/CheckoutReminderNotification.php` - Check-out reminders
-   `app/Notifications/RequestReviewNotification.php` - Review requests

### Commands

-   `app/Console/Commands/NotifyBookingReminders.php` - Scheduled command to send reminders
-   `app/Console/Kernel.php` - Updated to register the scheduled command (runs hourly)

### Observers

-   `app/Observers/BookingObserver.php` - Detects booking status changes and triggers review notifications
-   `app/Providers/AppServiceProvider.php` - Updated to register the BookingObserver

### Configuration

-   `config/notifications.php` - Configuration for notification timing windows

### Tests

-   `tests/Feature/NotifyBookingRemindersTest.php` - Tests for the scheduled command
-   `tests/Unit/BookingObserverTest.php` - Tests for the booking observer
-   `database/factories/BookingFactory.php` - Factory for testing bookings
-   `app/Models/Booking.php` - Added HasFactory trait

## Usage

### Manual Command Execution

```bash
php artisan notify:booking-reminders
```

### Running Tests

```bash
# Run notification tests
./vendor/bin/phpunit --filter NotifyBookingRemindersTest
./vendor/bin/phpunit --filter BookingObserverTest

# Run all tests
./vendor/bin/phpunit
```

### Configuration

Edit `config/notifications.php` to adjust timing:

```php
return [
    'checkin_window_hours' => 24,    // Hours before check-in to send reminder
    'checkout_window_hours' => 24,   // Hours before check-out to send reminder
    'review_followup_hours' => 48,   // Hours after checkout to send follow-up
];
```

## How It Works

### Scheduled Reminders

-   The `notify:booking-reminders` command runs hourly via Laravel's task scheduler
-   It queries for bookings with check-in/check-out dates within the configured windows
-   Only sends notifications for bookings in `confirmed` (check-in) or `checked_in` (check-out) status
-   Prevents duplicate notifications by checking existing notifications in the database

### Immediate Review Notifications

-   The `BookingObserver` watches for booking status changes
-   When a booking status changes to `completed` or `checked_out`, it immediately sends a review notification
-   Optionally schedules a follow-up reminder after the configured delay

### Notification Storage

-   All notifications are stored in the database using Laravel's notification system
-   The frontend polls `/api/reception/notifications` to display notifications
-   No real-time WebSocket connection required - uses polling approach

## Verification Steps

1. **Create test bookings** with check-in/check-out dates within 24 hours
2. **Run the command manually**: `php artisan notify:booking-reminders`
3. **Check notifications**: Call `GET /api/reception/notifications` to verify notifications were created
4. **Test status changes**: Update a booking status to `completed` and verify review notification is sent
5. **Run automated tests**: Execute the test suite to verify all functionality

## Queue Configuration

For production environments with high volumes:

-   Set `QUEUE_CONNECTION=redis` or `database` in `.env`
-   Run queue workers: `php artisan queue:work`
-   The follow-up reminders will be queued for delayed execution

## Logging

All notification activities are logged to `storage/logs/laravel.log` with context including:

-   Booking IDs and codes
-   User IDs
-   Success/failure status
-   Error messages for debugging
