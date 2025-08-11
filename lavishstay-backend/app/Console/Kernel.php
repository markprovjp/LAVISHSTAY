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
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
