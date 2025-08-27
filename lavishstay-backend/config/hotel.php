<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Hotel Booking Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for hotel booking system including check-in/check-out
    | times, pending booking TTL, and cleaning settings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Check-in/Check-out Default Times
    |--------------------------------------------------------------------------
    */
    'default_checkin_time' => '14:00',
    'default_checkout_time' => '12:00',

    /*
    |--------------------------------------------------------------------------
    | Pending Booking Management
    |--------------------------------------------------------------------------
    |
    | pending_ttl_minutes: Time (in minutes) after which pending bookings
    | are automatically deleted if payment is not completed.
    |
    | pending_retention_days: Optional - number of days to keep unsuccessful
    | booking records in logs before final purge.
    |
    */
    'pending_ttl_minutes' => 15,
    'pending_retention_days' => 7,

    /*
    |--------------------------------------------------------------------------
    | Room Cleaning Configuration
    |--------------------------------------------------------------------------
    |
    | cleaning_duration_minutes: Duration (in minutes) for room cleaning
    | after checkout. During this time, room is marked as unavailable.
    |
    */
    'cleaning_duration_minutes' => 120,

    /*
    |--------------------------------------------------------------------------
    | Scheduler Configuration
    |--------------------------------------------------------------------------
    |
    | expire_scheduler_frequency: How often (in minutes) the pending booking
    | expiration scheduler should run.
    |
    | max_expired_per_run: Maximum number of expired bookings to process
    | per scheduler run to avoid long database locks.
    |
    */
    'expire_scheduler_frequency' => 1,
    'max_expired_per_run' => 50,

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | enable_audit_logging: Whether to log booking deletions for audit trail
    |
    */
    'enable_audit_logging' => true,
];
