<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WeekendDay;
use App\Models\RoomType;
use App\Models\FlexiblePricingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WeekendPriceController extends Controller
{
    /**
     * Display the main page
     */
    public function index()
    {
        return view('admin.room_prices.weekend_price');
    }

    /**
     * Get weekend days configuration
     */
    public function getWeekendDays()
    {
        try {
            $weekendDays = WeekendDay::orderBy('id')->get();
            
            // If no weekend days exist, create default ones
            if ($weekendDays->isEmpty()) {
                $this->createDefaultWeekendDays();
                $weekendDays = WeekendDay::orderBy('id')->get();
            }

            return response()->json($weekendDays);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load weekend days'], 500);
        }
    }

    /**
     * Update weekend days configuration
     */
    public function updateWeekendDays(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weekend_days' => 'required|array',
            'weekend_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
        ], [
            'weekend_days.required' => 'Vui lòng chọn ít nhất một ngày cuối tuần',
            'weekend_days.array' => 'Dữ liệu ngày cuối tuần không hợp lệ',
            'weekend_days.*.in' => 'Ngày trong tuần không hợp lệ'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Reset all days to inactive
            WeekendDay::query()->update(['is_active' => 0]);

            // Activate selected days
            WeekendDay::whereIn('day_of_week', $request->weekend_days)
                ->update(['is_active' => 1]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật cấu hình ngày cuối tuần thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật cấu hình'
            ], 500);
        }
    }

    /**
     * Get paginated weekend pricing rules data
     */
    public function getData(Request $request)
    {
        try {
            $query = FlexiblePricingRule::with(['roomType'])
                ->where('rule_type', 'weekend')
                ->leftJoin('room_types', 'flexible_pricing_rules.room_type_id', '=', 'room_types.room_type_id')
                ->select('flexible_pricing_rules.*', 'room_types.name as room_type_name');

            $rules = $query->orderBy('flexible_pricing_rules.created_at', 'desc')->paginate(10);

            return response()->json($rules);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Get room types for dropdown
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
     * Store new weekend pricing rule
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'price_adjustment' => 'required|numeric|min:-100|max:1000',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ], [
            'room_type_id.exists' => 'Loại phòng không tồn tại',
            'price_adjustment.required' => 'Vui lòng nhập tỷ lệ điều chỉnh giá',
            'price_adjustment.numeric' => 'Tỷ lệ điều chỉnh giá phải là số',
            'price_adjustment.min' => 'Tỷ lệ giảm giá không thể vượt quá 100%',
            'price_adjustment.max' => 'Tỷ lệ tăng giá không thể vượt quá 1000%',
            'days_of_week.required' => 'Vui lòng chọn ít nhất một ngày cuối tuần',
            'days_of_week.array' => 'Dữ liệu ngày cuối tuần không hợp lệ',
            'days_of_week.min' => 'Vui lòng chọn ít nhất một ngày cuối tuần',
            'days_of_week.*.in' => 'Ngày trong tuần không hợp lệ',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check for overlapping rules
            $this->checkOverlappingRules($request, null);

            FlexiblePricingRule::create([
                'room_type_id' => $request->room_type_id,
                'rule_type' => 'weekend',
                'days_of_week' => json_encode($request->days_of_week),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->is_active ?? true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm quy tắc cuối tuần thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show specific weekend pricing rule
     */
    public function show($id)
    {
        try {
            $rule = FlexiblePricingRule::with(['roomType'])
                ->where('rule_type', 'weekend')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $rule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dữ liệu'
            ], 404);
        }
    }

    /**
     * Update weekend pricing rule
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'price_adjustment' => 'required|numeric|min:-100|max:1000',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ], [
            'room_type_id.exists' => 'Loại phòng không tồn tại',
            'price_adjustment.required' => 'Vui lòng nhập tỷ lệ điều chỉnh giá',
            'price_adjustment.numeric' => 'Tỷ lệ điều chỉnh giá phải là số',
            'price_adjustment.min' => 'Tỷ lệ giảm giá không thể vượt quá 100%',
            'price_adjustment.max' => 'Tỷ lệ tăng giá không thể vượt quá 1000%',
            'days_of_week.required' => 'Vui lòng chọn ít nhất một ngày cuối tuần',
            'days_of_week.array' => 'Dữ liệu ngày cuối tuần không hợp lệ',
            'days_of_week.min' => 'Vui lòng chọn ít nhất một ngày cuối tuần',
            'days_of_week.*.in' => 'Ngày trong tuần không hợp lệ',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $rule = FlexiblePricingRule::where('rule_type', 'weekend')->findOrFail($id);

            // Check for overlapping rules (exclude current rule)
            $this->checkOverlappingRules($request, $id);

            $rule->update([
                'room_type_id' => $request->room_type_id,
                'days_of_week' => json_encode($request->days_of_week),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->is_active ?? true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật quy tắc cuối tuần thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle rule status
     */
    public function toggleStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $rule = FlexiblePricingRule::where('rule_type', 'weekend')->findOrFail($id);
            $rule->update(['is_active' => $request->is_active]);

            DB::commit();

            $message = $request->is_active ? 'Kích hoạt quy tắc thành công' : 'Tạm dừng quy tắc thành công';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái'
            ], 500);
        }
    }

    /**
     * Delete weekend pricing rule
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $rule = FlexiblePricingRule::where('rule_type', 'weekend')->findOrFail($id);
            $rule->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa quy tắc cuối tuần thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa quy tắc cuối tuần'
            ], 500);
        }
    }

    /**
     * Export weekend pricing rules
     */
    public function export()
    {
        try {
            $rules = FlexiblePricingRule::with(['roomType'])
                ->where('rule_type', 'weekend')
                ->get();

            $data = [];
            $data[] = ['ID', 'Loại phòng', 'Ngày cuối tuần', 'Ngày bắt đầu', 'Ngày kết thúc', 'Điều chỉnh giá (%)', 'Trạng thái'];

            foreach ($rules as $rule) {
                               $weekendDays = $rule->days_of_week ? json_decode($rule->days_of_week, true) : [];
                $dayNames = [
                    'Monday' => 'Thứ Hai',
                    'Tuesday' => 'Thứ Ba', 
                    'Wednesday' => 'Thứ Tư',
                    'Thursday' => 'Thứ Năm',
                    'Friday' => 'Thứ Sáu',
                    'Saturday' => 'Thứ Bảy',
                    'Sunday' => 'Chủ Nhật'
                ];
                $displayDays = array_map(function($day) use ($dayNames) {
                    return $dayNames[$day] ?? $day;
                }, $weekendDays);

                $data[] = [
                    $rule->rule_id,
                    $rule->roomType ? $rule->roomType->name : 'Tất cả loại phòng',
                    implode(', ', $displayDays),
                    $rule->start_date ?? '-',
                    $rule->end_date ?? '-',
                    $rule->price_adjustment . '%',
                    $rule->is_active ? 'Đang hoạt động' : 'Tạm dừng'
                ];
            }

            // Create CSV content
            $csvContent = '';
            foreach ($data as $row) {
                $csvContent .= implode(',', array_map(function($field) {
                    return '"' . str_replace('"', '""', $field) . '"';
                }, $row)) . "\n";
            }

            // Add BOM for UTF-8
            $csvContent = "\xEF\xBB\xBF" . $csvContent;

            return response($csvContent)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="weekend-rules-' . date('Y-m-d') . '.csv"');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xuất dữ liệu'
            ], 500);
        }
    }

    /**
     * Check for overlapping rules
     */
    private function checkOverlappingRules(Request $request, $excludeId = null)
    {
        $query = FlexiblePricingRule::where('rule_type', 'weekend')
            ->where('is_active', true);

        if ($excludeId) {
            $query->where('rule_id', '!=', $excludeId);
        }

        // Check for same room type
        if ($request->room_type_id) {
            $query->where('room_type_id', $request->room_type_id);
        } else {
            $query->whereNull('room_type_id');
        }

        $existingRules = $query->get();

        foreach ($existingRules as $existingRule) {
            // Check date overlap
            $hasDateOverlap = $this->checkDateOverlap(
                $request->start_date,
                $request->end_date,
                $existingRule->start_date,
                $existingRule->end_date
            );

            if ($hasDateOverlap) {
                // Check day overlap
                $existingDays = json_decode($existingRule->days_of_week, true) ?? [];
                $newDays = $request->days_of_week ?? [];
                
                $dayOverlap = array_intersect($existingDays, $newDays);
                
                if (!empty($dayOverlap)) {
                    $roomTypeName = $request->room_type_id ? 
                        RoomType::find($request->room_type_id)->name ?? 'Loại phòng không xác định' : 
                        'tất cả loại phòng';
                    
                    throw new \Exception("Đã tồn tại quy tắc cuối tuần cho {$roomTypeName} trong khoảng thời gian này với các ngày trùng lặp: " . implode(', ', $dayOverlap));
                }
            }
        }
    }

    /**
     * Check if two date ranges overlap
     */
    private function checkDateOverlap($start1, $end1, $start2, $end2)
    {
        // If either range has no dates, they don't overlap with date-specific ranges
        if ((!$start1 && !$end1) || (!$start2 && !$end2)) {
            // Both have no date restrictions - they overlap
            if ((!$start1 && !$end1) && (!$start2 && !$end2)) {
                return true;
            }
            // One has date restrictions, one doesn't - they overlap
            return true;
        }

        // Convert to timestamps for comparison
        $start1 = $start1 ? strtotime($start1) : null;
        $end1 = $end1 ? strtotime($end1) : null;
        $start2 = $start2 ? strtotime($start2) : null;
        $end2 = $end2 ? strtotime($end2) : null;

        // If no end date, assume it goes indefinitely
        if (!$end1) $end1 = PHP_INT_MAX;
        if (!$end2) $end2 = PHP_INT_MAX;
        
        // If no start date, assume it starts from beginning of time
        if (!$start1) $start1 = 0;
        if (!$start2) $start2 = 0;

        // Check overlap: start1 <= end2 && start2 <= end1
        return $start1 <= $end2 && $start2 <= $end1;
    }

    /**
     * Create default weekend days
     */
    private function createDefaultWeekendDays()
    {
        $days = [
            ['day_of_week' => 'Monday', 'is_active' => 0],
            ['day_of_week' => 'Tuesday', 'is_active' => 0],
            ['day_of_week' => 'Wednesday', 'is_active' => 0],
            ['day_of_week' => 'Thursday', 'is_active' => 0],
            ['day_of_week' => 'Friday', 'is_active' => 0],
            ['day_of_week' => 'Saturday', 'is_active' => 1],
            ['day_of_week' => 'Sunday', 'is_active' => 1],
        ];

        foreach ($days as $day) {
            WeekendDay::create($day);
        }
    }

    /**
     * Get active weekend pricing rules for a specific date and room type
     */
    public function getActiveRulesForDate($date, $roomTypeId = null)
    {
        try {
            $dayOfWeek = date('l', strtotime($date));
            
            $query = FlexiblePricingRule::where('rule_type', 'weekend')
                ->where('is_active', true)
                ->where(function($q) use ($date) {
                    $q->where(function($subQ) use ($date) {
                        // Rules with date restrictions
                        $subQ->whereNotNull('start_date')
                             ->whereNotNull('end_date')
                             ->where('start_date', '<=', $date)
                             ->where('end_date', '>=', $date);
                    })->orWhere(function($subQ) {
                        // Rules without date restrictions
                        $subQ->whereNull('start_date')
                             ->whereNull('end_date');
                    })->orWhere(function($subQ) use ($date) {
                        // Rules with only start date
                        $subQ->whereNotNull('start_date')
                             ->whereNull('end_date')
                             ->where('start_date', '<=', $date);
                    })->orWhere(function($subQ) use ($date) {
                        // Rules with only end date
                        $subQ->whereNull('start_date')
                             ->whereNotNull('end_date')
                             ->where('end_date', '>=', $date);
                    });
                });

            // Filter by room type
            if ($roomTypeId) {
                $query->where(function($q) use ($roomTypeId) {
                    $q->where('room_type_id', $roomTypeId)
                      ->orWhereNull('room_type_id');
                });
            } else {
                $query->whereNull('room_type_id');
            }

            $rules = $query->get();

            // Filter by day of week
            $applicableRules = $rules->filter(function($rule) use ($dayOfWeek) {
                $days = json_decode($rule->days_of_week, true) ?? [];
                return in_array($dayOfWeek, $days);
            });

            return $applicableRules;

        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Calculate price adjustment for a specific date and room type
     */
    public function calculatePriceAdjustment($basePrice, $date, $roomTypeId = null)
    {
        try {
            $rules = $this->getActiveRulesForDate($date, $roomTypeId);
            
            if ($rules->isEmpty()) {
                return $basePrice;
            }

            // Apply the most specific rule (room type specific over general)
            $specificRule = $rules->where('room_type_id', $roomTypeId)->first();
            $generalRule = $rules->whereNull('room_type_id')->first();
            
            $applicableRule = $specificRule ?? $generalRule;
            
            if ($applicableRule) {
                $adjustment = $applicableRule->price_adjustment / 100;
                return $basePrice * (1 + $adjustment);
            }

            return $basePrice;

        } catch (\Exception $e) {
            return $basePrice;
        }
    }

    /**
     * Bulk update rules status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rule_ids' => 'required|array',
            'rule_ids.*' => 'integer|exists:flexible_pricing_rules,rule_id',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            FlexiblePricingRule::where('rule_type', 'weekend')
                ->whereIn('rule_id', $request->rule_ids)
                ->update(['is_active' => $request->is_active]);

            DB::commit();

            $message = $request->is_active ? 
                'Kích hoạt ' . count($request->rule_ids) . ' quy tắc thành công' : 
                'Tạm dừng ' . count($request->rule_ids) . ' quy tắc thành công';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái'
            ], 500);
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_rules' => FlexiblePricingRule::where('rule_type', 'weekend')->count(),
                'active_rules' => FlexiblePricingRule::where('rule_type', 'weekend')->where('is_active', true)->count(),
                'inactive_rules' => FlexiblePricingRule::where('rule_type', 'weekend')->where('is_active', false)->count(),
                'rules_with_date_limit' => FlexiblePricingRule::where('rule_type', 'weekend')
                    ->where(function($q) {
                        $q->whereNotNull('start_date')->orWhereNotNull('end_date');
                    })->count(),
                'expired_rules' => FlexiblePricingRule::where('rule_type', 'weekend')
                    ->whereNotNull('end_date')
                    ->where('end_date', '<', now()->toDateString())
                    ->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải thống kê'
            ], 500);
        }
    }
}

