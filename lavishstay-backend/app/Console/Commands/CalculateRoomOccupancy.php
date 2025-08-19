<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RoomOccupancyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalculateRoomOccupancy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'occupancy:calculate 
                            {--date= : Specific date to calculate (Y-m-d format)}
                            {--cleanup : Clean up old records}
                            {--days-to-keep=365 : Number of days to keep when cleaning up}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate daily room occupancy rates for room_occupancy table';

    /**
     * RoomOccupancyService instance
     */
    protected $occupancyService;

    /**
     * Create a new command instance.
     */
    public function __construct(RoomOccupancyService $occupancyService)
    {
        parent::__construct();
        $this->occupancyService = $occupancyService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('🏨 Starting room occupancy calculation...');
            
            // Get date from option or use today
            $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();
            
            $this->info("📅 Calculating occupancy for date: {$date->toDateString()}");

            // Calculate room occupancy
            $result = $this->occupancyService->calculateDailyOccupancy($date);

            if ($result['success']) {
                $this->displayResults($result);
                $this->info('✅ Room occupancy calculation completed successfully!');
                
                Log::info('Room occupancy calculation completed via command', [
                    'date' => $date->toDateString(),
                    'summary' => $result['summary']
                ]);
            } else {
                $this->error('❌ Failed to calculate room occupancy: ' . $result['error']);
                Log::error('Room occupancy calculation failed via command', [
                    'date' => $date->toDateString(),
                    'error' => $result['error']
                ]);
                return 1;
            }

            // Cleanup old records if requested
            if ($this->option('cleanup')) {
                $this->info('🧹 Cleaning up old occupancy records...');
                $daysToKeep = (int) $this->option('days-to-keep');
                $deletedCount = $this->occupancyService->cleanupOldRecords($daysToKeep);
                $this->info("🗑️  Cleaned up {$deletedCount} old records (keeping last {$daysToKeep} days)");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('💥 Command failed with exception: ' . $e->getMessage());
            
            Log::error('Room occupancy command failed with exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }
    }

    /**
     * Display calculation results in a formatted table
     */
    private function displayResults(array $result)
    {
        $this->info("\n📊 Occupancy Calculation Results for {$result['date']}:");
        
        // Display summary
        $summary = $result['summary'];
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Rooms', $summary['total_rooms']],
                ['Total Booked', $summary['total_booked']],
                ['Total Available', $summary['total_available']],
                ['Overall Occupancy Rate', $summary['overall_occupancy_rate'] . '%'],
                ['Room Types Processed', $summary['room_types_processed']]
            ]
        );

        // Display detailed results by room type
        if (!empty($result['results'])) {
            $this->info("\n🏨 Details by Room Type:");
            
            $tableData = [];
            foreach ($result['results'] as $roomResult) {
                $tableData[] = [
                    $roomResult['room_type_name'],
                    $roomResult['total_rooms'],
                    $roomResult['booked_rooms'],
                    $roomResult['available_rooms'],
                    $roomResult['occupancy_rate'] . '%'
                ];
            }

            $this->table(
                [
                    'Room Type',
                    'Total',
                    'Booked',
                    'Available',
                    'Occupancy %'
                ],
                $tableData
            );
        }
    }
}