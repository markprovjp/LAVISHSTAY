<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        $currentMonth = $request->get('current_month_only', true); // Only show current month by default
        
        try {
            $query = DB::table('booking')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('MONTHNAME(created_at) as month_name'),
                    DB::raw('SUM(total_price_vnd) as total_revenue'),
                    DB::raw('COUNT(*) as booking_count')
                )
                ->whereYear('created_at', $year)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut']);

            // If current_month_only is true, filter to current month only
            if ($currentMonth) {
                $query->whereMonth('created_at', Carbon::now()->month);
            }

            $monthlyRevenue = $query
                ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            // Format data for charts
            $chartData = [];
            
            if ($currentMonth) {
                // Only current month data
                $currentMonthData = $monthlyRevenue->first();
                if ($currentMonthData) {
                    $chartData[] = [
                        'date' => (string)$currentMonthData->month,
                        'month' => $currentMonthData->month,
                        'month_name' => 'Tháng ' . $currentMonthData->month,
                        'price' => (float)$currentMonthData->total_revenue,
                        'total_revenue' => (float)$currentMonthData->total_revenue,
                        'booking_count' => (int)$currentMonthData->booking_count
                    ];
                } else {
                    // No data for current month
                    $chartData[] = [
                        'date' => (string)Carbon::now()->month,
                        'month' => Carbon::now()->month,
                        'month_name' => 'Tháng ' . Carbon::now()->month,
                        'price' => 0,
                        'total_revenue' => 0,
                        'booking_count' => 0
                    ];
                }
            } else {
                // All months in year (fill missing months with 0)
                for ($month = 1; $month <= 12; $month++) {
                    $monthData = $monthlyRevenue->firstWhere('month', $month);
                    $chartData[] = [
                        'date' => (string)$month,
                        'month' => $month,
                        'month_name' => 'Tháng ' . $month,
                        'price' => $monthData ? (float)$monthData->total_revenue : 0,
                        'total_revenue' => $monthData ? (float)$monthData->total_revenue : 0,
                        'booking_count' => $monthData ? (int)$monthData->booking_count : 0
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $chartData,
                'summary' => [
                    'total_revenue' => $monthlyRevenue->sum('total_revenue'),
                    'total_bookings' => $monthlyRevenue->sum('booking_count'),
                    'year' => $year,
                    'current_month_only' => $currentMonth,
                    'period' => $currentMonth ? 'Tháng hiện tại' : 'Cả năm'
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
     * API doanh thu theo ngày trong tháng
     * GET /api/reception/chart/revenue-by-day
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDailyRevenueByMonth(Request $request)
    {
        $month = (int)$request->get('month', Carbon::now()->month);
        $year = (int)$request->get('year', Carbon::now()->year);

        try {
            $startOfMonth = Carbon::createFromDate($year, $month, 1);
            $daysInMonth = $startOfMonth->daysInMonth;

            $daily = DB::table('booking')
                ->select(
                    DB::raw('DAY(created_at) as day'),
                    DB::raw('SUM(total_price_vnd) as total_revenue'),
                    DB::raw('COUNT(*) as booking_count')
                )
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                ->groupBy(DB::raw('DAY(created_at)'))
                ->orderBy(DB::raw('DAY(created_at)'))
                ->get();

            $chartData = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $row = $daily->firstWhere('day', $d);
                $chartData[] = [
                    'day' => $d,
                    'date' => $startOfMonth->copy()->day($d)->format('Y-m-d'),
                    'total_revenue' => $row ? (float)$row->total_revenue : 0,
                    'booking_count' => $row ? (int)$row->booking_count : 0
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $chartData,
                'summary' => [
                    'month' => $month,
                    'year' => $year,
                    'days' => $daysInMonth
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy doanh thu theo ngày trong tháng',
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
                    DB::raw('COALESCE(room_types.name, "Khác") as type'),
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
                ->groupBy('room_types.name')
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
                    'room_types.name as room_type'
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
                    'room_types.name as room_type'
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
                    'room_types.name as room_type'
                )
                ->where('booking.status', 'Operational')
                ->where('booking.check_in_date', '<=', $today)
                ->where('booking.check_out_date', '>=', $today)
                ->orderBy('booking.check_out_date')
                ->get();

            // Format timeline data with better structure
            $timeline = [];
            
            foreach ($checkIns as $booking) {
                $timeline[] = [
                    'type' => 'checkin',
                    'time' => '14:00', // Default check-in time
                    'title' => 'Nhận phòng',
                    'description' => "{$booking->guest_name} - {$booking->booking_code}",
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->guest_name,
                    'guest_name' => $booking->guest_name,
                    'room_number' => $booking->room_type,
                    'room' => $booking->room_type,
                    'room_type' => $booking->room_type,
                    'status' => 'pending',
                    'action_type' => 'Check-in',
                    'priority' => 'high'
                ];
            }

            foreach ($checkOuts as $booking) {
                $timeline[] = [
                    'type' => 'checkout',
                    'time' => '12:00', // Default check-out time
                    'title' => 'Trả phòng',
                    'description' => "{$booking->guest_name} - {$booking->booking_code}",
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'customer_name' => $booking->guest_name,
                    'guest_name' => $booking->guest_name,
                    'room_number' => $booking->room_type,
                    'room' => $booking->room_type,
                    'room_type' => $booking->room_type,
                    'status' => 'active',
                    'action_type' => 'Check-out',
                    'priority' => 'medium'
                ];
            }

            // Sort timeline by time and priority
            usort($timeline, function($a, $b) {
                $timeA = strtotime($a['time']);
                $timeB = strtotime($b['time']);
                if ($timeA == $timeB) {
                    $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
                    return $priorityOrder[$a['priority']] - $priorityOrder[$b['priority']];
                }
                return $timeA - $timeB;
            });

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
                    'model',
                    'model_id'
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
            // Log full exception for debugging (message + stack trace)
            Log::error('ChartReceptionController@getNotifications error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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
                    DB::raw('COALESCE(room_types.name, "Không xác định") as keyword'),
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
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $today = Carbon::today();

            // Total bookings this month
            $totalBookings = DB::table('booking')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                ->count();

            // Total revenue this month
            $totalRevenue = DB::table('booking')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                ->sum('total_price_vnd');

            // Daily revenue today
            $dailyRevenue = DB::table('booking')
                ->whereDate('created_at', $today)
                ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                ->sum('total_price_vnd');

            // Monthly visits (mock data - can be replaced with actual analytics)
            $totalVisits = DB::table('booking')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count() * 3; // Estimate visits as 3x bookings

            // Daily visits (mock data)
            $dailyVisits = DB::table('booking')
                ->whereDate('created_at', $today)
                ->count() * 3;

            // Activity rate (occupancy rate)
            $activityResponse = $this->getActivityRate($request);
            $activityData = $activityResponse->getData();
            $activityRate = $activityData->success ? $activityData->data->activity_rate : 0;

            // Visits trend (last 7 days booking count * 3)
            $visitsTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dailyBookings = DB::table('booking')
                    ->whereDate('created_at', $date)
                    ->count();
                $visitsTrend[] = $dailyBookings * 3; // Estimate
            }

            // Payments trend (last 7 days successful payments)
            $paymentsTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dailyPayments = DB::table('booking')
                    ->whereDate('created_at', $date)
                    ->whereIn('status', ['Confirmed', 'Operational', 'CheckedOut'])
                    ->count();
                $paymentsTrend[] = $dailyPayments;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_bookings' => $totalBookings,
                    'total_revenue' => $totalRevenue,
                    'total_visits' => $totalVisits,
                    'performance_rate' => $activityRate,
                    'daily_revenue' => $dailyRevenue,
                    'daily_visits' => $dailyVisits,
                    'visits_trend' => $visitsTrend,
                    'payments_trend' => $paymentsTrend
                ],
                'summary' => [
                    'period' => 'Tháng hiện tại',
                    'month' => $currentMonth,
                    'year' => $currentYear,
                    'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
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

    /**
     * API trạng thái phòng
     * GET /api/reception/chart/room-status
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomStatus(Request $request)
    {
        try {
            $roomStatus = DB::table('room')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();

            // Format data with Vietnamese status names
            $statusMapping = [
                'available' => 'Sẵn sàng',
                'occupied' => 'Đang sử dụng',
                'maintenance' => 'Bảo trì',
                'cleaning' => 'Đang dọn',
                'out_of_order' => 'Hỏng'
            ];

            $formattedData = $roomStatus->map(function ($item) use ($statusMapping) {
                return [
                    'status' => $statusMapping[$item->status] ?? ucfirst($item->status),
                    'count' => (int)$item->count
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedData->values()->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy trạng thái phòng',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
