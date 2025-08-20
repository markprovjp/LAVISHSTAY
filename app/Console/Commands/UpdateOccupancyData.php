<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PricingService;

class UpdateOccupancyData extends Command
{
    protected $signature = 'pricing:update-occupancy {--date= : Specific date to update (Y-m-d format)}';
    protected $description = 'Update room occupancy data for pricing calculations';

    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        parent::__construct();
        $this->pricingService = $pricingService;
    }

    public function handle()
    {
        $date = $this->option('date') ?: now()->toDateString();
        
        $this->info("Updating occupancy data for date: {$date}");
        
        try {
            $this->pricingService->bulkUpdateOccupancy($date);
            $this->info('Occupancy data updated successfully!');
        } catch (\Exception $e) {
            $this->error('Error updating occupancy data: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
