<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display daily transactions
     */
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $startDate = Carbon::parse($selectedDate)->startOfDay();
        $endDate = Carbon::parse($selectedDate)->endOfDay();

        // Get income transactions (payments)
        $incomeQuery = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->select([
                'p.payment_id as transaction_id',
                'p.booking_id',
                'b.booking_code',
                'b.guest_name',
                'b.guest_email',
                'p.amount_vnd as amount',
                'p.payment_type',
                'p.transaction_id as payment_transaction_id',
                'p.status',
                'p.created_at',
                'p.updated_at',
                DB::raw("'income' as transaction_type"),
                DB::raw("'Thanh toán đặt phòng' as description"),
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names')
            ])
            ->where('p.status', 'completed')
            ->whereBetween('p.created_at', [$startDate, $endDate])
            ->groupBy([
                'p.payment_id', 'p.booking_id', 'b.booking_code', 'b.guest_name', 
                'b.guest_email', 'p.amount_vnd', 'p.payment_type', 'p.transaction_id', 
                'p.status', 'p.created_at', 'p.updated_at'
            ]);

        // Get service income transactions - Updated to work without service_name
        $serviceIncomeQuery = DB::table('booking_services as bs')
            ->join('booking as b', 'bs.booking_id', '=', 'b.booking_id')
            ->leftJoin('services as s', 'bs.service_id', '=', 's.service_id') // Join with services table if exists
            ->select([
                DB::raw('CONCAT("SRV_", bs.id) as transaction_id'),
                'bs.booking_id',
                'b.booking_code',
                'b.guest_name',
                'b.guest_email',
                DB::raw('(bs.quantity * bs.price_vnd) as amount'),
                DB::raw("'service' as payment_type"),
                DB::raw('NULL as payment_transaction_id'),
                DB::raw("'completed' as status"),
                'bs.created_at',
                'bs.updated_at',
                DB::raw("'income' as transaction_type"),
                // Use service name from services table if available, otherwise use service_id
                DB::raw('CASE 
                    WHEN s.name IS NOT NULL THEN CONCAT("Dịch vụ: ", s.name, " (", bs.quantity, " x ", FORMAT(bs.price_vnd, 0), "₫)")
                    ELSE CONCAT("Dịch vụ ID: ", bs.service_id, " (", bs.quantity, " x ", FORMAT(bs.price_vnd, 0), "₫)")
                END as description'),
                DB::raw('NULL as room_names')
            ])
            ->whereBetween('bs.created_at', [$startDate, $endDate]);

        // Apply filters
        if ($request->filled('transaction_type')) {
            if ($request->transaction_type === 'income') {
                $incomeQuery->where('p.status', 'completed');
            }
        }

        if ($request->filled('payment_type')) {
            if ($request->payment_type !== 'service') {
                $incomeQuery->where('p.payment_type', $request->payment_type);
            }
        }

        if ($request->filled('guest_name')) {
            $incomeQuery->where('b.guest_name', 'like', '%' . $request->guest_name . '%');
            $serviceIncomeQuery->where('b.guest_name', 'like', '%' . $request->guest_name . '%');
        }

        if ($request->filled('booking_code')) {
            $incomeQuery->where('b.booking_code', 'like', '%' . $request->booking_code . '%');
            $serviceIncomeQuery->where('b.booking_code', 'like', '%' . $request->booking_code . '%');
        }

        // Union income and service transactions
        $allTransactions = $incomeQuery;
        
        // Only include service transactions if payment_type is not specified or is 'service'
        if (!$request->filled('payment_type') || $request->payment_type === 'service') {
            $allTransactions = $incomeQuery->union($serviceIncomeQuery);
        }
        
        $allTransactions = $allTransactions->orderBy('created_at', 'desc');

        // Get paginated results
        $transactions = DB::table(DB::raw("({$allTransactions->toSql()}) as transactions"))
            ->mergeBindings($allTransactions)
            ->paginate(20);

        // Get statistics for selected date
        $statistics = $this->getTransactionStatistics($selectedDate);

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'transactions' => $transactions->items(),
                'statistics' => $statistics,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                ],
                'selected_date' => $selectedDate
            ]);
        }

        return view('admin.bookings.transactions.index', compact('transactions', 'statistics', 'selectedDate'));
    }

    /**
     * Get transaction analytics data
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        // Daily income trends
        $incomeTrends = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->selectRaw('DATE(p.created_at) as date, SUM(p.amount_vnd) as income')
            ->where('p.created_at', '>=', $startDate)
            ->where('p.status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Service income trends
        $serviceIncomeTrends = DB::table('booking_services as bs')
            ->selectRaw('DATE(bs.created_at) as date, SUM(bs.quantity * bs.price_vnd) as service_income')
            ->where('bs.created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment method distribution
        $paymentMethodDistribution = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->selectRaw('p.payment_type, SUM(p.amount_vnd) as total_amount, COUNT(*) as transaction_count')
            ->where('p.created_at', '>=', $startDate)
            ->where('p.status', 'completed')
            ->groupBy('p.payment_type')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Top services by revenue - Updated to work without service_name
        $topServices = DB::table('booking_services as bs')
            ->leftJoin('services as s', 'bs.service_id', '=', 's.service_id')
            ->selectRaw('
                COALESCE(s.name, CONCAT("Service ID: ", bs.service_id)) as service_name,
                SUM(bs.quantity * bs.price_vnd) as total_revenue,
                SUM(bs.quantity) as total_quantity,
                COUNT(DISTINCT bs.booking_id) as booking_count
            ')
            ->where('bs.created_at', '>=', $startDate)
            ->groupBy('bs.service_id', 's.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // Hourly transaction pattern (for today)
        $hourlyPattern = DB::table('payment as p')
            ->selectRaw('HOUR(p.created_at) as hour, COUNT(*) as transaction_count, SUM(p.amount_vnd) as hourly_revenue')
            ->whereDate('p.created_at', Carbon::today())
            ->where('p.status', 'completed')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Total statistics
        $totalStats = [
            'total_income' => DB::table('payment as p')
                ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
                ->where('p.status', 'completed')
                ->where('p.created_at', '>=', $startDate)
                ->sum('p.amount_vnd'),
            'total_service_income' => DB::table('booking_services')
                ->where('created_at', '>=', $startDate)
                ->sum(DB::raw('quantity * price_vnd')),
            'total_transactions' => DB::table('payment')
                ->where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'avg_transaction_value' => DB::table('payment')
                ->where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->avg('amount_vnd'),
        ];

        // Compare with previous period
        $previousPeriodStart = Carbon::now()->subDays($period * 2);
        $previousPeriodEnd = Carbon::now()->subDays($period);

        $previousIncome = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereBetween('p.created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->sum('p.amount_vnd');

        $incomeGrowth = $previousIncome > 0 
            ? (($totalStats['total_income'] - $previousIncome) / $previousIncome) * 100 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'income_trends' => $incomeTrends,
                'service_income_trends' => $serviceIncomeTrends,
                'payment_method_distribution' => $paymentMethodDistribution,
                'top_services' => $topServices,
                'hourly_pattern' => $hourlyPattern,
                'total_stats' => $totalStats,
                'income_growth' => round($incomeGrowth, 2),
                'previous_period_income' => $previousIncome,
                'period' => $period
            ]
        ]);
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $startDate = Carbon::parse($selectedDate)->startOfDay();
        $endDate = Carbon::parse($selectedDate)->endOfDay();

        // Get all transactions for the selected date
        $incomeQuery = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->select([
                'p.payment_id as transaction_id',
                'b.booking_code',
                'b.guest_name',
                'p.amount_vnd as amount',
                'p.payment_type',
                'p.created_at',
                DB::raw("'Thu nhập' as transaction_type"),
                DB::raw("'Thanh toán đặt phòng' as description"),
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names')
            ])
            ->where('p.status', 'completed')
            ->whereBetween('p.created_at', [$startDate, $endDate])
            ->groupBy([
                'p.payment_id', 'b.booking_code', 'b.guest_name', 
                'p.amount_vnd', 'p.payment_type', 'p.created_at'
            ]);

        $serviceIncomeQuery = DB::table('booking_services as bs')
            ->join('booking as b', 'bs.booking_id', '=', 'b.booking_id')
            ->leftJoin('services as s', 'bs.service_id', '=', 's.service_id')
            ->select([
                DB::raw('CONCAT("SRV_", bs.id) as transaction_id'),
                'b.booking_code',
                'b.guest_name',
                DB::raw('(bs.quantity * bs.price_vnd) as amount'),
                DB::raw("'Dịch vụ' as payment_type"),
                'bs.created_at',
                DB::raw("'Thu nhập' as transaction_type"),
                DB::raw('COALESCE(CONCAT("Dịch vụ: ", s.name), CONCAT("Service ID: ", bs.service_id)) as description'),
                DB::raw('NULL as room_names')
            ])
            ->whereBetween('bs.created_at', [$startDate, $endDate]);

        $transactions = $incomeQuery->union($serviceIncomeQuery)
            ->orderBy('created_at', 'desc')
            ->get();

        // Create CSV content
        $csvContent = "Mã giao dịch,Mã đặt phòng,Tên khách hàng,Số tiền,Loại thanh toán,Loại giao dịch,Mô tả,Phòng,Thời gian\n";
        
        foreach ($transactions as $transaction) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $transaction->transaction_id,
                $transaction->booking_code,
                $transaction->guest_name,
                number_format($transaction->amount),
                $transaction->payment_type,
                $transaction->transaction_type,
                $transaction->description,
                $transaction->room_names ?? 'N/A',
                Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s')
            );
        }

        $filename = 'transactions_' . $selectedDate . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($csvContent));
    }

    /**
     * Get transaction statistics for a specific date
     */
    private function getTransactionStatistics($date)
    {
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Today's statistics
        $todayIncome = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereBetween('p.created_at', [$startDate, $endDate])
            ->sum('p.amount_vnd');

        $todayServiceIncome = DB::table('booking_services')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('quantity * price_vnd'));

        $todayTransactionCount = DB::table('payment')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Yesterday's statistics for comparison
        $yesterdayIncome = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereDate('p.created_at', $yesterday)
            ->sum('p.amount_vnd');

        // This month statistics
        $thisMonthStart = Carbon::parse($date)->startOfMonth();
        $thisMonthIncome = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->where('p.status', 'completed')
            ->whereBetween('p.created_at', [$thisMonthStart, $endDate])
            ->sum('p.amount_vnd');

        $thisMonthServiceIncome = DB::table('booking_services')
            ->whereBetween('created_at', [$thisMonthStart, $endDate])
            ->sum(DB::raw('quantity * price_vnd'));

        // Payment method breakdown for selected date
        $paymentMethods = DB::table('payment as p')
            ->selectRaw('p.payment_type, SUM(p.amount_vnd) as total, COUNT(*) as count')
            ->where('p.status', 'completed')
            ->whereBetween('p.created_at', [$startDate, $endDate])
            ->groupBy('p.payment_type')
            ->get();

        // Growth calculation
        $incomeGrowth = $yesterdayIncome > 0 
            ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100 
            : 0;

        return [
            'selected_date' => $date,
            'today_income' => $todayIncome,
            'today_service_income' => $todayServiceIncome,
            'today_total_income' => $todayIncome + $todayServiceIncome,
            'today_transaction_count' => $todayTransactionCount,
            'yesterday_income' => $yesterdayIncome,
            'income_growth' => round($incomeGrowth, 2),
            'this_month_income' => $thisMonthIncome,
            'this_month_service_income' => $thisMonthServiceIncome,
            'this_month_total_income' => $thisMonthIncome + $thisMonthServiceIncome,
            'payment_methods' => $paymentMethods,
            'avg_transaction_value' => $todayTransactionCount > 0 ? round($todayIncome / $todayTransactionCount, 0) : 0,
        ];
    }

    /**
     * Get transaction details
     */
    public function show(Request $request, $id)
    {
        // Try to find in payments first
        $transaction = DB::table('payment as p')
            ->join('booking as b', 'p.booking_id', '=', 'b.booking_id')
            ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
            ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
            ->select([
                'p.payment_id as transaction_id',
                'p.booking_id',
                'b.booking_code',
                'b.guest_name',
                'b.guest_email',
                'b.guest_phone',
                'p.amount_vnd as amount',
                'p.payment_type',
                'p.transaction_id as payment_transaction_id',
                'p.status',
                'p.created_at',
                'p.updated_at',
                DB::raw("'income' as transaction_type"),
                DB::raw("'Thanh toán đặt phòng' as description"),
                DB::raw('GROUP_CONCAT(DISTINCT r.name) as room_names'),
                'b.check_in_date',
                'b.check_out_date'
            ])
            ->where('p.payment_id', $id)
            ->groupBy([
                'p.payment_id', 'p.booking_id', 'b.booking_code', 'b.guest_name', 
                'b.guest_email', 'b.guest_phone', 'p.amount_vnd', 'p.payment_type', 
                'p.transaction_id', 'p.status', 'p.created_at', 'p.updated_at',
                'b.check_in_date', 'b.check_out_date'
            ])
            ->first();

        // If not found in payments, try booking services
        if (!$transaction && str_starts_with($id, 'SRV_')) {
            $serviceId = str_replace('SRV_', '', $id);
            $transaction = DB::table('booking_services as bs')
                ->join('booking as b', 'bs.booking_id', '=', 'b.booking_id')
                ->leftJoin('services as s', 'bs.service_id', '=', 's.service_id')
                ->select([
                    DB::raw('CONCAT("SRV_", bs.id) as transaction_id'),
                    'bs.booking_id',
                    'b.booking_code',
                    'b.guest_name',
                    'b.guest_email',
                    'b.guest_phone',
                    DB::raw('(bs.quantity * bs.price_vnd) as amount'),
                    DB::raw("'service' as payment_type"),
                    DB::raw('NULL as payment_transaction_id'),
                    DB::raw("'completed' as status"),
                    'bs.created_at',
                    'bs.updated_at',
                    DB::raw("'income' as transaction_type"),
                    DB::raw('COALESCE(CONCAT("Dịch vụ: ", s.name, " (", bs.quantity, " x ", FORMAT(bs.price_vnd, 0), "₫)"), CONCAT("Service ID: ", bs.service_id, " (", bs.quantity, " x ", FORMAT(bs.price_vnd, 0), "₫)")) as description'),
                    DB::raw('NULL as room_names'),
                    'b.check_in_date',
                    'b.check_out_date'
                ])
                ->where('bs.id', $serviceId)
                ->first();
        }

        if (!$transaction) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy giao dịch'
                ], 404);
            }
            abort(404);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);
        }

        return view('admin.bookings.transactions.show', compact('transaction'));
    }

    /**
     * Get daily comparison data
     */
    public function getDailyComparison(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $compareDate = Carbon::parse($date)->subDay()->format('Y-m-d');

        $selectedStats = $this->getTransactionStatistics($date);
        $compareStats = $this->getTransactionStatistics($compareDate);

        return response()->json([
            'success' => true,
            'selected_date' => $date,
            'compare_date' => $compareDate,
            'selected_stats' => $selectedStats,
            'compare_stats' => $compareStats,
            'growth' => [
                'income' => $compareStats['today_total_income'] > 0 
                    ? (($selectedStats['today_total_income'] - $compareStats['today_total_income']) / $compareStats['today_total_income']) * 100 
                    : 0,
                'transactions' => $compareStats['today_transaction_count'] > 0 
                    ? (($selectedStats['today_transaction_count'] - $compareStats['today_transaction_count']) / $compareStats['today_transaction_count']) * 100 
                    : 0
            ]
        ]);
    }
}