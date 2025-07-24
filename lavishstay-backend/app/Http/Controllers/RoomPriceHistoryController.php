<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RoomPriceHistory;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RoomPriceHistoryExport;

class RoomPriceHistoryController extends Controller
{
    /**
     * Display the pricing history page
     */
    public function index()
    {
        return view('admin.pricing.history');
    }

    /**
     * Get paginated pricing history data
     */
    public function getData(Request $request)
    {
        try {
            $query = RoomPriceHistory::with(['roomType'])
                ->select('room_price_history.*')
                ->leftJoin('room_types', 'room_price_history.room_type_id', '=', 'room_types.room_type_id')
                ->selectRaw('room_types.name as room_type_name');

            // Apply filters
            if ($request->filled('room_type_id')) {
                $query->where('room_price_history.room_type_id', $request->room_type_id);
            }

            if ($request->filled('start_date')) {
                $query->where('room_price_history.date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->where('room_price_history.date', '<=', $request->end_date);
            }

            if ($request->filled('price_range')) {
                switch ($request->price_range) {
                    case 'under_1m':
                        $query->where('room_price_history.adjusted_price', '<', 1000000);
                        break;
                    case '1m_5m':
                        $query->whereBetween('room_price_history.adjusted_price', [1000000, 5000000]);
                        break;
                    case '5m_10m':
                        $query->whereBetween('room_price_history.adjusted_price', [5000000, 10000000]);
                        break;
                    case 'over_10m':
                        $query->where('room_price_history.adjusted_price', '>', 10000000);
                        break;
                }
            }

            if ($request->filled('rule_type')) {
                $query->whereJsonContains('room_price_history.applied_rules', [['type' => $request->rule_type]]);
            }

            $data = $query->orderBy('room_price_history.date', 'desc')
                         ->orderBy('room_price_history.created_at', 'desc')
                         ->paginate(15);

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error loading room price history data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Get statistics data
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_records' => RoomPriceHistory::count(),
                'average_price' => RoomPriceHistory::avg('adjusted_price') ?: 0,
                'average_increase' => $this->getAverageIncrease(),
                'most_common_rule' => $this->getMostCommonRule()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error loading room price history statistics: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }

    /**
     * Get chart data for price trends
     */
    public function getChartData(Request $request)
    {
        try {
            $roomTypeId = $request->get('room_type_id');
            $period = $request->get('period', '30'); // Default 30 days

            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays((int)$period);

            $query = RoomPriceHistory::select([
                DB::raw('DATE(date) as date'),
                DB::raw('AVG(adjusted_price) as avg_price')
            ])
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date');

            if ($roomTypeId) {
                $query->where('room_type_id', $roomTypeId);
            }

            $data = $query->get();

            // Fill missing dates with null values
            $labels = [];
            $prices = [];
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $labels[] = $currentDate->format('d/m');
                
                $dayData = $data->firstWhere('date', $dateStr);
                $prices[] = $dayData ? (float)$dayData->avg_price : null;
                
                $currentDate->addDay();
            }

            return response()->json([
                'labels' => $labels,
                'data' => $prices
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading chart data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load chart data'], 500);
        }
    }

    /**
     * Show specific pricing history record
     */
    public function show($id)
    {
        try {
            $history = RoomPriceHistory::with(['roomType'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch sử giá'
            ], 404);
        }
    }

    /**
     * Export pricing history data
     */
    public function export(Request $request)
    {
        try {
            $query = RoomPriceHistory::with(['roomType']);

            // Apply same filters as getData method
            if ($request->filled('room_type_id')) {
                $query->where('room_type_id', $request->room_type_id);
            }

            if ($request->filled('start_date')) {
                $query->where('date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->where('date', '<=', $request->end_date);
            }

            if ($request->filled('price_range')) {
                switch ($request->price_range) {
                    case 'under_1m':
                        $query->where('adjusted_price', '<', 1000000);
                        break;
                    case '1m_5m':
                        $query->whereBetween('adjusted_price', [1000000, 5000000]);
                        break;
                    case '5m_10m':
                        $query->whereBetween('adjusted_price', [5000000, 10000000]);
                        break;
                    case 'over_10m':
                        $query->where('adjusted_price', '>', 10000000);
                        break;
                }
            }

            if ($request->filled('rule_type')) {
                $query->whereJsonContains('applied_rules', [['type' => $request->rule_type]]);
            }

            $data = $query->orderBy('date', 'desc')->get();

            $filename = 'room_price_history_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx';

            return Excel::download(new RoomPriceHistoryExport($data), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting room price history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xuất dữ liệu'
            ], 500);
        }
    }

    /**
     * Export single pricing history record
     */
    public function exportSingle($id)
    {
        try {
            $history = RoomPriceHistory::with(['roomType'])
                ->findOrFail($id);

            $filename = 'room_price_history_' . $id . '_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx';

            return Excel::download(new RoomPriceHistoryExport(collect([$history])), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting single room price history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xuất báo cáo'
            ], 500);
        }
    }

    /**
     * Get room types for filters
     */
    public function getRoomTypes()
    {
        try {
            $roomTypes = RoomType::select('room_type_id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($roomTypes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load room types'], 500);
        }
    }
    /**
     * Calculate average price increase percentage
     */
    private function getAverageIncrease()
    {
        try {
            $result = RoomPriceHistory::selectRaw('
                AVG(
                    CASE 
                        WHEN base_price > 0 THEN ((adjusted_price - base_price) / base_price) * 100
                        ELSE 0 
                    END
                ) as avg_increase
            ')->first();

            return round($result->avg_increase ?: 0, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get most common pricing rule type
     */
    private function getMostCommonRule()
    {
        try {
            // This is a simplified version - you might need to adjust based on your JSON structure
            $result = DB::select("
                SELECT 
                    JSON_UNQUOTE(JSON_EXTRACT(applied_rules, '$[0].type')) as rule_type,
                    COUNT(*) as count
                FROM room_price_history 
                WHERE applied_rules IS NOT NULL 
                    AND JSON_LENGTH(applied_rules) > 0
                GROUP BY rule_type 
                ORDER BY count DESC 
                LIMIT 1
            ");

            if (!empty($result)) {
                $ruleTypeMap = [
                    'weekend' => 'Cuối tuần',
                    'event' => 'Sự kiện',
                    'holiday' => 'Lễ hội',
                    'season' => 'Mùa',
                    'dynamic' => 'Động'
                ];

                return $ruleTypeMap[$result[0]->rule_type] ?? $result[0]->rule_type;
            }

            return 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get pricing trends for dashboard
     */
    public function getTrends(Request $request)
    {
        try {
            $period = $request->get('period', 7); // Default 7 days
            $roomTypeId = $request->get('room_type_id');

            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays($period);

            $query = RoomPriceHistory::select([
                DB::raw('DATE(date) as date'),
                DB::raw('AVG(base_price) as avg_base_price'),
                DB::raw('AVG(adjusted_price) as avg_adjusted_price'),
                DB::raw('COUNT(*) as total_changes')
            ])
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date');

            if ($roomTypeId) {
                $query->where('room_type_id', $roomTypeId);
            }

            $trends = $query->get();

            return response()->json([
                'success' => true,
                'data' => $trends
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading pricing trends: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải xu hướng giá'
            ], 500);
        }
    }

    /**
     * Get pricing comparison between periods
     */
    public function getComparison(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date_1' => 'required|date',
                'end_date_1' => 'required|date|after_or_equal:start_date_1',
                'start_date_2' => 'required|date',
                'end_date_2' => 'required|date|after_or_equal:start_date_2',
                'room_type_id' => 'nullable|exists:room_types,room_type_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $query1 = RoomPriceHistory::whereBetween('date', [
                $request->start_date_1, 
                $request->end_date_1
            ]);

            $query2 = RoomPriceHistory::whereBetween('date', [
                $request->start_date_2, 
                $request->end_date_2
            ]);

            if ($request->filled('room_type_id')) {
                $query1->where('room_type_id', $request->room_type_id);
                $query2->where('room_type_id', $request->room_type_id);
            }

            $period1Stats = $query1->selectRaw('
                AVG(adjusted_price) as avg_price,
                MIN(adjusted_price) as min_price,
                MAX(adjusted_price) as max_price,
                COUNT(*) as total_records
            ')->first();

            $period2Stats = $query2->selectRaw('
                AVG(adjusted_price) as avg_price,
                MIN(adjusted_price) as min_price,
                MAX(adjusted_price) as max_price,
                COUNT(*) as total_records
            ')->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'period_1' => $period1Stats,
                    'period_2' => $period2Stats,
                    'comparison' => [
                        'avg_price_change' => $period1Stats->avg_price - $period2Stats->avg_price,
                        'avg_price_change_percent' => $period2Stats->avg_price > 0 
                            ? (($period1Stats->avg_price - $period2Stats->avg_price) / $period2Stats->avg_price) * 100 
                            : 0,
                        'records_change' => $period1Stats->total_records - $period2Stats->total_records,
                        'records_change_percent' => $period2Stats->total_records > 0 
                            ? (($period1Stats->total_records - $period2Stats->total_records) / $period2Stats->total_records) * 100 
                            : 0
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading pricing comparison: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi so sánh giá'
            ], 500);
        }
    }

    /**
     * Get rule effectiveness analysis
     */
    public function getRuleEffectiveness(Request $request)
    {
        try {
            $period = $request->get('period', 30); // Default 30 days
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays($period);

            // Get rule effectiveness data
            $effectiveness = DB::select("
                SELECT 
                    JSON_UNQUOTE(JSON_EXTRACT(rule_data.value, '$.type')) as rule_type,
                    JSON_UNQUOTE(JSON_EXTRACT(rule_data.value, '$.name')) as rule_name,
                    COUNT(*) as usage_count,
                    AVG(rph.adjusted_price - rph.base_price) as avg_price_impact,
                    AVG(
                        CASE 
                            WHEN rph.base_price > 0 THEN ((rph.adjusted_price - rph.base_price) / rph.base_price) * 100
                            ELSE 0 
                        END
                    ) as avg_percentage_impact
                FROM room_price_history rph
                CROSS JOIN JSON_TABLE(
                    rph.applied_rules, 
                    '$[*]' COLUMNS (
                        value JSON PATH '$'
                    )
                ) as rule_data
                WHERE rph.date BETWEEN ? AND ?
                    AND rph.applied_rules IS NOT NULL
                GROUP BY rule_type, rule_name
                ORDER BY usage_count DESC, avg_percentage_impact DESC
            ", [$startDate, $endDate]);

            return response()->json([
                'success' => true,
                'data' => $effectiveness
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading rule effectiveness: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi phân tích hiệu quả quy tắc'
            ], 500);
        }
    }

    /**
     * Get revenue impact analysis
     */
    public function getRevenueImpact(Request $request)
    {
        try {
            $period = $request->get('period', 30);
            $roomTypeId = $request->get('room_type_id');
            
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays($period);

            $query = RoomPriceHistory::selectRaw('
                DATE(date) as date,
                SUM(base_price) as total_base_revenue,
                SUM(adjusted_price) as total_adjusted_revenue,
                SUM(adjusted_price - base_price) as total_revenue_impact,
                COUNT(*) as booking_count
            ')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date');

            if ($roomTypeId) {
                $query->where('room_type_id', $roomTypeId);
            }

            $dailyImpact = $query->get();

            // Calculate totals
            $totalBaseRevenue = $dailyImpact->sum('total_base_revenue');
            $totalAdjustedRevenue = $dailyImpact->sum('total_adjusted_revenue');
            $totalImpact = $totalAdjustedRevenue - $totalBaseRevenue;
            $impactPercent = $totalBaseRevenue > 0 ? ($totalImpact / $totalBaseRevenue) * 100 : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'daily_impact' => $dailyImpact,
                    'summary' => [
                        'total_base_revenue' => $totalBaseRevenue,
                        'total_adjusted_revenue' => $totalAdjustedRevenue,
                        'total_impact' => $totalImpact,
                        'impact_percent' => round($impactPercent, 2),
                        'average_daily_impact' => $dailyImpact->count() > 0 ? $totalImpact / $dailyImpact->count() : 0
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error calculating revenue impact: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính toán tác động doanh thu'
            ], 500);
        }
    }

    /**
     * Clean old pricing history records
     */
    public function cleanOldRecords(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'days_to_keep' => 'required|integer|min:30|max:3650' // 30 days to 10 years
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $cutoffDate = Carbon::now()->subDays($request->days_to_keep);
            
            $deletedCount = RoomPriceHistory::where('created_at', '<', $cutoffDate)->delete();

            return response()->json([
                'success' => true,
                'message' => "Đã xóa {$deletedCount} bản ghi cũ",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error cleaning old room price history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi dọn dẹp dữ liệu cũ'
            ], 500);
        }
    }
}

    