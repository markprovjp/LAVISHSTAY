<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BookingCleanupService
{
    /**
     * Expire pending bookings older than 15 minutes
     */
    public function expirePendingBookings(bool $dryRun = true): array
    {
        $now = Carbon::now();
        $pendingExpired = $now->copy()->subMinutes(15);
        
        // Get bookings to expire - only Pending status
        $pendingQuery = DB::table('booking')
            ->where('status', 'Pending')
            ->where('created_at', '<', $pendingExpired);
            
        $pendingIds = $pendingQuery->pluck('booking_id');
        $sampleBookings = $pendingQuery->limit(5)
            ->get(['booking_id', 'booking_code', 'created_at', 'status'])
            ->toArray();
        
        if ($dryRun) {
            // Preview mode - just return counts
            $roomCount = 0;
            $repCount = 0;
            
            if ($pendingIds->count() > 0) {
                $roomCount = DB::table('booking_rooms')
                    ->whereIn('booking_id', $pendingIds)
                    ->count();
                    
                $repCount = DB::table('representatives')
                    ->whereIn('booking_id', $pendingIds)
                    ->count();
            }
            
            return [
                'deleted' => $pendingIds->count(),
                'deletedRooms' => $roomCount,
                'deletedReps' => $repCount,
                'sample' => $sampleBookings
            ];
        }
        
        // Real execution
        $runId = Str::uuid();
        $deleted = 0;
        $deletedRooms = 0;
        $deletedReps = 0;
        
        if ($pendingIds->count() > 0) {
            DB::beginTransaction();
            try {
                // Delete related records first
                $deletedRooms = DB::table('booking_rooms')
                    ->whereIn('booking_id', $pendingIds)
                    ->delete();
                    
                $deletedReps = DB::table('representatives')
                    ->whereIn('booking_id', $pendingIds)
                    ->delete();
                    
                // Delete bookings
                $deleted = DB::table('booking')
                    ->whereIn('booking_id', $pendingIds)
                    ->delete();
                
                // Log audit
                $this->logAudit('expire_pending_bookings', [
                    'run_id' => $runId,
                    'deleted' => $deleted,
                    'deleted_rooms' => $deletedRooms,
                    'deleted_reps' => $deletedReps,
                    'sample_ids' => $pendingIds->take(10)->toArray()
                ]);
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to expire pending bookings: ' . $e->getMessage());
                throw $e;
            }
        }
        
        return [
            'deleted' => $deleted,
            'deletedRooms' => $deletedRooms,
            'deletedReps' => $deletedReps,
            'sample' => $sampleBookings,
            'run_id' => $runId
        ];
    }
    
    /**
     * Complete past checkouts - Operational -> Completed
     */
    public function completePastCheckouts(bool $dryRun = true): array
    {
        $now = Carbon::now();
        
        $query = DB::table('booking')
            ->where('status', 'Operational')
            ->where('check_out_date', '<', $now->toDateString());
            
        $bookings = $query->get(['booking_id', 'booking_code', 'check_out_date', 'status']);
        $count = $bookings->count();
        
        if ($dryRun) {
            return [
                'updated' => $count,
                'sample' => $bookings->take(5)->toArray()
            ];
        }
        
        // Real execution
        $runId = Str::uuid();
        $updated = 0;
        
        if ($count > 0) {
            DB::beginTransaction();
            try {
                $updated = DB::table('booking')
                    ->where('status', 'Operational')
                    ->where('check_out_date', '<', $now->toDateString())
                    ->update(['status' => 'Completed']);
                
                $this->logAudit('complete_past_checkouts', [
                    'run_id' => $runId,
                    'updated' => $updated,
                    'sample_ids' => $bookings->pluck('booking_id')->take(10)->toArray()
                ]);
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to complete past checkouts: ' . $e->getMessage());
                throw $e;
            }
        }
        
        return [
            'updated' => $updated,
            'sample' => $bookings->take(5)->toArray(),
            'run_id' => $runId
        ];
    }
    
    /**
     * Complete cleaning bookings when cleaning time is over
     */
    public function completeCleaningBookings(bool $dryRun = true): array
    {
        $now = Carbon::now();
        
        // Find bookings in Cleaning status with expired cleaning time
        $cleaningBookings = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->join('room as r', 'br.room_id', '=', 'r.room_id')
            ->where('b.status', 'Cleaning')
            ->whereNotNull('r.cleaning_ends_at')
            ->where('r.cleaning_ends_at', '<=', $now)
            ->distinct()
            ->select(['b.booking_id', 'b.booking_code', 'b.status', 'r.room_id', 'r.name as room_name', 'r.cleaning_ends_at'])
            ->get();
            
        $bookingIds = $cleaningBookings->pluck('booking_id')->unique();
        $roomIds = $cleaningBookings->pluck('room_id')->unique();
        
        if ($dryRun) {
            return [
                'cleaningUpdated' => $bookingIds->count(),
                'roomsCleared' => $roomIds->count(),
                'sampleBookings' => $cleaningBookings->take(5)->toArray(),
                'sampleRooms' => $cleaningBookings->unique('room_id')->take(5)->toArray()
            ];
        }
        
        // Real execution
        $runId = Str::uuid();
        $cleaningUpdated = 0;
        $roomsCleared = 0;
        
        if ($bookingIds->count() > 0) {
            DB::beginTransaction();
            try {
                // Update bookings to Completed
                $cleaningUpdated = DB::table('booking')
                    ->whereIn('booking_id', $bookingIds)
                    ->update(['status' => 'Completed']);
                
                // Clear room cleaning flags
                $roomsCleared = DB::table('room')
                    ->whereIn('room_id', $roomIds)
                    ->update([
                        'cleaning_started_at' => null,
                        'cleaning_ends_at' => null,
                        'cleaning_by' => null,
                        'cleaning_note' => null,
                    ]);
                
                $this->logAudit('complete_cleaning_bookings', [
                    'run_id' => $runId,
                    'cleaning_updated' => $cleaningUpdated,
                    'rooms_cleared' => $roomsCleared,
                    'booking_ids' => $bookingIds->take(10)->toArray(),
                    'room_ids' => $roomIds->take(10)->toArray()
                ]);
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to complete cleaning bookings: ' . $e->getMessage());
                throw $e;
            }
        }
        
        return [
            'cleaningUpdated' => $cleaningUpdated,
            'roomsCleared' => $roomsCleared,
            'sampleBookings' => $cleaningBookings->take(5)->toArray(),
            'sampleRooms' => $cleaningBookings->unique('room_id')->take(5)->toArray(),
            'run_id' => $runId
        ];
    }
    
    /**
     * Run all cleanup operations
     */
    public function runAll(bool $dryRun = true): array
    {
        $runId = Str::uuid();
        
        $expireResult = $this->expirePendingBookings($dryRun);
        $checkoutResult = $this->completePastCheckouts($dryRun);
        $cleaningResult = $this->completeCleaningBookings($dryRun);
        
        if (!$dryRun) {
            $this->logAudit('run_all_cleanup', [
                'run_id' => $runId,
                'expire_result' => $expireResult,
                'checkout_result' => $checkoutResult,
                'cleaning_result' => $cleaningResult
            ]);
        }
        
        return [
            'run_id' => $runId,
            'expire_pending' => $expireResult,
            'complete_checkouts' => $checkoutResult,
            'complete_cleaning' => $cleaningResult,
            'summary' => [
                'total_bookings_affected' => $expireResult['deleted'] + $checkoutResult['updated'] + $cleaningResult['cleaningUpdated'],
                'total_rooms_affected' => $expireResult['deletedRooms'] + $cleaningResult['roomsCleared']
            ]
        ];
    }
    
    /**
     * Get MySQL advisory lock for cleanup operations
     */
    public function acquireLock(string $lockName = 'booking_cleanup', int $timeout = 10): bool
    {
        $result = DB::selectOne("SELECT GET_LOCK(?, ?) as lock_result", [$lockName, $timeout]);
        return $result->lock_result == 1;
    }
    
    /**
     * Release MySQL advisory lock
     */
    public function releaseLock(string $lockName = 'booking_cleanup'): bool
    {
        $result = DB::selectOne("SELECT RELEASE_LOCK(?) as release_result", [$lockName]);
        return $result->release_result == 1;
    }
    
    /**
     * Log audit entry for cleanup operations
     */
    private function logAudit(string $action, array $payload): void
    {
        try {
            // Try to insert into audit_logs table if it exists
            DB::table('audit_logs')->insert([
                'user_id' => auth()->id(),
                'action' => $action,
                'model' => 'BookingCleanup',
                'model_id' => null,
                'old_values' => null,
                'new_values' => json_encode($payload),
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            // Fallback to Laravel log if audit table doesn't exist
            Log::info("BookingCleanup audit: {$action}", $payload);
        }
    }
}
