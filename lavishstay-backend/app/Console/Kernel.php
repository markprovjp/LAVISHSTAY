<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\UpdateOccupancyData::class,
        Commands\ClearPricingCache::class,
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
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
