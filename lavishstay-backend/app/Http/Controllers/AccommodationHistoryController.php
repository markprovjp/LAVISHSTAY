<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccommodationHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $roomId = $request->get('room_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $quickFilter = $request->get('quick_filter');
        $guestName = $request->get('guest_name');
        $bookingCode = $request->get('booking_code');

        // Handle quick filters
        if ($quickFilter === 'this_month') {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($quickFilter === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        } elseif (!$startDate || !$endDate) {
            // Default to current month if no dates provided
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        // Build the main query - sắp xếp theo ngày check-in và phòng
        $query = DB::table('booking_rooms as br')
            ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
            ->join('room as r', 'br.room_id', '=', 'r.room_id')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('floors as f', 'r.floor_id', '=', 'f.floor_id')
            ->select([
                'br.*',
                'b.booking_code',
                'b.guest_name',
                'b.guest_phone',
                'b.guest_email',
                'b.check_in_date',
                'b.check_out_date',
                'b.status as booking_status',
                'b.guest_count',
                'b.total_price_vnd',
                'b.created_at as booking_created_at',
                'r.name as room_name',
                'r.room_id',
                'f.floor_number',
                'f.floor_name',
                'rt.name as room_type_name',
                'rt.room_type_id'
            ])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('b.check_in_date', [$startDate, $endDate])
                  ->orWhereBetween('b.check_out_date', [$startDate, $endDate])
                  ->orWhere(function($subQ) use ($startDate, $endDate) {
                      $subQ->where('b.check_in_date', '<=', $startDate)
                           ->where('b.check_out_date', '>=', $endDate);
                  });
            });

        // Apply filters
        if ($roomId) {
            $query->where('br.room_id', $roomId);
        }

        if ($guestName) {
            $query->where('b.guest_name', 'LIKE', '%' . $guestName . '%');
        }

        if ($bookingCode) {
            $query->where('b.booking_code', 'LIKE', '%' . $bookingCode . '%');
        }

        // Only show confirmed, completed bookings
        $query->whereIn('b.status', ['confirmed', 'completed']);

        // Sắp xếp theo ngày check-in, sau đó theo tầng và tên phòng
        $query->orderBy('b.check_in_date', 'desc')
              ->orderBy('f.floor_number', 'asc')
              ->orderBy('r.name', 'asc');

        // Paginate results
        $accommodationHistory = $query->paginate(20)->appends($request->query());

        // Get rooms organized properly for dropdown
        $roomsForFilter = $this->getRoomsForFilter();

        // dd($roomsForFilter);
        // Calculate statistics
        $statistics = $this->calculateStatistics($startDate, $endDate, $roomId);

        return view('admin.bookings.accommodation-history.index', compact(
            'accommodationHistory',
            'roomsForFilter',
            'statistics',
            'roomId',
            'startDate',
            'endDate',
            'quickFilter',
            'guestName',
            'bookingCode'
        ));
    }

    private function getRoomsForFilter()
    {
        // Lấy tất cả phòng có thể sử dụng được (không phải out_of_service)
        $rooms = DB::table('room as r')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('floors as f', 'r.floor_id', '=', 'f.floor_id')
            ->select(
                'r.room_id',
                'r.name as room_name',
                'r.room_type_id',
                'rt.name as room_type_name',
                'f.floor_id',
                'f.floor_number',
                'f.floor_name',
                'r.status as room_status'
            )
            ->whereNotIn('r.status', ['out_of_service']) // Loại bỏ phòng ngừng phục vụ
            ->orderBy('rt.name', 'asc')
            ->orderBy('f.floor_number', 'asc')
            ->orderBy('r.name', 'asc')
            ->get();

        // Debug: Log số lượng phòng tìm được
        \Log::info('Rooms found for filter: ' . $rooms->count());
        
        // Nhóm theo loại phòng, sau đó theo tầng
        $groupedRooms = [];
        
        foreach ($rooms as $room) {
            $roomTypeName = $room->room_type_name;
            
            // Xử lý tên tầng
            if ($room->floor_number) {
                $floorDisplay = "Tầng {$room->floor_number}";
                if ($room->floor_name) {
                    $floorDisplay .= " - {$room->floor_name}";
                }
            } else {
                $floorDisplay = "Chưa xác định tầng";
            }
            
            if (!isset($groupedRooms[$roomTypeName])) {
                $groupedRooms[$roomTypeName] = [];
            }
            
            if (!isset($groupedRooms[$roomTypeName][$floorDisplay])) {
                $groupedRooms[$roomTypeName][$floorDisplay] = [];
            }
            
            $groupedRooms[$roomTypeName][$floorDisplay][] = $room;
        }

        // Debug: Log cấu trúc dữ liệu
        \Log::info('Grouped rooms structure: ', $groupedRooms);

        return $groupedRooms;
    }

    private function calculateStatistics($startDate, $endDate, $roomId = null)
    {
        $baseQuery = DB::table('booking_rooms as br')
            ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('b.check_in_date', [$startDate, $endDate])
                  ->orWhereBetween('b.check_out_date', [$startDate, $endDate])
                  ->orWhere(function($subQ) use ($startDate, $endDate) {
                      $subQ->where('b.check_in_date', '<=', $startDate)
                           ->where('b.check_out_date', '>=', $endDate);
                  });
            })
            ->whereIn('b.status', ['confirmed', 'completed']);

        if ($roomId) {
            $baseQuery->where('br.room_id', $roomId);
        }

        // Tính toán thống kê chính xác hơn
        $totalBookings = (clone $baseQuery)->distinct('b.booking_id')->count('b.booking_id');
        $totalRooms = (clone $baseQuery)->distinct('br.room_id')->count('br.room_id');
        $totalGuests = (clone $baseQuery)->sum('b.guest_count');
        
        // Tính tổng doanh thu không trùng lặp
        $totalRevenue = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('b.check_in_date', [$startDate, $endDate])
                  ->orWhereBetween('b.check_out_date', [$startDate, $endDate])
                  ->orWhere(function($subQ) use ($startDate, $endDate) {
                      $subQ->where('b.check_in_date', '<=', $startDate)
                           ->where('b.check_out_date', '>=', $endDate);
                  });
            })
            ->whereIn('b.status', ['confirmed', 'completed'])
            ->when($roomId, function($query) use ($roomId) {
                return $query->where('br.room_id', $roomId);
            })
            ->distinct()
            ->sum('b.total_price_vnd');

        // Calculate occupancy rate if specific room is selected
        $occupancyRate = 0;
        if ($roomId) {
            $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
            $occupiedDays = (clone $baseQuery)
                ->selectRaw('SUM(DATEDIFF(LEAST(b.check_out_date, ?), GREATEST(b.check_in_date, ?))) as occupied_days', [$endDate, $startDate])
                ->first()->occupied_days ?? 0;
            
            $occupancyRate = $totalDays > 0 ? round(($occupiedDays / $totalDays) * 100, 1) : 0;
        }

        return [
            'total_bookings' => $totalBookings,
            'total_rooms' => $totalRooms,
            'total_guests' => $totalGuests,
            'total_revenue' => $totalRevenue,
            'occupancy_rate' => $occupancyRate,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
                'days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1
            ]
        ];
    }

    public function export(Request $request)
    {
        $roomId = $request->get('room_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $guestName = $request->get('guest_name');
        $bookingCode = $request->get('booking_code');

        if (!$startDate || !$endDate) {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $query = DB::table('booking_rooms as br')
            ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
            ->join('room as r', 'br.room_id', '=', 'r.room_id')
            ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->leftJoin('floors as f', 'r.floor_id', '=', 'f.floor_id')
            ->select([
                'b.booking_code',
                'b.guest_name',
                'b.guest_phone',
                'b.guest_email',
                'r.name as room_name',
                'f.floor_number',
                'f.floor_name',
                'rt.name as room_type_name',
                'b.check_in_date',
                'b.check_out_date',
                'b.guest_count',
                'b.total_price_vnd',
                'b.status as booking_status',
                'b.created_at as booking_created_at'
            ])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('b.check_in_date', [$startDate, $endDate])
                  ->orWhereBetween('b.check_out_date', [$startDate, $endDate])
                  ->orWhere(function($subQ) use ($startDate, $endDate) {
                      $subQ->where('b.check_in_date', '<=', $startDate)
                           ->where('b.check_out_date', '>=', $endDate);
                  });
            })
            ->whereIn('b.status', ['confirmed', 'completed']);

        // Apply filters
        if ($roomId) $query->where('br.room_id', $roomId);
        if ($guestName) $query->where('b.guest_name', 'LIKE', '%' . $guestName . '%');
        if ($bookingCode) $query->where('b.booking_code', 'LIKE', '%' . $bookingCode . '%');

        $data = $query->orderBy('b.check_in_date', 'desc')
                     ->orderBy('f.floor_number', 'asc')
                     ->orderBy('r.name', 'asc')
                     ->get();

        // Create CSV export
        $filename = 'lich-su-luu-tru-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Mã đặt phòng',
                'Tên khách',
                'Điện thoại',
                'Email',
                'Phòng',
                'Tầng',
                'Tên tầng',
                'Loại phòng',
                'Check-in',
                'Check-out',
                'Số khách',
                'Tổng tiền (VNĐ)',
                'Trạng thái',
                'Ngày đặt'
            ]);

            // Data rows
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->booking_code,
                    $row->guest_name,
                    $row->guest_phone,
                    $row->guest_email,
                    $row->room_name,
                    $row->floor_number ?? 'N/A',
                    $row->floor_name ?? 'N/A',
                    $row->room_type_name,
                    $row->check_in_date,
                    $row->check_out_date,
                    $row->guest_count,
                    $row->total_price_vnd,
                    $row->booking_status,
                    Carbon::parse($row->booking_created_at)->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}