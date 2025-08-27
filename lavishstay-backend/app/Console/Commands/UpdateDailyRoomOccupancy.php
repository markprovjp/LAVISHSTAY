<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoomType;
use App\Models\RoomOccupancy;
use App\Models\Booking;
use App\Models\BookingRoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateDailyRoomOccupancy extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'occupancy:update-daily {--date= : Specific date to update (Y-m-d format)}';

    /**
     * The console command description.
     */
    protected $description = 'Update daily room occupancy data for all room types to support dynamic pricing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting daily room occupancy update...');
        
        try {
            // Get target date (today or specified date)
            $targetDate = $this->option('date') 
                ? Carbon::parse($this->option('date'))->startOfDay()
                : Carbon::today();

            $this->info("📅 Processing date: {$targetDate->format('Y-m-d')}");

            // Get all room types
            $roomTypes = RoomType::all();
            
            if ($roomTypes->isEmpty()) {
                $this->warn('⚠️ No room types found!');
                return Command::FAILURE;
            }

            $this->info("🏨 Found {$roomTypes->count()} room types to process");

            $processedCount = 0;
            $errorCount = 0;

            // Process each room type
            foreach ($roomTypes as $roomType) {
                try {
                    $this->processRoomTypeOccupancy($roomType, $targetDate);
                    $processedCount++;
                    $this->info("✅ Processed room type: {$roomType->name} (ID: {$roomType->room_type_id})");
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("❌ Error processing room type {$roomType->name}: {$e->getMessage()}");
                    Log::error("Room occupancy update error for room type {$roomType->room_type_id}", [
                        'error' => $e->getMessage(),
                        'date' => $targetDate->format('Y-m-d'),
                        'room_type' => $roomType->toArray()
                    ]);
                }
            }

            // Summary
            $this->info("📊 Summary:");
            $this->info("   - Processed: {$processedCount} room types");
            $this->info("   - Errors: {$errorCount} room types");
            
            if ($errorCount === 0) {
                $this->info("🎉 Daily room occupancy update completed successfully!");
                return Command::SUCCESS;
            } else {
                $this->warn("⚠️ Daily room occupancy update completed with {$errorCount} errors. Check logs for details.");
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error("💥 Fatal error during occupancy update: {$e->getMessage()}");
            Log::error("Fatal error in daily room occupancy update", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Process occupancy data for a specific room type and date
     */
    private function processRoomTypeOccupancy(RoomType $roomType, Carbon $targetDate)
    {
        // Get total rooms for this room type
        $totalRooms = (int) $roomType->total_room;
        
        if ($totalRooms <= 0) {
            throw new \Exception("Room type {$roomType->name} has invalid total_room value: {$totalRooms}");
        }

        // Calculate booked rooms for the target date
        $bookedRooms = $this->calculateBookedRooms($roomType->room_type_id, $targetDate);

        // Ensure booked rooms doesn't exceed total rooms
        $bookedRooms = min($bookedRooms, $totalRooms);

        $this->line("   📊 Room Type: {$roomType->name}");
        $this->line("      - Total Rooms: {$totalRooms}");
        $this->line("      - Booked Rooms: {$bookedRooms}");
        $this->line("      - Available Rooms: " . ($totalRooms - $bookedRooms));

        // Update or create room occupancy record
        RoomOccupancy::updateOrCreate(
            [
                'room_type_id' => $roomType->room_type_id,
                'date' => $targetDate->format('Y-m-d')
            ],
            [
                'total_rooms' => $totalRooms,
                'booked_rooms' => $bookedRooms
                // occupancy_rate will be auto-calculated by database
            ]
        );
    }

    /**
     * Calculate booked rooms for a specific room type and date
     * 
     * Logic: Count rooms that are:
     * 1. Checked-in and not checked-out yet
     * 2. Have bookings for today (today is within check_in_date to check_out_date - 1)
     */
    private function calculateBookedRooms(int $roomTypeId, Carbon $targetDate): int
    {
        try {
            // Get bookings that overlap with the target date
            $bookedRooms = DB::table('bookings')
                ->join('booking_rooms', 'bookings.booking_id', '=', 'booking_rooms.booking_id')
                ->join('rooms', 'booking_rooms.room_id', '=', 'rooms.room_id')
                ->where('rooms.room_type_id', $roomTypeId)
                ->where('bookings.status', 'confirmed') // Only confirmed bookings
                ->where('bookings.check_in_date', '<=', $targetDate->format('Y-m-d'))
                ->where('bookings.check_out_date', '>', $targetDate->format('Y-m-d')) // check_out_date is exclusive
                ->distinct('booking_rooms.room_id') // Count unique rooms
                ->count('booking_rooms.room_id');

            return (int) $bookedRooms;

        } catch (\Exception $e) {
            Log::error("Error calculating booked rooms", [
                'room_type_id' => $roomTypeId,
                'date' => $targetDate->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            // Return 0 as fallback to prevent system errors
            return 0;
        }
    }

    /**
     * Get occupancy statistics for display
     */
    private function getOccupancyStats(Carbon $date)
    {
        return RoomOccupancy::with('roomType')
            ->where('date', $date->format('Y-m-d'))
            ->get()
            ->map(function ($occupancy) {
                return [
                    'room_type' => $occupancy->roomType->name ?? 'Unknown',
                    'total_rooms' => $occupancy->total_rooms,
                    'booked_rooms' => $occupancy->booked_rooms,
                    'available_rooms' => $occupancy->total_rooms - $occupancy->booked_rooms,
                    'occupancy_rate' => $occupancy->occupancy_rate
                ];
            });
    }
}