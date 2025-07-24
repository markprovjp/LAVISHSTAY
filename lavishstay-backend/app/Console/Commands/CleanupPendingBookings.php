<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupPendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-pending-bookings {--minutes=15 : Number of minutes after which pending bookings should be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete pending bookings older than the specified number of minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $minutes = $this->option('minutes');
            $cutoffTime = Carbon::now()->subMinutes($minutes);
            
            // Get bookings to delete
            $bookingsToDelete = DB::table('booking')
                ->where('status', 'pending')
                ->where('created_at', '<=', $cutoffTime)
                ->get(['booking_id', 'booking_code', 'created_at']);
            
            $count = $bookingsToDelete->count();
            
            if ($count === 0) {
                $this->info('No pending bookings found to delete.');
                Log::info('Cleanup pending bookings: No bookings found to delete.');
                return;
            }
            
            // Show bookings to be deleted
            $this->info("Found {$count} pending bookings older than {$minutes} minutes to delete:");
            
            $headers = ['Booking ID', 'Booking Code', 'Created At', 'Age (minutes)'];
            $rows = [];
            
            foreach ($bookingsToDelete as $booking) {
                $createdAt = Carbon::parse($booking->created_at);
                $ageInMinutes = $createdAt->diffInMinutes(Carbon::now());
                
                $rows[] = [
                    $booking->booking_id,
                    $booking->booking_code,
                    $booking->created_at,
                    $ageInMinutes
                ];
            }
            
            $this->table($headers, $rows);
            
            // Ask for confirmation
            if (!$this->option('no-interaction') && !$this->confirm('Do you want to proceed with deletion?')) {
                $this->info('Operation cancelled.');
                return;
            }
            
            DB::beginTransaction();
            
            try {
                // Delete related records first (to maintain referential integrity)
                foreach ($bookingsToDelete as $booking) {
                    // Delete booking_rooms
                    DB::table('booking_rooms')
                        ->where('booking_id', $booking->booking_id)
                        ->delete();
                    
                    // Delete representatives
                    DB::table('representatives')
                        ->where('booking_id', $booking->booking_id)
                        ->delete();
                    
                    // Delete payments
                    DB::table('payment')
                        ->where('booking_id', $booking->booking_id)
                        ->delete();
                }
                
                // Now delete the bookings
                $deletedCount = DB::table('booking')
                    ->where('status', 'pending')
                    ->where('created_at', '<=', $cutoffTime)
                    ->delete();
                
                DB::commit();
                
                $this->info("Successfully deleted {$deletedCount} pending bookings.");
                Log::info("Cleanup pending bookings: Deleted {$deletedCount} bookings older than {$minutes} minutes.");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error('Error deleting bookings: ' . $e->getMessage());
                Log::error('Error deleting pending bookings: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            $this->error('Error in cleanup command: ' . $e->getMessage());
            Log::error('Error in cleanup pending bookings command: ' . $e->getMessage());
        }
    }
}

// php artisan app:cleanup-pending-bookings --no-interaction