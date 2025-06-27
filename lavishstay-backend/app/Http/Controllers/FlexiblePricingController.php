<?php

namespace App\Http\Controllers;
use App\Services\PricingService;
use App\Http\Controllers\Controller;
use App\Models\FlexiblePricingRule;
use App\Models\RoomType;
use App\Models\Event;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FlexiblePricingController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function index()
    {
        return view('admin.room_prices.event_festival');
    }

    /**
     * Get statistics data
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_pricing_rules' => FlexiblePricingRule::count(),
                'active_total' => FlexiblePricingRule::where('is_active', 1)->count(),
                'total_events' => Event::count(),
                'total_holidays' => Holiday::count()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }

    /**
     * Get paginated data
     */
    public function getData(Request $request)
    {
        try {
            $query = FlexiblePricingRule::with(['roomType', 'event', 'holiday'])
                ->select('flexible_pricing_rules.*')
                ->leftJoin('room_types', 'flexible_pricing_rules.room_type_id', '=', 'room_types.room_type_id')
                ->leftJoin('events', 'flexible_pricing_rules.event_id', '=', 'events.event_id')
                ->leftJoin('holidays', 'flexible_pricing_rules.holiday_id', '=', 'holidays.holiday_id')
                ->selectRaw('room_types.name as room_type_name')
                ->selectRaw('events.name as event_name')
                ->selectRaw('holidays.name as holiday_name');

            // Apply filters
            if ($request->filled('rule_type')) {
                $query->where('flexible_pricing_rules.rule_type', $request->rule_type);
            }

            if ($request->filled('start_date')) {
                $query->where(function($q) use ($request) {
                    $q->whereNull('flexible_pricing_rules.start_date')
                      ->orWhere('flexible_pricing_rules.start_date', '>=', $request->start_date);
                });
            }

            if ($request->filled('end_date')) {
                $query->where(function($q) use ($request) {
                    $q->whereNull('flexible_pricing_rules.end_date')
                      ->orWhere('flexible_pricing_rules.end_date', '<=', $request->end_date);
                });
            }

                        if ($request->filled('is_active')) {
                $query->where('flexible_pricing_rules.is_active', $request->is_active);
            }

            $data = $query->orderBy('flexible_pricing_rules.created_at', 'desc')
                         ->paginate(10);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Store new flexible pricing rule
     */
    public function store(Request $request)
    {
        $validator = $this->validateData($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate rule conflicts for events and holidays
        if (in_array($request->rule_type, ['event', 'holiday'])) {
            $conflictValidation = $this->pricingService->validateRuleConflicts(
                $request->rule_type,
                $request->start_date,
                $request->end_date
            );

            if (!$conflictValidation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $conflictValidation['message'],
                    'conflicts' => $conflictValidation['conflicts'] ?? []
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $data = [
                'room_type_id' => $request->room_type_id,
                'rule_type' => $request->rule_type,
                'price_adjustment' => $request->price_adjustment,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? true,
                'priority' => $request->priority ?? 5,
                'is_exclusive' => $request->is_exclusive ?? false
            ];

            // Add rule-specific data
            switch ($request->rule_type) {
                case 'weekend':
                    $data['days_of_week'] = json_encode($request->days_of_week);
                    break;
                case 'event':
                    $data['event_id'] = $request->event_id;
                    break;
                case 'holiday':
                    $data['holiday_id'] = $request->holiday_id;
                    break;
                case 'season':
                    $data['season_name'] = $request->season_name;
                    break;
            }

            $rule = FlexiblePricingRule::create($data);

            // Clear pricing cache for affected room types
            $this->clearAffectedCache($rule);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm quy tắc giá thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm quy tắc giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show specific rule
     */
    public function show($id)
    {
        try {
            $rule = FlexiblePricingRule::with(['roomType', 'event', 'holiday'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $rule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy quy tắc giá'
            ], 404);
        }
    }

    /**
     * Update flexible pricing rule
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validateData($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate rule conflicts for events and holidays
        if (in_array($request->rule_type, ['event', 'holiday'])) {
            $conflictValidation = $this->pricingService->validateRuleConflicts(
                $request->rule_type,
                $request->start_date,
                $request->end_date,
                $id // Exclude current rule from conflict check
            );

            if (!$conflictValidation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $conflictValidation['message'],
                    'conflicts' => $conflictValidation['conflicts'] ?? []
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $rule = FlexiblePricingRule::findOrFail($id);

            $data = [
                'room_type_id' => $request->room_type_id,
                'rule_type' => $request->rule_type,
                'price_adjustment' => $request->price_adjustment,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? true,
                'priority' => $request->priority ?? $rule->priority,
                'is_exclusive' => $request->is_exclusive ?? $rule->is_exclusive
            ];

            // Clear previous rule-specific data
            $data['days_of_week'] = null;
            $data['event_id'] = null;
            $data['holiday_id'] = null;
            $data['season_name'] = null;

            // Add new rule-specific data
            switch ($request->rule_type) {
                case 'weekend':
                    $data['days_of_week'] = json_encode($request->days_of_week);
                    break;
                case 'event':
                    $data['event_id'] = $request->event_id;
                    break;
                case 'holiday':
                    $data['holiday_id'] = $request->holiday_id;
                    break;
                case 'season':
                    $data['season_name'] = $request->season_name;
                    break;
            }

            $rule->update($data);

            // Clear pricing cache for affected room types
            $this->clearAffectedCache($rule);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật quy tắc giá thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật quy tắc giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle rule status
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $rule = FlexiblePricingRule::findOrFail($id);
            $rule->update(['is_active' => $request->is_active]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->is_active ? 'Kích hoạt quy tắc thành công' : 'Tạm dừng quy tắc thành công'
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
     * Delete rule
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $rule = FlexiblePricingRule::findOrFail($id);
            
            // Clear pricing cache before deleting
            $this->clearAffectedCache($rule);
            
            $rule->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa quy tắc giá thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa quy tắc giá: ' . $e->getMessage()
            ], 500);
        }
    }

    private function clearAffectedCache($rule)
    {
        if ($rule->room_type_id) {
            // Clear cache for specific room type
            $this->pricingService->clearPricingCache(
                $rule->room_type_id,
                $rule->start_date,
                $rule->end_date
            );
        } else {
            // Clear cache for all room types
            $roomTypes = RoomType::pluck('room_type_id');
            foreach ($roomTypes as $roomTypeId) {
                $this->pricingService->clearPricingCache(
                    $roomTypeId,
                    $rule->start_date,
                    $rule->end_date
                );
            }
        }
    }

    /**
     * Validate rule conflicts (new method)
     */
    public function validateConflicts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rule_type' => 'required|in:event,holiday',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'exclude_rule_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->pricingService->validateRuleConflicts(
                $request->rule_type,
                $request->start_date,
                $request->end_date,
                $request->exclude_rule_id
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get room types
     */
    public function getRoomTypes()
    {
        try {
            $roomTypes = RoomType::select('room_type_id', 'name')
                                ->where('is_active', 1)
                                ->orderBy('name')
                                ->get();

            return response()->json($roomTypes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load room types'], 500);
        }
    }

    /**
     * Export data
     */
    public function export(Request $request)
    {
        try {
            // Implementation for export functionality
            // You can use Laravel Excel or similar package
            
            return response()->json([
                'success' => true,
                'message' => 'Export functionality will be implemented'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xuất dữ liệu'
            ], 500);
        }
    }

    /**
     * Validate request data
     */
    private function validateData(Request $request)
    {
        $rules = [
            'rule_type' => 'required|in:weekend,event,holiday,season',
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'price_adjustment' => 'required|numeric|min:-100|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ];

        // Add conditional rules based on rule type
        if ($request->rule_type === 'weekend') {
            $rules['days_of_week'] = 'required|array|min:1';
            $rules['days_of_week.*'] = 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';
        } elseif ($request->rule_type === 'event') {
            $rules['event_id'] = 'required|exists:events,event_id';
        } elseif ($request->rule_type === 'holiday') {
            $rules['holiday_id'] = 'required|exists:holidays,holiday_id';
        } elseif ($request->rule_type === 'season') {
            $rules['season_name'] = 'required|string|max:50';
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        $messages = [
            'rule_type.required' => 'Vui lòng chọn loại quy tắc',
            'rule_type.in' => 'Loại quy tắc không hợp lệ',
            'room_type_id.exists' => 'Loại phòng không tồn tại',
            'price_adjustment.required' => 'Vui lòng nhập tỷ lệ điều chỉnh giá',
            'price_adjustment.numeric' => 'Tỷ lệ điều chỉnh giá phải là số',
            'price_adjustment.min' => 'Tỷ lệ điều chỉnh giá không được nhỏ hơn -100%',
            'price_adjustment.max' => 'Tỷ lệ điều chỉnh giá không được lớn hơn 1000%',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'days_of_week.required' => 'Vui lòng chọn ít nhất một ngày cuối tuần',
            'days_of_week.array' => 'Ngày cuối tuần không hợp lệ',
            'days_of_week.min' => 'Vui lòng chọn ít nhất một ngày cuối tuần',
            'event_id.required' => 'Vui lòng chọn sự kiện',
            'event_id.exists' => 'Sự kiện không tồn tại',
            'holiday_id.required' => 'Vui lòng chọn lễ hội',
            'holiday_id.exists' => 'Lễ hội không tồn tại',
            'season_name.required' => 'Vui lòng nhập tên mùa',
            'season_name.max' => 'Tên mùa không được vượt quá 50 ký tự',
            'is_active.boolean' => 'Trạng thái không hợp lệ'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}
