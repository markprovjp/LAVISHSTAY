<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PricingService;
use App\Models\RoomType;

class ClearPricingCache extends Command
{
    protected $signature = 'pricing:clear-cache {--room-type= : Specific room type ID} {--date= : Specific date}';
    protected $description = 'Clear pricing cache data';

    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        parent::__construct();
        $this->pricingService = $pricingService;
    }

    public function handle()
    {
        $roomTypeId = $this->option('room-type');
        $date = $this->option('date');
        
        try {
            if ($roomTypeId) {
                $this->pricingService->clearPricingCache($roomTypeId, $date, $date);
                $this->info("Cleared cache for room type {$roomTypeId}");
            } else {
                $roomTypes = RoomType::pluck('room_type_id');
                foreach ($roomTypes as $id) {
                    $this->pricingService->clearPricingCache($id, $date, $date);
                }
                $this->info('Cleared cache for all room types');
            }
        } catch (\Exception $e) {
            $this->error('Error clearing cache: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
