<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get dashboard data
            $dataFeed = $this->getDashboardData();
            
            return view('pages.dashboard.dashboard', compact('dataFeed'));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            
            return view('pages.dashboard.dashboard', [
                'dataFeed' => $this->getEmptyData()
            ])->with('error', 'Có lỗi xảy ra khi tải dữ liệu dashboard');
        }
    }
    
    private function getDashboardData()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $last30Days = Carbon::now()->subDays(30);
        
        // Basic stats
        $totalRooms = DB::table('room')->count();
        $occupiedRooms = DB::table('room')->where('status', 'occupied')->count();
        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        
        // Revenue stats
        $totalRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $thisMonth)
            ->sum('p.amount_vnd');
            
        $todayRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereDate('b.created_at', $today)
            ->sum('p.amount_vnd');
        
        // Booking stats
        $totalBookings = DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->count();
            
        $todayBookings = DB::table('booking')
            ->whereDate('created_at', $today)
            ->count();
        
        // Chart data - Revenue by day (last 30 days)
        $revenueChartData = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->select(
                DB::raw('DATE(b.created_at) as date'),
                DB::raw('SUM(p.amount_vnd) as revenue')
            )
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $last30Days)
            ->groupBy(DB::raw('DATE(b.created_at)'))
            ->orderBy('date')
            ->get();
        
        // Chart data - Bookings by day (last 30 days)
        $bookingsChartData = DB::table('booking')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as bookings')
            )
            ->where('created_at', '>=', $last30Days)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Room type revenue
        $roomTypeRevenue = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->join('room as r', 'br.room_id', '=', 'r.room_id')
            ->join('room_type as rt', 'r.room_type_id', '=', 'rt.room_type_id')
            ->join('payment as p', 'b.booking_id', '=', 'p.booking_id')
            ->select(
                'rt.name',
                DB::raw('SUM(p.amount_vnd) as revenue'),
                DB::raw('COUNT(DISTINCT b.booking_id) as bookings')
            )
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $thisMonth)
            ->groupBy('rt.room_type_id', 'rt.name')
            ->get();
        
        return [
            'total_revenue' => $totalRevenue,
            'today_revenue' => $todayRevenue,
            'total_bookings' => $totalBookings,
            'today_bookings' => $todayBookings,
            'occupancy_rate' => $occupancyRate,
            'rooms_occupied' => $occupiedRooms,
            'rooms_available' => $availableRooms,
            'revenue_chart_data' => $revenueChartData,
            'bookings_chart_data' => $bookingsChartData,
            'room_type_revenue' => $roomTypeRevenue,
            'alerts' => $this->getAlerts()
        ];
    }
    
    private function getAlerts()
    {
        return [
            'rooms_need_cleaning' => DB::table('room')->where('status', 'cleaning')->count(),
            'overdue_payments' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'pending')
                ->where('b.check_in_date', '<', Carbon::now())
                ->count(),
            'arriving_tomorrow' => DB::table('booking')
                ->whereDate('check_in_date', Carbon::tomorrow())
                ->where('status', 'confirmed')
                ->count(),
            'maintenance_rooms' => DB::table('room')->where('status', 'maintenance')->count()
        ];
    }
    
    private function getEmptyData()
    {
        return [
            'total_revenue' => 0,
            'today_revenue' => 0,
            'total_bookings' => 0,
            'today_bookings' => 0,
            'occupancy_rate' => 0,
            'rooms_occupied' => 0,
            'rooms_available' => 0,
            'revenue_chart_data' => collect(),
            'bookings_chart_data' => collect(),
            'room_type_revenue' => collect(),
            'alerts' => []
        ];
    }
    
    public function getChartDataAjax(Request $request)
    {
        try {
            $period = $request->get('period', '30days');
            $startDate = $this->getStartDateByPeriod($period);
            
            $revenueData = DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->select(
                    DB::raw('DATE(b.created_at) as date'),
                    DB::raw('SUM(p.amount_vnd) as revenue')
                )
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', $startDate)
                ->groupBy(DB::raw('DATE(b.created_at)'))
                ->orderBy('date')
                ->get();
            
            $bookingsData = DB::table('booking')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as bookings')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải dữ liệu biểu đồ'
            ], 500);
        }
    }
    
    private function getStartDateByPeriod($period)
    {
        switch ($period) {
            case '7days':
                return Carbon::now()->subDays(7);
            case '30days':
                return Carbon::now()->subDays(30);
            case '90days':
                return Carbon::now()->subDays(90);
            default:
                return Carbon::now()->subDays(30);
        }
    }
}
