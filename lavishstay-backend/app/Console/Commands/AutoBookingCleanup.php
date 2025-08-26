<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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

        // Chuyển booking ở trạng thái Cleaning sang Completed nếu thời gian dọn dẹp đã kết thúc
        // Logic: tìm booking_id liên quan đến phòng có cleaning_ends_at <= now và booking.status = 'Cleaning'
        $cleaningBookings = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->join('room as r', 'br.room_id', '=', 'r.room_id')
            ->where('b.status', 'Cleaning')
            ->whereNotNull('r.cleaning_ends_at')
            ->where('r.cleaning_ends_at', '<=', $now)
            ->distinct()
            ->pluck('b.booking_id');

        $cleaningUpdated = 0;
        $roomsCleared = 0;
        if ($cleaningBookings->count() > 0) {
            DB::beginTransaction();
            try {
                // Update bookings to Completed
                $cleaningUpdated = DB::table('booking')
                    ->whereIn('booking_id', $cleaningBookings)
                    ->update(['status' => 'Completed']);

                // Clear room cleaning flags for related rooms
                $roomsCleared = DB::table('room')
                    ->whereIn('room_id', function ($query) use ($cleaningBookings) {
                        $query->select('br.room_id')
                            ->from('booking_rooms as br')
                            ->whereIn('br.booking_id', $cleaningBookings);
                    })
                    ->update([
                        'cleaning_started_at' => null,
                        'cleaning_ends_at' => null,
                        'cleaning_by' => null,
                        'cleaning_note' => null,
                    ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to auto-complete cleaning bookings: ' . $e->getMessage());
            }
        }

        $this->info("Đã chuyển {$cleaningUpdated} booking từ Cleaning sang Completed và cập nhật {$roomsCleared} phòng (clear cleaning flags).");
    }
}
