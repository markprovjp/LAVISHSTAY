<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExpirePendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'expire:pending-bookings 
                            {--dry-run : Run without actually deleting data}
                            {--limit= : Maximum number of bookings to process}';

    /**
     * The console command description.
     */
    protected $description = 'Delete expired pending bookings that have not been completed within TTL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $isDryRun = $this->option('dry-run');
        $limit = $this->option('limit') ?: config('hotel.max_expired_per_run', 50);
        
        $ttlMinutes = config('hotel.pending_ttl_minutes', 15);
        $cutoffTime = Carbon::now()->subMinutes($ttlMinutes);
        
        $this->info("🕐 Starting expire pending bookings process...");
        $this->info("⏰ TTL: {$ttlMinutes} minutes, Cutoff: {$cutoffTime->toDateTimeString()}");
        $this->info("📊 Limit: {$limit} bookings per run");
        
        if ($isDryRun) {
            $this->warn("🧪 DRY RUN MODE - No data will be deleted");
        }

        try {
            // Find expired pending bookings
            $expiredBookingsQuery = DB::table('booking')
                ->where('status', 'Pending')
                ->where('created_at', '<', $cutoffTime)
                ->limit($limit);

            $expiredBookings = $expiredBookingsQuery->get();
            $totalFound = $expiredBookings->count();

            if ($totalFound === 0) {
                $this->info("✅ No expired pending bookings found");
                return Command::SUCCESS;
            }

            $this->info("🔍 Found {$totalFound} expired pending bookings");

            $deletedCount = 0;
            $failedCount = 0;

            foreach ($expiredBookings as $booking) {
                try {
                    if ($isDryRun) {
                        $this->line("   📝 Would delete booking: {$booking->booking_code} (ID: {$booking->booking_id})");
                        $deletedCount++;
                        continue;
                    }

                    // Process deletion in transaction
                    DB::transaction(function () use ($booking) {
                        $this->deleteBookingAndRelatedData($booking);
                    });

                    // Log deletion for audit trail
                    if (config('hotel.enable_audit_logging', true)) {
                        $this->logBookingDeletion($booking);
                    }

                    $deletedCount++;
                    $this->line("   ✅ Deleted: {$booking->booking_code} (ID: {$booking->booking_id})");

                } catch (\Exception $e) {
                    $failedCount++;
                    $this->error("   ❌ Failed to delete booking {$booking->booking_code}: {$e->getMessage()}");
                    
                    Log::error("ExpirePendingBookings: Failed to delete booking {$booking->booking_id}", [
                        'booking_code' => $booking->booking_code,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Summary
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            $this->info("📊 Summary:");
            $this->line("   • Processed: {$totalFound} bookings");
            $this->line("   • " . ($isDryRun ? "Would delete" : "Deleted") . ": {$deletedCount} bookings");
            
            if ($failedCount > 0) {
                $this->line("   • Failed: {$failedCount} bookings");
            }
            
            $this->line("   • Duration: {$duration}ms");

            if (!$isDryRun && $deletedCount > 0) {
                Log::info("ExpirePendingBookings: Successfully processed {$deletedCount} expired bookings", [
                    'total_found' => $totalFound,
                    'deleted_count' => $deletedCount,
                    'failed_count' => $failedCount,
                    'duration_ms' => $duration,
                    'cutoff_time' => $cutoffTime->toDateTimeString()
                ]);
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("💥 Fatal error in expire pending bookings process: {$e->getMessage()}");
            
            Log::error("ExpirePendingBookings: Fatal error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }

    /**
     * Delete booking and all related data
     */
    private function deleteBookingAndRelatedData($booking)
    {
        $bookingId = $booking->booking_id;

        // Delete related data in correct order (children first, then parents)
        
        // 1. Delete booking room children
        if (DB::getSchemaBuilder()->hasTable('booking_room_children')) {
            $deletedChildren = DB::table('booking_room_children')
                ->whereIn('booking_room_id', function($query) use ($bookingId) {
                    $query->select('id')
                        ->from('booking_rooms')
                        ->where('booking_id', $bookingId);
                })
                ->delete();
            
            if ($deletedChildren > 0) {
                $this->line("     📝 Deleted {$deletedChildren} booking room children");
            }
        }

        // 2. Delete booking rooms
        if (DB::getSchemaBuilder()->hasTable('booking_rooms')) {
            $deletedRooms = DB::table('booking_rooms')
                ->where('booking_id', $bookingId)
                ->delete();
            
            if ($deletedRooms > 0) {
                $this->line("     🏨 Deleted {$deletedRooms} booking rooms");
            }
        }

        // 3. Delete booking services
        if (DB::getSchemaBuilder()->hasTable('booking_services')) {
            $deletedServices = DB::table('booking_services')
                ->where('booking_id', $bookingId)
                ->delete();
                
            if ($deletedServices > 0) {
                $this->line("     🛎️ Deleted {$deletedServices} booking services");
            }
        }

        // 4. Delete payments and payment intents
        if (DB::getSchemaBuilder()->hasTable('payment')) {
            $deletedPayments = DB::table('payment')
                ->where('booking_id', $bookingId)
                ->delete();
                
            if ($deletedPayments > 0) {
                $this->line("     💳 Deleted {$deletedPayments} payments");
            }
        }

        if (DB::getSchemaBuilder()->hasTable('payment_intents')) {
            $deletedIntents = DB::table('payment_intents')
                ->where('booking_id', $bookingId)
                ->delete();
                
            if ($deletedIntents > 0) {
                $this->line("     🎯 Deleted {$deletedIntents} payment intents");
            }
        }

        // 5. Delete invoices
        if (DB::getSchemaBuilder()->hasTable('invoices')) {
            $deletedInvoices = DB::table('invoices')
                ->where('booking_id', $bookingId)
                ->delete();
                
            if ($deletedInvoices > 0) {
                $this->line("     📄 Deleted {$deletedInvoices} invoices");
            }
        }

        // 6. Delete deposit records
        if (DB::getSchemaBuilder()->hasTable('deposits')) {
            $deletedDeposits = DB::table('deposits')
                ->where('booking_id', $bookingId)
                ->delete();
                
            if ($deletedDeposits > 0) {
                $this->line("     💰 Deleted {$deletedDeposits} deposits");
            }
        }

        // 7. Delete representatives (must remove before deleting booking due to FK)
        if (DB::getSchemaBuilder()->hasTable('representatives')) {
            $deletedRepresentatives = DB::table('representatives')
                ->where('booking_id', $bookingId)
                ->delete();

            if ($deletedRepresentatives > 0) {
                $this->line("     🧾 Deleted {$deletedRepresentatives} representatives");
            }
        }

        // 7. Finally, delete the booking itself
        $deletedBooking = DB::table('booking')
            ->where('booking_id', $bookingId)
            ->delete();

        if ($deletedBooking === 0) {
            throw new \Exception("Failed to delete booking record");
        }
    }

    /**
     * Log booking deletion for audit trail
     */
    private function logBookingDeletion($booking)
    {
        try {
            Log::info("ExpirePendingBookings: Auto-deleted expired booking", [
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'user_id' => $booking->user_id,
                'guest_name' => $booking->guest_name,
                'guest_email' => $booking->guest_email,
                'total_price_vnd' => $booking->total_price_vnd,
                'created_at' => $booking->created_at,
                'reason' => 'auto-expired',
                'expired_after_minutes' => Carbon::parse($booking->created_at)->diffInMinutes(Carbon::now())
            ]);
        } catch (\Exception $e) {
            // Don't fail the deletion if logging fails
            Log::warning("ExpirePendingBookings: Failed to log deletion", [
                'booking_id' => $booking->booking_id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
