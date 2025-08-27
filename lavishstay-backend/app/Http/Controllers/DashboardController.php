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
        try {
            $businessSummary = $this->getBusinessSummary();
            $chartData = $this->getChartData();
            $detailTables = $this->getDetailTables();
            $frontDeskStats = $this->getFrontDeskStats();
            $roomTypeStats = $this->getRoomTypeStats();
            $bookingChannelStats = $this->getBookingChannelStats();
            $customerStats = $this->getCustomerStats();
            $alerts = $this->getAlerts();
            $financialMetrics = $this->getFinancialMetrics();
            
            // Thêm dữ liệu mới cho dashboard
            $recentReviews = $this->getRecentReviews();
            $hotNews = $this->getHotNews();
            $recentComments = $this->getRecentComments();

            return view('admin.dashboard.dashboard', compact(
                'businessSummary',
                'chartData',
                'detailTables',
                'frontDeskStats',
                'roomTypeStats',
                'bookingChannelStats',
                'customerStats',
                'alerts',
                'financialMetrics',
                'recentReviews',
                'hotNews',
                'recentComments'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());
            
            // Return với dữ liệu mặc định nếu có lỗi
            return view('admin.dashboard.dashboard', [
                'businessSummary' => $this->getDefaultBusinessSummary(),
                'chartData' => ['revenue' => [], 'bookings' => [], 'occupancy' => []],
                'detailTables' => $this->getDefaultDetailTables(),
                'frontDeskStats' => $this->getDefaultFrontDeskStats(),
                'roomTypeStats' => ['booking_by_type' => [], 'occupancy_by_type' => []],
                'bookingChannelStats' => [],
                'customerStats' => $this->getDefaultCustomerStats(),
                'alerts' => $this->getDefaultAlerts(),
                'financialMetrics' => $this->getDefaultFinancialMetrics(),
                'recentReviews' => [],
                'hotNews' => [],
                'recentComments' => []
            ]);
        }
    }

    public function analytics($id = null)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Analytics Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải dữ liệu phân tích.');
        }
    }

    private function getBusinessSummary()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Tính phòng đang sử dụng dựa trên booking đang hoạt động
        $occupiedRooms = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->whereIn('b.status', ['Confirmed', 'Operational'])
            ->whereDate('b.check_in_date', '<=', $today)
            ->whereDate('b.check_out_date', '>=', $today)
            ->distinct('br.room_id')
            ->count('br.room_id');

        $totalRooms = DB::table('room')->count();
        $availableRooms = max(0, $totalRooms - $occupiedRooms);

        return [
            'rooms' => [
                'available' => $availableRooms,
                'occupied' => $occupiedRooms,
                'maintenance' => DB::table('room')->where('status', 'maintenance')->count(),
                'total' => $totalRooms
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
                    ->sum('p.amount_vnd') ?? 0,
                'this_week' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', $thisWeek)
                    ->sum('p.amount_vnd') ?? 0,
                'this_month' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', $thisMonth)
                    ->sum('p.amount_vnd') ?? 0
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
                ->whereIn('status', ['Confirmed', 'Operational'])
                ->get(),
            'departing_today' => DB::table('booking')
                ->whereDate('check_out_date', Carbon::today())
                ->whereIn('status', ['Confirmed', 'Operational'])
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
                ->whereIn('status', ['Confirmed', 'Operational'])
                ->count(),
            'checkouts_today' => DB::table('booking')
                ->whereDate('check_out_date', $today)
                ->whereIn('status', ['Confirmed', 'Operational'])
                ->count(),
            'pending_bookings' => DB::table('booking')
                ->where('status', 'Pending')
                ->count(),
                'rooms_need_cleaning' => DB::table('room')
                    ->where('cleaning_ends_at', '>', Carbon::now())
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
                ->whereNotNull('guest_email')
                ->groupBy('guest_email', 'guest_name')
                ->orderBy('total_spent', 'desc')
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
                ->where('cleaning_ends_at', '>', Carbon::now())
                ->count(),
            'overdue_payments' => DB::table('payment')
                ->where('status', 'pending')
                ->where('created_at', '<', Carbon::now()->subDays(3))
                ->count(),
            'arriving_tomorrow' => DB::table('booking')
                ->whereDate('check_in_date', $tomorrow)
                ->whereIn('status', ['Confirmed', 'Operational'])
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
                ->sum('amount_vnd') ?? 0,
            'pending_payments' => DB::table('payment')
                ->where('status', 'pending')
                ->sum('amount_vnd') ?? 0,
            'total_bookings_value' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereNotIn('status', ['Cancelled', 'Cancelled With Penalty'])
                ->sum('total_price_vnd') ?? 0
        ];
    }

    // Thêm các method mới cho reviews, news, comments
    private function getRecentReviews()
    {
        try {
            // Kiểm tra xem bảng reviews có tồn tại không
            if (!DB::getSchemaBuilder()->hasTable('reviews')) {
                return collect([]);
            }

            return DB::table('reviews as r')
                ->leftJoin('booking as b', 'r.booking_id', '=', 'b.booking_id')
                ->select('r.*', 'b.guest_name')
                ->orderBy('r.created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            Log::warning('Reviews table not found: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function getHotNews()
    {
        try {
            // Kiểm tra xem bảng news có tồn tại không
            if (!DB::getSchemaBuilder()->hasTable('news')) {
                return collect([]);
            }

            return DB::table('news')
                ->select('title', 'summary', 'views', 'created_at')
                ->where('status', 'published')
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            Log::warning('News table not found: ' . $e->getMessage());
            return collect([]);
        }
    }

    private function getRecentComments()
    {
        try {
            // Kiểm tra xem bảng comments có tồn tại không
            if (!DB::getSchemaBuilder()->hasTable('comments')) {
                return collect([]);
            }

            return DB::table('comments as c')
                ->leftJoin('news as n', 'c.news_id', '=', 'n.id')
                ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
                ->select('c.content', 'c.created_at', 'u.name', 'n.title')
                ->orderBy('c.created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            Log::warning('Comments table not found: ' . $e->getMessage());
            return collect([]);
        }
    }

    // Các method tính toán chỉ số
    private function getCancellationRate()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $totalBookings = DB::table('booking')->where('created_at', '>=', $thisMonth)->count();
        $cancelledBookings = DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
            ->count();

        return $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0;
    }

    private function getOccupancyRate()
    {
        $today = Carbon::today();
        $totalRooms = DB::table('room')->count();
        
        // Tính phòng đang được sử dụng dựa trên booking đang hoạt động
        $occupiedRooms = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->whereIn('b.status', ['Confirmed', 'Operational'])
            ->whereDate('b.check_in_date', '<=', $today)
            ->whereDate('b.check_out_date', '>=', $today)
            ->distinct('br.room_id')
            ->count('br.room_id');

        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
    }

    private function getAverageDailyRate()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $totalRevenue = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->where('b.created_at', '>=', $thisMonth)
            ->sum('p.amount_vnd') ?? 0;
            
        $totalNights = DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereNotIn('status', ['Cancelled', 'Cancelled With Penalty'])
            ->selectRaw('SUM(DATEDIFF(check_out_date, check_in_date)) as total_nights')
            ->value('total_nights') ?? 0;

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

        return DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->where('b.created_at', '>=', $startDate)
            ->whereNotIn('b.status', ['Cancelled', 'Cancelled With Penalty'])
            ->selectRaw('DATE(b.check_in_date) as date, COUNT(DISTINCT br.room_id) as occupied_rooms')
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
        $today = Carbon::today();
        
        return DB::table('room_types as rt')
            ->leftJoin('room as r', 'rt.room_type_id', '=', 'r.room_type_id')
            ->leftJoin('booking_rooms as br', 'r.room_id', '=', 'br.room_id')
            ->leftJoin('booking as b', function($join) use ($today) {
                $join->on('br.booking_id', '=', 'b.booking_id')
                     ->whereIn('b.status', ['Confirmed', 'Operational'])
                     ->whereDate('b.check_in_date', '<=', $today)
                     ->whereDate('b.check_out_date', '>=', $today);
            })
            ->selectRaw('
                rt.name,
                COUNT(DISTINCT r.room_id) as total_rooms,
                COUNT(DISTINCT CASE WHEN b.booking_id IS NOT NULL THEN r.room_id END) as occupied_rooms,
                ROUND((COUNT(DISTINCT CASE WHEN b.booking_id IS NOT NULL THEN r.room_id END) / COUNT(DISTINCT r.room_id)) * 100, 2) as occupancy_rate
            ')
            ->groupBy('rt.room_type_id', 'rt.name')
            ->get();
    }

    private function getReturningCustomers()
    {
        return DB::table('booking')
            ->selectRaw('guest_email')
            ->whereNotNull('guest_email')
            ->groupBy('guest_email')
            ->havingRaw('COUNT(*) > 1')
            ->count();
    }

    // ANALYTICS METHODS - Các method phân tích chi tiết cho trang analytics

    private function getRevenueAnalysis()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $last30Days = Carbon::now()->subDays(30);

        return [
            'by_time' => [
                'daily' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', $last30Days)
                    ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
                'weekly' => DB::table('payment as p')
                    ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                    ->where('p.status', 'completed')
                    ->where('b.created_at', '>=', $last30Days)
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
                    ->get()
            ],
            'by_room_type' => DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->join('room as r', 'br.room_id', '=', 'r.room_id')
                ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->where('b.created_at', '>=', $thisMonth)
                ->selectRaw('rt.name, SUM(b.total_price_vnd) as revenue')
                ->groupBy('rt.room_type_id', 'rt.name')
                ->get(),
            'by_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('COALESCE(booking_source, "Direct") as source, SUM(total_price_vnd) as revenue')
                ->groupBy('booking_source')
                ->get(),
            'by_policy' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('
                    CASE 
                        WHEN total_price_vnd > 0 THEN "Có doanh thu"
                        ELSE "Không doanh thu"
                    END as policy_name,
                    SUM(total_price_vnd) as revenue,
                    COUNT(*) as bookings,
                    ROUND((COUNT(*) / (SELECT COUNT(*) FROM booking WHERE created_at >= ?)) * 100, 2) as completion_rate
                ', [$thisMonth])
                ->groupBy('policy_name')
                ->get(),
            'child_surcharge_ratio' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('guest_count', '>', 2)
                ->count() > 0 ? 
                round((DB::table('booking')->where('created_at', '>=', $thisMonth)->where('guest_count', '>', 2)->count() / 
                       DB::table('booking')->where('created_at', '>=', $thisMonth)->count()) * 100, 2) : 0,
            'child_surcharge_total' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->where('guest_count', '>', 2)
                ->sum('total_price_vnd') ?? 0,
            'total_revenue' => DB::table('payment')
                ->where('status', 'completed')
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount_vnd') ?? 0
        ];
    }

    private function getBookingAnalysis()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'status_ratio' => [
                'success' => DB::table('booking')
                    ->where('created_at', '>=', $thisMonth)
                    ->where('status', 'Completed')
                    ->count(),
                'cancelled' => DB::table('booking')
                    ->where('created_at', '>=', $thisMonth)
                    ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
                    ->count(),
                'total' => DB::table('booking')
                    ->where('created_at', '>=', $thisMonth)
                    ->count()
            ],
            'by_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('COALESCE(booking_source, "Direct") as source, COUNT(*) as bookings')
                ->groupBy('booking_source')
                ->get(),
            'avg_daily' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->count() / max(1, Carbon::now()->diffInDays($thisMonth)),
            'avg_seasonal' => DB::table('booking')
                ->where('created_at', '>=', Carbon::now()->subMonths(3))
                ->count() / 3,
            'by_customer_type' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('
                    CASE 
                        WHEN guest_count = 1 THEN "Khách lẻ"
                        WHEN guest_count = 2 THEN "Cặp đôi"
                        WHEN guest_count > 2 THEN "Gia đình"
                        ELSE "Khác"
                    END as customer_type,
                    COUNT(*) as count
                ')
                ->groupBy('customer_type')
                ->get()
        ];
    }

    private function getOccupancyAnalysis()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $totalRooms = DB::table('room')->count();

        $dailyOccupancy = DB::table('booking as b')
            ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->where('b.created_at', '>=', $thisMonth)
            ->whereNotIn('b.status', ['Cancelled', 'Cancelled With Penalty'])
            ->selectRaw('
                DATE(b.check_in_date) as date,
                COUNT(DISTINCT br.room_id) as occupied_rooms,
                ROUND((COUNT(DISTINCT br.room_id) / ?) * 100, 2) as occupancy_rate
            ', [$totalRooms])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'by_room_type' => $this->getOccupancyByRoomType(),
            'daily' => $dailyOccupancy,
            'highest_day' => $dailyOccupancy->sortByDesc('occupancy_rate')->first() ?? (object)['date' => 'N/A', 'rate' => 0],
            'lowest_day' => $dailyOccupancy->sortBy('occupancy_rate')->first() ?? (object)['date' => 'N/A', 'rate' => 0]
        ];
    }

    private function getCustomerBehavior()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'avg_stay_length' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Confirmed', 'Operational', 'Completed'])
                ->selectRaw('AVG(DATEDIFF(check_out_date, check_in_date)) as avg')
                ->value('avg') ?? 0,
            'avg_guests_per_room' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Confirmed', 'Operational', 'Completed'])
                ->avg('guest_count') ?? 0,
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
            'holidays' => DB::table('booking as b')
                ->join('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', $thisYear)
                ->selectRaw('
                    CASE
                        WHEN b.check_in_date BETWEEN "2025-01-01" AND "2025-02-15" THEN "Tết Nguyên Đán"
                        WHEN b.check_in_date BETWEEN "2025-04-20" AND "2025-05-05" THEN "Lễ 30/4-1/5"
                        WHEN b.check_in_date BETWEEN "2025-09-01" AND "2025-09-05" THEN "Lễ Quốc Khánh"
                        ELSE "Ngày thường"
                    END as period,
                    SUM(p.amount_vnd) as revenue,
                    COUNT(*) as bookings
                ')
                ->groupBy('period')
                ->get(),
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

        $cancelledBookings = DB::table('booking')
            ->where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
            ->get();

        return [
            'by_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
                ->selectRaw('
                    COALESCE(booking_source, "Direct") as source,
                    COUNT(*) as cancellations
                ')
                ->groupBy('booking_source')
                ->get(),
            'reasons' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
                ->selectRaw('
                    CASE 
                        WHEN notes LIKE "%thay đổi kế hoạch%" THEN "Thay đổi kế hoạch"
                        WHEN notes LIKE "%không thanh toán%" THEN "Không thanh toán"
                        WHEN notes LIKE "%tìm được chỗ khác%" THEN "Tìm được chỗ khác"
                        ELSE "Lý do khác"
                    END as reason,
                    COUNT(*) as count,
                    ROUND((COUNT(*) / (SELECT COUNT(*) FROM booking WHERE created_at >= ? AND status IN ("Cancelled", "Cancelled With Penalty"))) * 100, 2) as percentage
                ', [$thisMonth])
                ->groupBy('reason')
                ->get(),
            'avg_cancellation_days' => $cancelledBookings->map(function($booking) {
                return Carbon::parse($booking->check_in_date)->diffInDays(Carbon::parse($booking->created_at));
            })->avg() ?? 0
        ];
    }

    private function getCustomerAnalysis()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'top_customers' => DB::table('booking')
                ->selectRaw('
                    guest_name,
                    guest_email,
                    COUNT(*) as booking_count,
                    SUM(total_price_vnd) as total_spent
                ')
                ->where('created_at', '>=', $thisMonth)
                ->whereNotNull('guest_email')
                ->groupBy('guest_email', 'guest_name')
                ->orderBy('total_spent', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    private function getAnalyticsChartData()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $last30Days = Carbon::now()->subDays(30);

        return [
            'revenue' => [
                'by_time' => [
                    'daily' => DB::table('payment as p')
                        ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                        ->where('p.status', 'completed')
                        ->where('b.created_at', '>=', $last30Days)
                        ->selectRaw('DATE(b.created_at) as date, SUM(p.amount_vnd) as revenue')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get(),
                    'weekly' => DB::table('payment as p')
                        ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                        ->where('p.status', 'completed')
                        ->where('b.created_at', '>=', $last30Days)
                        ->selectRaw('YEARWEEK(b.created_at) as week, SUM(p.amount_vnd) as revenue')
                        ->groupBy('week')
                        ->orderBy('week')
                        ->get()
                ],
                'by_room_type' => DB::table('booking as b')
                    ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                    ->join('room as r', 'br.room_id', '=', 'r.room_id')
                    ->join('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                    ->where('b.created_at', '>=', $thisMonth)
                    ->selectRaw('rt.name, SUM(b.total_price_vnd) as revenue')
                    ->groupBy('rt.room_type_id', 'rt.name')
                    ->get(),
                'by_source' => DB::table('booking')
                    ->where('created_at', '>=', $thisMonth)
                    ->selectRaw('COALESCE(booking_source, "Direct") as source, SUM(total_price_vnd) as revenue')
                    ->groupBy('booking_source')
                    ->get()
            ],
            'booking' => [
                'status' => DB::table('booking')
                    ->where('created_at', '>=', $thisMonth)
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->get(),
                'by_source' => DB::table('booking')
                    ->where('created_at', '>=', $thisMonth)
                    ->selectRaw('COALESCE(booking_source, "Direct") as source, COUNT(*) as count')
                    ->groupBy('booking_source')
                    ->get(),
                'by_customer_type' => DB::table('booking')
                    ->where('created_at', '>=', $thisMonth)
                    ->selectRaw('
                        CASE 
                            WHEN guest_count = 1 THEN "Khách lẻ"
                            WHEN guest_count = 2 THEN "Cặp đôi"
                            WHEN guest_count > 2 THEN "Gia đình"
                            ELSE "Khác"
                        END as customer_type,
                        COUNT(*) as count
                    ')
                    ->groupBy('customer_type')
                    ->get()
            ],
            'occupancy' => [
                'daily' => $this->getOccupancyChartData($last30Days)
            ],
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
            'festival_revenue' => DB::table('booking as b')
                ->join('payment as p', 'b.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->where('b.created_at', '>=', Carbon::now()->startOfYear())
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
            'cancellation_by_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->whereIn('status', ['Cancelled', 'Cancelled With Penalty'])
                ->selectRaw('
                    COALESCE(booking_source, "Direct") as source,
                    COUNT(*) as count
                ')
                ->groupBy('booking_source')
                ->get(),
            'customer_source' => DB::table('booking')
                ->where('created_at', '>=', $thisMonth)
                ->selectRaw('
                    CASE 
                        WHEN booking_source IS NULL THEN "Direct"
                        ELSE booking_source
                    END as customer_source,
                    COUNT(*) as count
                ')
                ->groupBy('customer_source')
                ->get()
        ];
    }

    // API endpoints cho real-time updates
    public function getRealtimeStats(): JsonResponse
    {
        try {
            $today = Carbon::today();
            $totalRooms = DB::table('room')->count();

            // Rooms occupied (based on active bookings)
            $roomsOccupied = DB::table('booking as b')
                ->join('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->whereIn('b.status', ['Confirmed', 'Operational'])
                ->whereDate('b.check_in_date', '<=', $today)
                ->whereDate('b.check_out_date', '>=', $today)
                ->distinct('br.room_id')
                ->count('br.room_id');

            // Rooms available
            $roomsAvailable = max(0, $totalRooms - $roomsOccupied);

            // Today's bookings
            $todayBookings = DB::table('booking')
                ->whereDate('created_at', $today)
                ->count();

            // Today's revenue
            $todayRevenue = DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->whereDate('b.created_at', $today)
                ->sum('p.amount_vnd') ?? 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'rooms_available' => $roomsAvailable,
                    'rooms_occupied' => $roomsOccupied,
                    'today_bookings' => $todayBookings,
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
        try {
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
        } catch (\Exception $e) {
            Log::error('Error in getChartDataAjax: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu biểu đồ'
            ], 500);
        }
    }

    // Analytics API endpoints
    public function getAnalyticsRealtimeStats(): JsonResponse
    {
        try {
            $analyticsData = $this->getAnalyticsChartData();
            
            return response()->json([
                'success' => true,
                'data' => $analyticsData
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAnalyticsRealtimeStats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu phân tích thời gian thực'
            ], 500);
        }
    }

    public function getAnalyticsChartDataAjax(Request $request)
    {
        try {
            $period = $request->get('period', '30');
            $startDate = Carbon::now()->subDays($period);

            // Cập nhật dữ liệu analytics theo period
            $analyticsData = $this->getAnalyticsChartData();
            
            return response()->json([
                'success' => true,
                'data' => $analyticsData
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAnalyticsChartDataAjax: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu biểu đồ phân tích'
            ], 500);
        }
    }

    // Default data methods để tránh lỗi khi không có dữ liệu
    private function getDefaultBusinessSummary()
    {
        return [
            'rooms' => ['available' => 0, 'occupied' => 0, 'maintenance' => 0, 'total' => 0],
            'bookings' => ['today' => 0, 'this_week' => 0, 'this_month' => 0, 'total' => 0],
            'revenue' => ['today' => 0, 'this_week' => 0, 'this_month' => 0],
            'cancellation_rate' => 0,
            'occupancy_rate' => 0,
            'adr' => 0,
            'revpar' => 0
        ];
    }

    private function getDefaultDetailTables()
    {
        return [
            'recent_bookings' => collect([]),
            'arriving_today' => collect([]),
            'departing_today' => collect([]),
            'recent_payments' => collect([])
        ];
    }

    private function getDefaultFrontDeskStats()
    {
        return [
            'checkins_today' => 0,
            'checkouts_today' => 0,
            'pending_bookings' => 0,
            'rooms_need_cleaning' => 0,
            'rooms_maintenance' => 0
        ];
    }

    private function getDefaultCustomerStats()
    {
        return [
            'new_customers' => 0,
            'returning_customers' => 0,
            'top_customers' => collect([])
        ];
    }

    private function getDefaultAlerts()
    {
        return [
            'rooms_need_cleaning' => 0,
            'overdue_payments' => 0,
            'arriving_tomorrow' => 0,
            'maintenance_rooms' => 0
        ];
    }

    private function getDefaultFinancialMetrics()
    {
        return [
            'total_collected' => 0,
            'pending_payments' => 0,
            'total_bookings_value' => 0
        ];
    }
}