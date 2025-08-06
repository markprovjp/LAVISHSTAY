<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RoomOccupancyService;
use Carbon\Carbon;

class SyncOccupancyData extends Command
{
    protected $signature = 'occupancy:sync {--date=} {--days=1} {--clean}';
    protected $description = 'Sync occupancy data for specified date range';

    protected $occupancyService;

    public function __construct(RoomOccupancyService $occupancyService)
    {
        parent::__construct();
        $this->occupancyService = $occupancyService;
    }

    public function handle()
    {
        $startDate = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();
        $days = (int) $this->option('days');
        $clean = $this->option('clean');

        $this->info("Syncing occupancy data for {$days} days starting from {$startDate->format('Y-m-d')}");

        // Sync occupancy data
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $this->info("Processing date: {$date->format('Y-m-d')}");
            
            $this->occupancyService->syncOccupancyForDate($date);
        }

        // Clean old data if requested
        if ($clean) {
            $this->info('Cleaning old occupancy data...');
            $deleted = $this->occupancyService->cleanOldOccupancyData();
            $this->info("Deleted {$deleted} old records");
        }

        $this->info('Occupancy data sync completed!');
    }
}
