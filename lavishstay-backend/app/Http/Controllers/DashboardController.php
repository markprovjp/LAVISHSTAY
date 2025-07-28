<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Giữ nguyên hàm index từ mã nguồn trước đó
        $businessSummary = $this->getBusinessSummary();
        $chartData = $this->getChartData();
        $detailTables = $this->getDetailTables();
        $frontDeskStats = $this->getFrontDeskStats();
        $roomTypeStats = $this->getRoomTypeStats();
        $bookingChannelStats = $this->getBookingChannelStats();
        $customerStats = $this->getCustomerStats();
        $alerts = $this->getAlerts();
        $financialMetrics = $this->getFinancialMetrics();

        return view('admin.dashboard.dashboard', compact(
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

    public function analytics($id = null)
    {
        // 1. Phân tích doanh thu
        $revenueAnalysis = $this->getRevenueAnalysis();

        // 2. Phân tích booking
        $bookingAnalysis = $this->getBookingAnalysis();

        // 3. Phân tích công suất phòng
        $occupancyAnalysis = $this->getOccupancyAnalysis();

        // 4. Phân tích hành vi khách hàng
        $customerBehavior = $this->getCustomerBehavior();

        // 5. Phân tích thời điểm đặc biệt
        $specialPeriodAnalysis = $this->getSpecialPeriodAnalysis();

        // 6. Phân tích nguyên nhân hủy phòng
        $cancellationAnalysis = $this->getCancellationAnalysis();

        // 7. Phân tích theo khách hàng
        $customerAnalysis = $this->getCustomerAnalysis();

        // 8. Dữ liệu cho biểu đồ
        $analyticsData = $this->getAnalyticsChartData();

        // 9. Chỉ số tài chính (tái sử dụng từ dashboard)
        $financialMetrics = $this->getFinancialMetrics();

        return view('admin.dashboard.analytics', compact(
            'revenueAnalysis',
            'bookingAnalysis',
            'occupancyAnalysis',
            'customerBehavior',
            'specialPeriodAnalysis',
            'cancellationAnalysis',
            'customerAnalysis',
            'analyticsData',
            'financialMetrics'
        ));
    }

    private function getRevenueAnalysis()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $today = Carbon::today();

        return [
            // Doanh thu theo thời gian
            'by_time' => [
                'daily' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', Carbon::now()->subDays(30))
                    ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
                'weekly' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', Carbon::now()->subWeeks(12))
                    ->selectRaw('YEARWEEK(b.created_at) as week, SUM(p.amount_vnd) as revenue')
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get(),
                'monthly' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', $thisYear)
                    ->selectRaw('DATE_FORMAT(b.created_at, "%Y-%m") as month, SUM(p.amount_vnd) as revenue')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get(),
                'yearly' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->selectRaw('YEAR(b.created_at) as year, SUM(p.amount_vnd) as revenue')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get()
            ],
            // Doanh thu theo loại phòng
            'by_room_type' => DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->join('room as r', 'br.room_id', '=', 'r.room_id')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->leftJoin('translation as t', function ($join) {
                    $join->on('rt.room_type_id', '=', 't.record_id')
                        ->where('t.table_name', '=', 'room_types')
                        ->where('t.column_name', '=', 'name')
                        ->where('t.language_code', '=', 'vi'); // Adjust language code as needed
                })
                ->where('b.created_at', '>=', $thisMonth)
                ->selectRaw('COALESCE(t.value, "Unknown") as name, SUM(b.total_price_vnd) as revenue')
                ->groupBy('rt.room_type_id', 't.value')
                ->get(),
            // Doanh thu theo nguồn đặt
            'by_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('COALESCE(booking_source, "Direct") as source, SUM(total_price_vnd) as revenue')
                ->groupBy('booking_source')
                ->get(),
            // Doanh thu theo chính sách (using deposit_policy_id from room_option)
            'by_policy' => DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->join('room_option as ro', 'br.option_id', '=', 'ro.option_id')
                ->join('deposit_policies as dp', 'ro.deposit_policy_id', '=', 'dp.policy_id')
                ->join('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->where('b.created_at', '>=', $thisMonth)
                ->where('p.status', 'completed')
                ->selectRaw('
                    dp.name as policy_name,
                    SUM(p.amount_vnd) as revenue,
                    ROUND((SUM(CASE WHEN b.status = "Completed" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as completion_rate
                ')
                ->groupBy('dp.policy_id', 'dp.name')
                ->get(),
            // Tỷ lệ phụ thu trẻ em
            'child_surcharge_ratio' => $this->getChildSurchargeRatio(),
            'child_surcharge_total' => DB::table('booking as b')
                ->join('children_surcharges as cs', 'b.booking_id', '=', 'cs.booking_id')
                ->where('b.created_at', '>=', $thisMonth)
                ->sum('cs.surcharge_amount_vnd')
        ];
    }

    private function getBookingAnalysis()
{
    $thisMonth = Carbon::now()->startOfMonth();
    $thisYear = Carbon::now()->startOfYear();

    return [
        'status_ratio' => [
            'success' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('status', 'Completed')
                ->count(),
            'cancelled' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('status', 'Cancelled')
                ->count(),
            'total' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->count()
        ],
        'by_source' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->selectRaw('"Direct" as source, COUNT(*) as bookings')
            ->groupByRaw('"Direct"')
            ->get(),
        'avg_daily' => DB::table('booking')
            ->selectRaw('COALESCE(AVG(daily_bookings), 0) as avg')
            ->fromSub(function ($query) {
                $query->from('booking')
                    ->selectRaw('COUNT(*) as daily_bookings')
                    ->where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->groupByRaw('DATE(created_at)');
            }, 'sub')
            ->value('avg'),
        'avg_seasonal' => DB::table('booking')
            ->selectRaw('COALESCE(AVG(monthly_bookings), 0) as avg')
            ->fromSub(function ($query) {
                $query->from('booking')
                    ->selectRaw('COUNT(*) as monthly_bookings')
                    ->where('created_at', '>=', Carbon::now()->startOfYear())
                    ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")');
            }, 'sub')
            ->value('avg'),
        'by_customer_type' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->selectRaw('
                CASE 
                    WHEN guest_count > 5 THEN "Group"
                    ELSE "Individual"
                END as customer_type,
                COUNT(*) as bookings
            ')
            ->groupBy('customer_type')
            ->get()
    ];
}

    private function getOccupancyAnalysis()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $totalRooms = DB::table('room')->count();

        return [
            // Công suất theo loại phòng
            'by_room_type' => DB::table('room_types as rt')
                ->leftJoin('room as r', 'rt.room_type_id', '=', 'r.room_type_id')
                ->selectRaw('
                    rt.name,
                    COUNT(r.room_id) as total_rooms,
                    SUM(CASE WHEN r.status = "occupied" THEN 1 ELSE 0 END) as occupied_rooms,
                    ROUND((SUM(CASE WHEN r.status = "occupied" THEN 1 ELSE 0 END) / COUNT(r.room_id)) * 100, 2) as occupancy_rate
                ')
                ->groupBy('rt.room_type_id', 'rt.name')
                ->get(),
            
            // Công suất hàng ngày
            'daily' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('status', '!=', 'cancelled')
                ->selectRaw('
                    DATE(check_in_date) as date,
                    COUNT(*) as occupied_rooms,
                    ROUND((COUNT(*) / ?) * 100, 2) as occupancy_rate
                ', [$totalRooms])
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            // Ngày công suất cao nhất/thấp nhất
            'highest_day' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('status', '!=', 'cancelled')
                ->selectRaw('
                    DATE(check_in_date) as date,
                    ROUND((COUNT(*) / ?) * 100, 2) as rate
                ', [$totalRooms])
                ->groupBy('date')
                ->orderBy('rate', 'desc')
                ->first(),
            'lowest_day' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('status', '!=', 'cancelled')
                ->selectRaw('
                    DATE(check_in_date) as date,
                    ROUND((COUNT(*) / ?) * 100, 2) as rate
                ', [$totalRooms])
                ->groupBy('date')
                ->orderBy('rate', 'asc')
                ->first()
        ];
    }
    
    private function getCustomerBehavior()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            // Số đêm lưu trú trung bình
            'avg_stay_length' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Confirmed', 'Operational', 'Completed'])
                ->selectRaw('AVG(DATEDIFF(check_out_date, check_in_date)) as avg')
                ->value('avg'),
            // Số người lớn/trẻ em trung bình mỗi phòng
            'avg_guests_per_room' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Confirmed', 'Operational', 'Completed'])
                ->selectRaw('AVG(guest_count + COALESCE(children, 0)) as avg')
                ->value('avg'),
            // Mức giá ưa chuộng
            'price_preference' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Confirmed', 'Operational', 'Completed'])
                ->selectRaw('
                    CASE
                        WHEN total_price_vnd <= 500000 THEN "Dưới 500K"
                        WHEN total_price_vnd <= 1000000 THEN "500K-1M"
                        WHEN total_price_vnd <= 2000000 THEN "1M-2M"
                        ELSE "Trên 2M"
                    END as price_range,
                    COUNT(*) as bookings
                ')
                ->groupBy('price_range')
                ->get()
        ];
    }

    private function getSpecialPeriodAnalysis()
    {
        $thisYear = Carbon::now()->startOfYear();

        return [
            // Hiệu quả theo ngày lễ (using holidays table)
            'holidays' => DB::table('booking as b')
                ->join('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->leftJoin('holidays as h', function ($join) {
                    $join->whereRaw('b.check_in_date BETWEEN h.start_date AND h.end_date');
                })
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', $thisYear)
                ->selectRaw('
                    COALESCE(h.name, "Regular Period") as period,
                    SUM(p.amount_vnd) as revenue
                ')
                ->groupBy('h.holiday_id', 'h.name')
                ->get(),
            // So sánh doanh thu lễ hội (hardcoded periods)
            'festival_comparison' => DB::table('booking as b')
                ->join('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', $thisYear)
                ->selectRaw('
                    CASE
                        WHEN b.check_in_date BETWEEN "2025-01-01" AND "2025-02-15" THEN "Tết"
                        WHEN b.check_in_date BETWEEN "2025-04-20" AND "2025-05-05" THEN "Lễ 30/4-1/5"
                        ELSE "Bình thường"
                    END as period,
                    SUM(p.amount_vnd) as revenue
                ')
                ->groupBy('period')
                ->get()
        ];
    }

    private function getCancellationAnalysis()
{
    $thisMonth = Carbon::now()->startOfMonth();

    return [
        'by_source' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
            ->selectRaw('
                "Direct" as source,
                COUNT(*) as cancellations,
                ROUND((COUNT(*) / (SELECT COUNT(*) FROM booking WHERE created_at >= ?)) * 100, 2) as percentage
            ', [$thisMonth])
            ->groupByRaw('"Direct"')
            ->get(),
        'avg_cancellation_days' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
            ->selectRaw('COALESCE(AVG(DATEDIFF(check_in_date, updated_at)), 0) as avg')
            ->value('avg'),
        'reasons' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
            ->selectRaw('
                COALESCE(notes, "Không xác định") as reason,
                COUNT(*) as count,
                ROUND((COUNT(*) / (SELECT COUNT(*) FROM booking WHERE created_at >= ? AND status IN ("Cancelled", "Cancelled With Penalty"))) * 100, 2) as percentage
            ', [$thisMonth])
            ->groupBy('notes')
            ->get()
    ];
}

    private function getCustomerAnalysis()
{
    $thisMonth = Carbon::now()->startOfMonth();

    return [
        // Top khách hàng
        'top_customers' => DB::table('booking')
            ->selectRaw('
                guest_name,
                guest_email,
                COUNT(*) as booking_count,
                SUM(total_price_vnd) as total_spent
            ')
            ->where('created_at', '>=', $thisMonth)
            ->groupBy('guest_email', 'guest_name')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get(),
        // Nguồn khách hàng (using guest_name as proxy)
        'by_source' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->selectRaw('
                COALESCE(guest_name, "Không xác định") as customer_source,
                COUNT(*) as count,
                ROUND((COUNT(*) / (SELECT COUNT(*) FROM booking WHERE created_at >= ?)) * 100, 2) as percentage
            ', [$thisMonth])
            ->groupBy('guest_name')
            ->get()
    ];
}

    private function getAnalyticsChartData()
{
    $thisMonth = Carbon::now()->startOfMonth();
    $thisYear = Carbon::now()->startOfYear();

    return [
        // Doanh thu theo thời gian
        'revenue' => [
            'by_time' => [
                'daily' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', Carbon::now()->subDays(30))
                    ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
                'weekly' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', Carbon::now()->subWeeks(12))
                    ->selectRaw('YEARWEEK(b.created_at) as week, SUM(p.amount_vnd) as revenue')
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get()
            ],
            'by_room_type' => DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->join('room as r', 'br.room_id', '=', 'r.room_id')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->leftJoin('translation as t', function ($join) {
                    $join->on('rt.room_type_id', '=', 't.record_id')
                        ->where('t.table_name', '=', 'room_types')
                        ->where('t.column_name', '=', 'name')
                        ->where('t.language_code', '=', 'vi');
                })
                ->where('b.created_at', '>=', $thisMonth)
                ->selectRaw('COALESCE(t.value, "Unknown") as name, SUM(b.total_price_vnd) as revenue')
                ->groupBy('rt.room_type_id', 't.value')
                ->get(),
            'by_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('"Direct" as source, SUM(total_price_vnd) as revenue')
                ->groupByRaw('"Direct"')
                ->get()
        ],
        // Booking
        'booking' => [
            'status' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'by_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('"Direct" as source, COUNT(*) as count')
                ->groupByRaw('"Direct"')
                ->get(),
            'by_customer_type' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('
                    CASE 
                        WHEN guest_count > 5 THEN "Group"
                        ELSE "Individual"
                    END as customer_type,
                    COUNT(*) as count
                ')
                ->groupBy('customer_type')
                ->get()
        ],
        // Công suất
        'occupancy' => [
            'daily' => DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->where('b.created_at', '>=', $thisMonth)
                ->whereIn('b.status', ['Confirmed', 'Operational', 'Completed'])
                ->selectRaw('
                    DATE(b.check_in_date) as date,
                    COUNT(DISTINCT br.room_id) as occupied_rooms
                ')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ],
        // Mức giá ưa chuộng
        'price_preference' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Confirmed', 'Operational', 'Completed'])
            ->selectRaw('
                CASE
                    WHEN total_price_vnd <= 500000 THEN "Dưới 500K"
                    WHEN total_price_vnd <= 1000000 THEN "500K-1M"
                    WHEN total_price_vnd <= 2000000 THEN "1M-2M"
                    ELSE "Trên 2M"
                END as price_range,
                COUNT(*) as count
            ')
            ->groupBy('price_range')
            ->get(),
        // Doanh thu lễ hội
        'festival_revenue' => DB::table('booking as b')
            ->join('payment as p', 'b.booking_id', '=', 'p.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $thisYear)
            ->selectRaw('
                CASE
                    WHEN b.check_in_date BETWEEN "2025-01-01" AND "2025-02-15" THEN "Tết"
                    WHEN b.check_in_date BETWEEN "2025-04-20" AND "2025-05-05" THEN "Lễ 30/4-1/5"
                    ELSE "Bình thường"
                END as period,
                SUM(p.amount_vnd) as revenue
            ')
            ->groupBy('period')
            ->get(),
        // Hủy phòng
        'cancellation_by_source' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
            ->selectRaw('"Direct" as source, COUNT(*) as count')
            ->groupByRaw('"Direct"')
            ->get(),
        // Nguồn khách hàng
        'customer_source' => DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->selectRaw('
                COALESCE(guest_name, "Không xác định") as customer_source,
                COUNT(*) as count
            ')
            ->groupBy('guest_name')
            ->get()
    ];
}

    private function getChildSurchargeRatio()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $totalRevenue = DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Confirmed', 'Operational', 'Completed'])
            ->sum('total_price_vnd');
        $childSurcharge = DB::table('booking as b')
            ->join('children_surcharges as cs', 'b.booking_id', '=', 'cs.booking_id')
            ->where('b.created_at', '>=', $thisMonth)
            ->whereIn('b.status', ['Confirmed', 'Operational', 'Completed'])
            ->sum('cs.surcharge_amount_vnd');

        return $totalRevenue > 0 ? round(($childSurcharge / $totalRevenue) * 100, 2) : 0;
    }

    // Giữ nguyên các hàm khác từ mã nguồn trước đó
    private function getBusinessSummary()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'rooms' => [
                'available' => DB::table('room')->where('status', 'available')->count(),
                'occupied' => DB::table('room')->where('status', 'occupied')->count(),
                'maintenance' => DB::table('room')->where('status', 'maintenance')->count(),
                'total' => DB::table('room')->count()
            ],
            'bookings' => [
                'today' => DB::table('booking')->whereDate('created_at', $today)->count(),
                'this_week' => DB::table('booking')->where('created_at', '>=', $thisWeek)->count(),
                'this_month' => DB::table('booking')->where('created_at', '>=', $thisMonth)->count(),
                'total' => DB::table('booking')->count()
            ],
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
            'cancellation_rate' => $this->getCancellationRate(),
            'occupancy_rate' => $this->getOccupancyRate(),
            'adr' => $this->getAverageDailyRate(),
            'revpar' => $this->getRevPAR()
        ];
    }

    private function getChartData()
    {
        $last30Days = Carbon::now()->subDays(30);

        $revenueData = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $last30Days)
            ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $bookingData = DB::table('booking')
            ->where('created_at', '>=', $last30Days)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as bookings')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

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
            'recent_bookings' => DB::table('booking')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'arriving_today' => DB::table('booking')
                ->whereDate('check_in_date', Carbon::today())
                ->where('status', 'confirmed')
                ->get(),
            'departing_today' => DB::table('booking')
                ->whereDate('check_out_date', Carbon::today())
                ->where('status', 'confirmed')
                ->get(),
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

    
    public function getRealtimeStats(): JsonResponse
    {
        try {
            // Total rooms (assuming 'room' table exists)
            $totalRooms = DB::table('room')->count();

            // Rooms occupied (based on active bookings)
            $roomsOccupied = DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->whereIn('b.status', ['Confirmed', 'Operational'])
                ->whereDate('b.check_in_date', '<=', Carbon::today())
                ->whereDate('b.check_out_date', '>=', Carbon::today())
                ->selectRaw('COUNT(DISTINCT br.room_id) as occupied_rooms')
                ->value('occupied_rooms') ?? 0;

            // Rooms available (total rooms minus occupied)
            $roomsAvailable = max(0, $totalRooms - $roomsOccupied);

            // Pending bookings (corrected status to 'Pending')
            $pendingBookings = DB::table('booking')
                ->where('status', 'Pending')
                ->count();

            // Today's revenue
            $todayRevenue = DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->whereDate('b.created_at', Carbon::today())
                ->sum('p.amount_vnd') ?? 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'rooms_available' => $roomsAvailable,
                    'rooms_occupied' => $roomsOccupied,
                    'pending_bookings' => $pendingBookings,
                    'today_revenue' => $todayRevenue,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getRealtimeStats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu thời gian thực',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getChartDataAjax(Request $request)
    {
        $period = $request->get('period', '30');
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

    public function getAnalyticsChartDataAjax(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $analyticsData = [
            'revenue' => [
                'by_time' => [
                    'daily' => DB::table('payment as p')
                        ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                        ->where('p.status', 'completed')
                        ->where('b.created_at', '>=', $startDate)
                        ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get(),
                    'weekly' => DB::table('payment as p')
                        ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                        ->where('p.status', 'completed')
                        ->where('b.created_at', '>=', $startDate)
                        ->selectRaw('YEARWEEK(b.created_at) as week, SUM(p.amount_vnd) as revenue')
                        ->groupBy('week')
                        ->orderBy('week')
                        ->get()
                ],
                'by_room_type' => DB::table('booking as b')
                    ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                    ->join('room as r', 'br.room_id', '=', 'r.room_id')
                    ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                    ->where('b.created_at', '>=', $startDate)
                    ->selectRaw('rt.name, SUM(b.total_price_vnd) as revenue')
                    ->groupBy('rt.room_type_id', 'rt.name')
                    ->get(),
                'by_source' => DB::table('booking')
                    ->where('created_at', '>=', $startDate)
                    ->selectRaw('COALESCE(booking_source, "Direct") as source, SUM(total_price_vnd) as revenue')
                    ->groupBy('booking_source')
                    ->get()
            ],
            'booking' => [
                'status' => DB::table('booking')
                    ->where('created_at', '>=', $startDate)
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->get(),
                'by_source' => DB::table('booking')
                    ->where('created_at', '>=', $startDate)
                    ->selectRaw('COALESCE(booking_source, "Direct") as source, COUNT(*) as count')
                    ->groupBy('booking_source')
                    ->get(),
                'by_customer_type' => DB::table('booking')
                    ->where('created_at', '>=', $startDate)
                    ->selectRaw('
                        CASE 
                            WHEN number_of_guests > 5 THEN "Group"
                            ELSE "Individual"
                        END as customer_type,
                        COUNT(*) as count
                    ')
                    ->groupBy('customer_type')
                    ->get()
            ],
            'occupancy' => [
                'daily' => DB::table('booking')
                    ->where('created_at', '>=', $startDate)
                    ->where('status', '!=', 'cancelled')
                    ->selectRaw('
                        DATE(check_in_date) as date,
                        COUNT(*) as occupied_rooms
                    ')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
            ],
            'price_preference' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', '!=', 'cancelled')
                ->selectRaw('
                    CASE
                        WHEN total_price_vnd <= 500000 THEN "Dưới 500K"
                        WHEN total_price_vnd <= 1000000 THEN "500K-1M"
                        WHEN total_price_vnd <= 2000000 THEN "1M-2M"
                        ELSE "Trên 2M"
                    END as price_range,
                    COUNT(*) as count
                ')
                ->groupBy('price_range')
                ->get(),
            'festival_revenue' => DB::table('booking as b')
                ->join('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', $thisYear)
                ->selectRaw('
                    CASE
                        WHEN b.created_at BETWEEN "2025-01-01" AND "2025-02-15" THEN "Tết"
                        WHEN b.created_at BETWEEN "2025-04-20" AND "2025-05-05" THEN "Lễ 30/4-1/5"
                        ELSE "Bình thường"
                    END as period,
                    SUM(p.amount_vnd) as revenue
                ')
                ->groupBy('period')
                ->get(),
            'cancellation_by_source' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->where('status', 'cancelled')
                ->selectRaw('
                    COALESCE(booking_source, "Direct") as source,
                    COUNT(*) as count
                ')
                ->groupBy('booking_source')
                ->get(),
            'customer_source' => DB::table('booking')
                ->where('created_at', '>=', $startDate)
                ->selectRaw('
                    COALESCE(guest_nationality, "Không xác định") as nationality,
                    COUNT(*) as count
                ')
                ->groupBy('guest_nationality')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $analyticsData
        ]);
    }
}