<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AutoBookingCleanup extends Command
{
    protected $signature = 'booking:auto-cleanup';
    protected $description = 'Tự động xoá booking pending quá 15 phút và chuyển trạng thái completed cho booking đã check-out';

    public function handle()
    {
        $now = Carbon::now();
        $pendingExpired = $now->copy()->subMinutes(15);


        // Lấy danh sách booking_id cần xoá
        $pendingIds = DB::table('booking')
            ->where('status', 'Pending')
            ->where('created_at', '<', $pendingExpired)
            ->pluck('booking_id');


        // Xoá booking_rooms liên quan
        $deletedRooms = 0;
        $deletedReps = 0;
        if ($pendingIds->count() > 0) {
            $deletedRooms = DB::table('booking_rooms')
                ->whereIn('booking_id', $pendingIds)
                ->delete();
            // Xoá representatives liên quan
            $deletedReps = DB::table('representatives')
                ->whereIn('booking_id', $pendingIds)
                ->delete();
        }

        // Xoá booking pending quá 15 phút
        $deleted = 0;
        if ($pendingIds->count() > 0) {
            $deleted = DB::table('booking')
                ->whereIn('booking_id', $pendingIds)
                ->delete();
        }

        $this->info("Đã xoá {$deleted} booking ở trạng thái pending quá 15 phút (kèm {$deletedRooms} booking_rooms, {$deletedReps} representatives liên quan).");

        // Chuyển booking đã check-out sang completed
        $updated = DB::table('booking')
            ->where('status', 'Operational')
            ->where('check_out_date', '<', $now->toDateString())
            ->update(['status' => 'Completed']);

        $this->info("Đã chuyển {$updated} booking sang trạng thái completed.");
    }
}
