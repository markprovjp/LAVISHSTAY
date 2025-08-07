<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartReceptionController extends Controller
{
    /**
     * API doanh thu theo tháng
     * GET /api/reception/chart/revenue-by-month
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRevenueByMonth(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        
        try {
            $monthlyRevenue = DB::table('booking')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('MONTHNAME(created_at) as month_name'),
                    DB::raw('SUM(total_price_vnd) as total_revenue'),
                    DB::raw('COUNT(*) as booking_count')
                )
                ->whereYear('created_at', $year)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            // Format data for charts (fill missing months with 0)
            $chartData = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthData = $monthlyRevenue->firstWhere('month', $month);
                $chartData[] = [
                    'date' => (string)$month,
                    'month' => $month,
                    'month_name' => Carbon::create()->month($month)->format('M'),
                    'price' => $monthData ? (float)$monthData->total_revenue : 0,
                    'total_revenue' => $monthData ? (float)$monthData->total_revenue : 0,
                    'booking_count' => $monthData ? (int)$monthData->booking_count : 0
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $chartData,
                'summary' => [
                    'total_revenue' => $monthlyRevenue->sum('total_revenue'),
                    'total_bookings' => $monthlyRevenue->sum('booking_count'),
                    'year' => $year
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu doanh thu theo tháng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API phân loại doanh thu theo loại phòng
     * GET /api/reception/chart/revenue-by-category
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRevenueByCategory(Request $request)
    {
        $period = $request->get('period', 'month'); // month, quarter, year
        $date = $request->get('date', Carbon::now()->format('Y-m'));
        
        try {
            $query = DB::table('booking')
                ->leftJoin('room_types', 'booking.room_type_id', '=', 'room_types.room_type_id')
                ->select(
                    DB::raw('COALESCE(room_types.type_name, "Khác") as type'),
                    DB::raw('SUM(booking.total_price_vnd) as value'),
                    DB::raw('COUNT(*) as booking_count'),
                    DB::raw('AVG(booking.total_price_vnd) as avg_price')
                )
                ->whereIn('booking.status', ['Confirmed', 'Operational', 'CheckedOut']);

            // Apply date filter based on period
            if ($period === 'month') {
                $query->whereRaw('DATE_FORMAT(booking.created_at, "%Y-%m") = ?', [$date]);
            } elseif ($period === 'quarter') {
                $year = substr($date, 0, 4);
                $quarter = substr($date, 5);
                $query->whereRaw('YEAR(booking.created_at) = ? AND QUARTER(booking.created_at) = ?', [$year, $quarter]);
            } else { // year
                $query->whereYear('booking.created_at', $date);
            }

            $categoryRevenue = $query
                ->groupBy('room_types.type_name')
                ->orderBy('value', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categoryRevenue,
                'summary' => [
                    'total_categories' => $categoryRevenue->count(),
                    'total_revenue' => $categoryRevenue->sum('value'),
                    'period' => $period,
                    'date' => $date
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu phân loại doanh thu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API hiệu suất hoạt động phòng
     * GET /api/reception/chart/activity-rate
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivityRate(Request $request)
    {
        try {
            // Get total rooms
            $totalRooms = DB::table('room')->count();
            
            // Get occupied rooms (rooms with active bookings)
            $occupiedRooms = DB::table('room')
                ->leftJoin('booking', 'room.room_id', '=', 'booking.room_id')
                ->whereIn('booking.status', ['Operational', 'Confirmed'])
                ->where(function($query) {
                    $today = Carbon::today();
                    $query->where('booking.check_in_date', '<=', $today)
                          ->where('booking.check_out_date', '>=', $today);
                })
                ->distinct()
                ->count('room.room_id');

            // Get room status statistics
            $roomStats = DB::table('room')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            $activityRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

            // Weekly trend (last 7 days)
            $weeklyTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dailyOccupied = DB::table('room')
                    ->leftJoin('booking', 'room.room_id', '=', 'booking.room_id')
                    ->whereIn('booking.status', ['Operational', 'Confirmed'])
                    ->where('booking.check_in_date', '<=', $date->format('Y-m-d'))
                    ->where('booking.check_out_date', '>=', $date->format('Y-m-d'))
                    ->distinct()
                    ->count('room.room_id');
                
                $dailyRate = $totalRooms > 0 ? round(($dailyOccupied / $totalRooms) * 100, 1) : 0;
                $weeklyTrend[] = [
                    'x' => $i + 1,
                    'y' => $dailyRate,
                    'date' => $date->format('Y-m-d')
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'activity_rate' => $activityRate,
                    'total_rooms' => $totalRooms,
                    'occupied_rooms' => $occupiedRooms,
                    'available_rooms' => $totalRooms - $occupiedRooms,
                    'room_stats' => $roomStats,
                    'weekly_trend' => $weeklyTrend
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu hiệu suất hoạt động',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API lịch trình hôm nay
     * GET /api/reception/chart/today-schedule
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTodaySchedule(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        try {
            $today = Carbon::parse($date);
            
            // Check-ins today
            $checkIns = DB::table('booking')
                ->leftJoin('room_types', 'booking.room_type_id', '=', 'room_types.room_type_id')
                ->select(
                    'booking.booking_id',
                    'booking.booking_code',
                    'booking.guest_name',
                    'booking.guest_phone',
                    'booking.check_in_date',
                    'booking.status',
                    'room_types.type_name as room_type'
                )
                ->whereDate('booking.check_in_date', $today)
                ->whereIn('booking.status', ['Confirmed', 'Pending'])
                ->orderBy('booking.check_in_date')
                ->get();

            // Check-outs today
            $checkOuts = DB::table('booking')
                ->leftJoin('room_types', 'booking.room_type_id', '=', 'room_types.room_type_id')
                ->select(
                    'booking.booking_id',
                    'booking.booking_code',
                    'booking.guest_name',
                    'booking.guest_phone',
                    'booking.check_out_date',
                    'booking.status',
                    'room_types.type_name as room_type'
                )
                ->whereDate('booking.check_out_date', $today)
                ->whereIn('booking.status', ['Operational'])
                ->orderBy('booking.check_out_date')
                ->get();

            // Active bookings (currently staying)
            $activeBookings = DB::table('booking')
                ->leftJoin('room_types', 'booking.room_type_id', '=', 'room_types.room_type_id')
                ->select(
                    'booking.booking_id',
                    'booking.booking_code',
                    'booking.guest_name',
                    'booking.check_in_date',
                    'booking.check_out_date',
                    'room_types.type_name as room_type'
                )
                ->where('booking.status', 'Operational')
                ->where('booking.check_in_date', '<=', $today)
                ->where('booking.check_out_date', '>=', $today)
                ->orderBy('booking.check_out_date')
                ->get();

            // Format timeline data
            $timeline = [];
            
            foreach ($checkIns as $booking) {
                $timeline[] = [
                    'type' => 'checkin',
                    'time' => '14:00', // Default check-in time
                    'title' => 'Check-in',
                    'description' => "{$booking->guest_name} - {$booking->booking_code}",
                    'booking_id' => $booking->booking_id,
                    'room_type' => $booking->room_type,
                    'status' => 'pending'
                ];
            }

            foreach ($checkOuts as $booking) {
                $timeline[] = [
                    'type' => 'checkout',
                    'time' => '12:00', // Default check-out time
                    'title' => 'Check-out',
                    'description' => "{$booking->guest_name} - {$booking->booking_code}",
                    'booking_id' => $booking->booking_id,
                    'room_type' => $booking->room_type,
                    'status' => 'active'
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'timeline' => $timeline,
                    'check_ins' => $checkIns,
                    'check_outs' => $checkOuts,
                    'active_bookings' => $activeBookings,
                    'summary' => [
                        'total_checkins' => $checkIns->count(),
                        'total_checkouts' => $checkOuts->count(),
                        'active_bookings' => $activeBookings->count(),
                        'date' => $date
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy lịch trình hôm nay',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API thông báo quan trọng
     * GET /api/reception/chart/notifications
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        try {
            // Get notifications from audit_logs and system events
            $notifications = [];
            
            // Recent booking activities
            $recentActivities = DB::table('audit_logs')
                ->select(
                    'action',
                    'description',
                    'created_at',
                    'table_name',
                    'record_id'
                )
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($recentActivities as $activity) {
                $type = 'info';
                $priority = 'normal';
                
                if (strpos($activity->action, 'Transfer') !== false) {
                    $type = 'warning';
                    $priority = 'high';
                } elseif (strpos($activity->action, 'Reschedule') !== false) {
                    $type = 'info';
                    $priority = 'medium';
                }

                $notifications[] = [
                    'id' => uniqid(),
                    'type' => $type,
                    'priority' => $priority,
                    'title' => $activity->action,
                    'message' => $activity->description,
                    'time' => Carbon::parse($activity->created_at)->diffForHumans(),
                    'created_at' => $activity->created_at,
                    'read' => false
                ];
            }

            // Add system notifications for overdue bookings
            $overdueBookings = DB::table('booking')
                ->where('status', 'Confirmed')
                ->where('check_in_date', '<', Carbon::now()->subHours(2))
                ->count();

            if ($overdueBookings > 0) {
                array_unshift($notifications, [
                    'id' => 'overdue-checkins',
                    'type' => 'error',
                    'priority' => 'urgent',
                    'title' => 'Booking quá hạn check-in',
                    'message' => "{$overdueBookings} booking chưa check-in sau giờ quy định",
                    'time' => 'Vừa xong',
                    'created_at' => Carbon::now(),
                    'read' => false
                ]);
            }

            // Add maintenance notifications
            $maintenanceRooms = DB::table('room')
                ->where('status', 'maintenance')
                ->count();

            if ($maintenanceRooms > 0) {
                array_unshift($notifications, [
                    'id' => 'maintenance-rooms',
                    'type' => 'warning',
                    'priority' => 'medium',
                    'title' => 'Phòng đang bảo trì',
                    'message' => "{$maintenanceRooms} phòng đang trong quá trình bảo trì",
                    'time' => 'Hôm nay',
                    'created_at' => Carbon::now(),
                    'read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => array_slice($notifications, 0, $limit),
                'summary' => [
                    'total_notifications' => count($notifications),
                    'unread_count' => count(array_filter($notifications, fn($n) => !$n['read'])),
                    'urgent_count' => count(array_filter($notifications, fn($n) => $n['priority'] === 'urgent'))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông báo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API xếp hạng đặt phòng
     * GET /api/reception/chart/top-booked-services
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopBookedServices(Request $request)
    {
        $period = $request->get('period', 'month'); // week, month, quarter
        $limit = $request->get('limit', 5);
        
        try {
            $query = DB::table('booking')
                ->leftJoin('room_types', 'booking.room_type_id', '=', 'room_types.room_type_id')
                ->select(
                    DB::raw('COALESCE(room_types.type_name, "Không xác định") as keyword'),
                    DB::raw('COUNT(*) as users'),
                    DB::raw('SUM(booking.total_price_vnd) as total_revenue'),
                    DB::raw('AVG(booking.total_price_vnd) as avg_price'),
                    DB::raw('ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM booking WHERE status IN ("Confirmed", "Operational", "CheckedOut"))), 1) as percentage')
                )
                ->whereIn('booking.status', ['Confirmed', 'Operational', 'CheckedOut']);

            // Apply period filter
            if ($period === 'week') {
                $query->where('booking.created_at', '>=', Carbon::now()->subWeek());
            } elseif ($period === 'month') {
                $query->where('booking.created_at', '>=', Carbon::now()->subMonth());
            } elseif ($period === 'quarter') {
                $query->where('booking.created_at', '>=', Carbon::now()->subQuarter());
            }

            $topServices = $query
                ->groupBy('keyword')
                ->orderBy('users', 'desc')
                ->limit($limit)
                ->get();

            // Add ranking and format data
            $rankedServices = $topServices->map(function ($item, $index) {
                // Calculate trend (mock data for now, can be enhanced with historical comparison)
                $trendDirection = rand(0, 1) ? '▲' : '▼';
                $trendColor = $trendDirection === '▲' ? '#52c41a' : '#ff4d4f';
                
                return [
                    'rank' => $index + 1,
                    'keyword' => $item->keyword,
                    'users' => (int)$item->users,
                    'total_revenue' => (float)$item->total_revenue,
                    'avg_price' => (float)$item->avg_price,
                    'rate' => $item->percentage . '% ' . $trendDirection,
                    'trend_direction' => $trendDirection,
                    'trend_color' => $trendColor,
                    'percentage' => (float)$item->percentage
                ];
            });

            // Get search trends (mock data for mini charts)
            $searchTrends = [];
            for ($i = 1; $i <= 6; $i++) {
                $searchTrends[] = [
                    'x' => $i,
                    'y' => rand(12, 28)
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $rankedServices,
                'trends' => [
                    'user_search' => [
                        'value' => 17.1,
                        'trend' => '▲',
                        'color' => '#52c41a',
                        'data' => $searchTrends
                    ],
                    'avg_search' => [
                        'value' => 26.2,
                        'trend' => '▼',
                        'color' => '#ff4d4f',
                        'data' => array_map(fn($item) => ['x' => $item['x'], 'y' => $item['y'] + rand(8, 15)], $searchTrends)
                    ]
                ],
                'summary' => [
                    'period' => $period,
                    'total_services' => $rankedServices->count(),
                    'total_bookings' => $rankedServices->sum('users'),
                    'total_revenue' => $rankedServices->sum('total_revenue')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy xếp hạng dịch vụ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API tổng hợp thống kê dashboard
     * GET /api/reception/chart/dashboard-stats
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardStats(Request $request)
    {
        try {
            // Total bookings this month
            $totalBookings = DB::table('booking')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                ->count();

            // Total revenue this month
            $totalRevenue = DB::table('booking')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                ->sum('total_price_vnd');

            // Visits trend (mock data)
            $visitsTrend = [1200, 1400, 1100, 1600, 1300, 1700, 1500];

            // Payments trend (mock data)
            $paymentsTrend = [300, 400, 350, 500, 420, 480, 390];

            // Activity rate
            $activityResponse = $this->getActivityRate($request);
            $activityRate = $activityResponse->getData()->data->activity_rate ?? 78;

            return response()->json([
                'success' => true,
                'data' => [
                    'total_bookings' => $totalBookings,
                    'total_revenue' => $totalRevenue,
                    'visits_trend' => $visitsTrend,
                    'payments_trend' => $paymentsTrend,
                    'activity_rate' => $activityRate
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thống kê dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
