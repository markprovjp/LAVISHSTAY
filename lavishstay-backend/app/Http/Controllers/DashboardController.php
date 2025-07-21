<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tổng quan kinh doanh
        $businessSummary = $this->getBusinessSummary();
        
        // 2. Dữ liệu cho biểu đồ
        $chartData = $this->getChartData();
        // dd($chartData);
        // 3. Bảng dữ liệu chi tiết
        $detailTables = $this->getDetailTables();
        
        // 4. Thống kê hoạt động lễ tân
        $frontDeskStats = $this->getFrontDeskStats();
        
        // 5. Thống kê theo loại phòng
        $roomTypeStats = $this->getRoomTypeStats();
        // dd($roomTypeStats);
        // 6. Thống kê theo nguồn đặt
        $bookingChannelStats = $this->getBookingChannelStats();
        
        // 7. Thống kê khách hàng
        $customerStats = $this->getCustomerStats();
        
        // 8. Cảnh báo
        $alerts = $this->getAlerts();
        
        // 9. Chỉ số tài chính
        $financialMetrics = $this->getFinancialMetrics();

        return view('pages.dashboard.dashboard', compact(
            'businessSummary',
            'chartData', 
            'detailTables',
            'frontDeskStats',
            'roomTypeStats',
            'bookingChannelStats',
            'customerStats',
            'alerts',
            'financialMetrics'
        ));
    }

    private function getBusinessSummary()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            // Thống kê phòng
            'rooms' => [
                'available' => DB::table('room')->where('status', 'available')->count(),
                'occupied' => DB::table('room')->where('status', 'occupied')->count(),
                'maintenance' => DB::table('room')->where('status', 'maintenance')->count(),
                'total' => DB::table('room')->count()
            ],
            
            // Đặt phòng
            'bookings' => [
                'today' => DB::table('booking')->whereDate('created_at', $today)->count(),
                'this_week' => DB::table('booking')->where('created_at', '>=', $thisWeek)->count(),
                'this_month' => DB::table('booking')->where('created_at', '>=', $thisMonth)->count(),
                'total' => DB::table('booking')->count()
            ],
            
            // Doanh thu
            'revenue' => [
                'today' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->whereDate('b.created_at', $today)
                    ->sum('p.amount_vnd'),
                'this_week' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', $thisWeek)
                    ->sum('p.amount_vnd'),
                'this_month' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', $thisMonth)
                    ->sum('p.amount_vnd')
            ],
            
            // Tỉ lệ hủy phòng
            'cancellation_rate' => $this->getCancellationRate(),
            
            // Tỷ lệ lấp đầy
            'occupancy_rate' => $this->getOccupancyRate(),
            
            // Giá phòng trung bình (ADR)
            'adr' => $this->getAverageDailyRate(),
            
            // Doanh thu trung bình/phòng (RevPAR)
            'revpar' => $this->getRevPAR()
        ];
    }

    private function getChartData()
    {
        $last30Days = Carbon::now()->subDays(30);
        
        // Doanh thu 30 ngày qua
        $revenueData = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $last30Days)
            ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Lượt đặt phòng 30 ngày qua
        $bookingData = DB::table('booking')
            ->where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as bookings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tỷ lệ lấp đầy 30 ngày qua
        $occupancyData = $this->getOccupancyChartData($last30Days);

        return [
            'revenue' => $revenueData->toArray(),
            'bookings' => $bookingData->toArray(),
            'occupancy' => $occupancyData->toArray()
        ];
    }

    private function getDetailTables()
    {
        return [
            // Top 10 đặt phòng mới nhất
            'recent_bookings' => DB::table('booking')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
                
            // Khách sắp đến hôm nay
            'arriving_today' => DB::table('booking')
                ->whereDate('check_in_date', Carbon::today())
                ->where('status', 'confirmed')
                ->get(),
                
            // Khách sắp check-out hôm nay
            'departing_today' => DB::table('booking')
                ->whereDate('check_out_date', Carbon::today())
                ->where('status', 'confirmed')
                ->get(),
                
            // Lịch sử thanh toán gần đây
            'recent_payments' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->select('p.*', 'b.booking_code', 'b.guest_name')
                ->orderBy('p.created_at', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    private function getFrontDeskStats()
    {
        $today = Carbon::today();
        
        return [
            'checkins_today' => DB::table('booking')
                ->whereDate('check_in_date', $today)
                ->where('status', 'confirmed')
                ->count(),
                
            'checkouts_today' => DB::table('booking')
                ->whereDate('check_out_date', $today)
                ->where('status', 'confirmed')
                ->count(),
                
            'pending_bookings' => DB::table('booking')
                ->where('status', 'pending')
                ->count(),
                
            'rooms_need_cleaning' => DB::table('room')
                ->where('status', 'cleaning')
                ->count(),
                
            'rooms_maintenance' => DB::table('room')
                ->where('status', 'maintenance')
                ->count()
        ];
    }

    private function getRoomTypeStats()
    {
        return [
            'booking_by_type' => DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->join('room as r', 'br.room_id', '=', 'r.room_id')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->selectRaw('rt.name, COUNT(*) as bookings, SUM(b.total_price_vnd) as revenue')
                ->groupBy('rt.room_type_id', 'rt.name')
                ->get(),
                
            'occupancy_by_type' => $this->getOccupancyByRoomType()
        ];
    }

    private function getBookingChannelStats()
    {
        // Giả sử có trường booking_source trong bảng booking
        return DB::table('booking')
            ->selectRaw('COALESCE(booking_source, "Direct") as channel, COUNT(*) as bookings, SUM(total_price_vnd) as revenue')
            ->groupBy('booking_source')
            ->get();
    }

    private function getCustomerStats()
    {
        return [
            'new_customers' => DB::table('booking')
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->distinct('guest_email')
                ->count(),
                
            'returning_customers' => $this->getReturningCustomers(),
            
            'top_customers' => DB::table('booking')
                ->selectRaw('guest_name, guest_email, COUNT(*) as booking_count, SUM(total_price_vnd) as total_spent')
                ->groupBy('guest_email', 'guest_name')
                ->orderBy('booking_count', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    private function getAlerts()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        
        return [
            'rooms_need_cleaning' => DB::table('room')
                ->where('status', 'cleaning')
                ->count(),
                
            'overdue_payments' => DB::table('payment')
                ->where('status', 'pending')
                ->where('created_at', '<', Carbon::now()->subDays(3))
                ->count(),
                
            'arriving_tomorrow' => DB::table('booking')
                ->whereDate('check_in_date', $tomorrow)
                ->where('status', 'confirmed')
                ->count(),
                
            'maintenance_rooms' => DB::table('room')
                ->where('status', 'maintenance')
                ->count()
        ];
    }

    private function getFinancialMetrics()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total_collected' => DB::table('payment')
                ->where('status', 'completed')
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount_vnd'),
                
            'pending_payments' => DB::table('payment')
                ->where('status', 'pending')
                ->sum('amount_vnd'),
                
            'total_bookings_value' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price_vnd')
        ];
    }

    // Helper methods
    private function getCancellationRate()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $totalBookings = DB::table('booking')->where('created_at', '>=', $thisMonth)->count();
        $cancelledBookings = DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->where('status', 'cancelled')
            ->count();
            
        return $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0;
    }

    private function getOccupancyRate()
    {
        $totalRooms = DB::table('room')->count();
        $occupiedRooms = DB::table('room')->where('status', 'occupied')->count();
        
        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
    }

    private function getAverageDailyRate()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $totalRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $thisMonth)
            ->sum('p.amount_vnd');
            
        $totalNights = DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('SUM(DATEDIFF(check_out_date, check_in_date)) as total_nights')
            ->value('total_nights');
            
        return $totalNights > 0 ? round($totalRevenue / $totalNights, 0) : 0;
    }

    private function getRevPAR()
    {
        $occupancyRate = $this->getOccupancyRate();
        $adr = $this->getAverageDailyRate();
        
        return round(($occupancyRate / 100) * $adr, 0);
    }

    private function getOccupancyChartData($startDate)
    {
        // Simplified occupancy calculation for chart
        $totalRooms = DB::table('room')->count();
        
        return DB::table('booking')
            ->where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as occupied_rooms')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) use ($totalRooms) {
                $item->occupancy_rate = $totalRooms > 0 ? round(($item->occupied_rooms / $totalRooms) * 100, 2) : 0;
                return $item;
            });
    }
        private function getOccupancyByRoomType()
    {
        return DB::table('room_types as rt')
            ->leftJoin('room as r', 'rt.room_type_id', '=', 'r.room_type_id')
            ->selectRaw('
                rt.name,
                COUNT(r.room_id) as total_rooms,
                SUM(CASE WHEN r.status = "occupied" THEN 1 ELSE 0 END) as occupied_rooms,
                ROUND((SUM(CASE WHEN r.status = "occupied" THEN 1 ELSE 0 END) / COUNT(r.room_id)) * 100, 2) as occupancy_rate
            ')
            ->groupBy('rt.room_type_id', 'rt.name')
            ->get();
    }

    private function getReturningCustomers()
    {
        return DB::table('booking')
            ->selectRaw('guest_email')
            ->groupBy('guest_email')
            ->havingRaw('COUNT(*) > 1')
            ->count();
    }

    // API endpoints for AJAX updates
    public function getRealtimeStats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'rooms_available' => DB::table('room')->where('status', 'available')->count(),
                'rooms_occupied' => DB::table('room')->where('status', 'occupied')->count(),
                'pending_bookings' => DB::table('booking')->where('status', 'pending')->count(),
                'today_revenue' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->whereDate('b.created_at', Carbon::today())
                    ->sum('p.amount_vnd')
            ]
        ]);
    }

    public function getChartDataAjax(Request $request)
    {
        $period = $request->get('period', '30'); // 7, 30, 90 days
        $startDate = Carbon::now()->subDays($period);
        
        $revenueData = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $startDate)
            ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $bookingData = DB::table('booking')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as bookings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'revenue' => $revenueData,
                'bookings' => $bookingData
            ]
        ]);
    }
}
