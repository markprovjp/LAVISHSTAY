<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SyncOccupancyData::class,
        Commands\ClearPricingCache::class,
        Commands\CleanupPendingBookings::class,
        Commands\AutoBookingCleanup::class,
        Commands\NotifyBookingReminders::class,
        Commands\ExpirePendingBookings::class,
    Commands\SchedulerDaemon::class,
        \App\Console\Commands\NotificationDebugCommand::class
    ];

    protected function schedule(Schedule $schedule)
    {
        // Update occupancy data every hour
        $schedule->command('pricing:update-occupancy')
                 ->hourly()
                 ->withoutOverlapping();
                 
        // Clear old pricing cache daily at midnight
        $schedule->command('pricing:clear-cache')
                 ->daily();
                 
        // Clean up pending bookings every 5 minutes
        $schedule->command('app:cleanup-pending-bookings')
                 ->everyFiveMinutes()
                 ->withoutOverlapping();
        $schedule->command('room-occupancy:daily-update')->daily();
        // Auto cleanup booking: xoá pending quá 15 phút, chuyển completed khi qua ngày checkout
        $schedule->command('booking:auto-cleanup')->everyMinute();
        
        // Expire pending bookings every minute (new implementation)
        $schedule->command('expire:pending-bookings')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Send booking reminders (check-in and check-out) every hour
        $schedule->command('notify:booking-reminders')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();
                 
        // Clean up old notifications daily at 2 AM
        $schedule->command('notifications:cleanup --days=30')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->appendOutputTo(storage_path('logs/notification-cleanup.log'));

        // Send shift reminders every 15 minutes during business hours
        $schedule->command('notifications:cleanup --send-reminders')
                 ->everyFifteenMinutes()
                 ->between('06:00', '23:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Send checkin reminders every hour during the day
        $schedule->command('notifications:cleanup --send-reminders')
                 ->hourly()
                 ->between('08:00', '20:00')
                 ->withoutOverlapping();

        // Weekly notification statistics report (for admins)
        $schedule->command('notifications:stats --email-report')
                 ->weeklyOn(1, '09:00') // Every Monday at 9 AM
                 ->withoutOverlapping();

        // Clean up very old notifications (older than 90 days) monthly
        $schedule->command('notifications:cleanup --days=90')
                 ->monthlyOn(1, '03:00') // First day of month at 3 AM
                 ->withoutOverlapping();

        // Backup notification data before cleanup (if needed)
        $schedule->command('notifications:backup')
                 ->monthlyOn(1, '01:00') // Before cleanup
                 ->withoutOverlapping();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
     protected function scheduleTimezone(): string
    {
        return config('app.timezone', 'UTC');
    }
}
