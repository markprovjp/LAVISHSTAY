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

            // Apply filters...
            if ($request->filled('rule_type')) {
                $query->where('flexible_pricing_rules.rule_type', $request->rule_type);
            }

            if ($request->filled('start_date')) {
                $query->where(function ($q) use ($request) {
                    $q->whereNull('flexible_pricing_rules.start_date')
                        ->orWhere('flexible_pricing_rules.start_date', '>=', $request->start_date);
                });
            }

            if ($request->filled('end_date')) {
                $query->where(function ($q) use ($request) {
                    $q->whereNull('flexible_pricing_rules.end_date')
                        ->orWhere('flexible_pricing_rules.end_date', '<=', $request->end_date);
                });
            }

            if ($request->filled('is_active')) {
                $query->where('flexible_pricing_rules.is_active', $request->is_active);
            }

            $data = $query->orderBy('flexible_pricing_rules.created_at', 'desc')
                ->paginate(10);

            // Debug: Log một vài records để kiểm tra
            \Log::info('Sample pricing rules data:', $data->take(2)->toArray());

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error in getData:', $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }



    /**
     * Get events for dropdown (chỉ những events chưa được sử dụng)
     */
    public function getEvents()
    {
        try {
            // Lấy danh sách event_id đã được sử dụng trong flexible_pricing_rules
            $usedEventIds = FlexiblePricingRule::where('rule_type', 'event')
                ->where('is_active', true)
                ->whereNotNull('event_id')
                ->pluck('event_id')
                ->toArray();

            // Lấy events chưa được sử dụng
            $events = \App\Models\Event::select('event_id', 'name', 'description', 'start_date', 'end_date', 'is_active')
                ->where('is_active', 1)
                ->whereNotIn('event_id', $usedEventIds)
                ->orderBy('name')
                ->get();

            \Log::info('Used event IDs:', $usedEventIds);
            \Log::info('Available events:', $events->toArray());

            return response()->json([
                'data' => $events,
                'type' => 'events',
                'used_ids' => $usedEventIds
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading events:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to load events'], 500);
        }
    }

    /**
     * Get holidays for dropdown (chỉ những holidays chưa được sử dụng)
     */
    public function getHolidays()
    {
        try {
            // Lấy danh sách holiday_id đã được sử dụng trong flexible_pricing_rules
            $usedHolidayIds = FlexiblePricingRule::where('rule_type', 'holiday')
                ->where('is_active', true)
                ->whereNotNull('holiday_id')
                ->pluck('holiday_id')
                ->toArray();

            // Lấy holidays chưa được sử dụng
            $holidays = \App\Models\Holiday::select('holiday_id', 'name', 'description', 'start_date', 'end_date', 'is_active')
                ->where('is_active', 1)
                ->whereNotIn('holiday_id', $usedHolidayIds)
                ->orderBy('name')
                ->get();

            \Log::info('Used holiday IDs:', $usedHolidayIds);
            \Log::info('Available holidays:', $holidays->toArray());

            return response()->json([
                'data' => $holidays,
                'type' => 'holidays',
                'used_ids' => $usedHolidayIds
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading holidays:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to load holidays'], 500);
        }
    }


    /**
     * Get events for dropdown including used ones (for edit mode)
     */
    public function getEventsForEdit($currentEventId = null)
    {
        try {
            // Lấy danh sách event_id đã được sử dụng (trừ event hiện tại nếu đang edit)
            $usedEventIds = FlexiblePricingRule::where('rule_type', 'event')
                ->where('is_active', true)
                ->whereNotNull('event_id')
                ->when($currentEventId, function ($query) use ($currentEventId) {
                    return $query->where('event_id', '!=', $currentEventId);
                })
                ->pluck('event_id')
                ->toArray();

            // Lấy events chưa được sử dụng hoặc event hiện tại
            $events = \App\Models\Event::select('event_id', 'name', 'description', 'start_date', 'end_date', 'is_active')
                ->where('is_active', 1)
                ->where(function ($query) use ($usedEventIds, $currentEventId) {
                    $query->whereNotIn('event_id', $usedEventIds);
                    if ($currentEventId) {
                        $query->orWhere('event_id', $currentEventId);
                    }
                })
                ->orderBy('name')
                ->get();

            return response()->json([
                'data' => $events,
                'type' => 'events',
                'used_ids' => $usedEventIds
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load events for edit'], 500);
        }
    }

    /**
     * Get holidays for dropdown including used ones (for edit mode)
     */
    public function getHolidaysForEdit($currentHolidayId = null)
    {
        try {
            // Lấy danh sách holiday_id đã được sử dụng (trừ holiday hiện tại nếu đang edit)
            $usedHolidayIds = FlexiblePricingRule::where('rule_type', 'holiday')
                ->where('is_active', true)
                ->whereNotNull('holiday_id')
                ->when($currentHolidayId, function ($query) use ($currentHolidayId) {
                    return $query->where('holiday_id', '!=', $currentHolidayId);
                })
                ->pluck('holiday_id')
                ->toArray();

            // Lấy holidays chưa được sử dụng hoặc holiday hiện tại
            $holidays = \App\Models\Holiday::select('holiday_id', 'name', 'description', 'start_date', 'end_date', 'is_active')
                ->where('is_active', 1)
                ->where(function ($query) use ($usedHolidayIds, $currentHolidayId) {
                    $query->whereNotIn('holiday_id', $usedHolidayIds);
                    if ($currentHolidayId) {
                        $query->orWhere('holiday_id', $currentHolidayId);
                    }
                })
                ->orderBy('name')
                ->get();

            return response()->json([
                'data' => $holidays,
                'type' => 'holidays',
                'used_ids' => $usedHolidayIds
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load holidays for edit'], 500);
        }
    }

    /**
     * Store new flexible pricing rule
     */
    public function store(Request $request)
    {
        // Log request data để debug
        \Log::info('FlexiblePricing Store Request:', $request->all());

        try {
            $validator = $this->validateData($request);

            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate rule conflicts for events and holidays
            if (in_array($request->rule_type, ['event', 'holiday'])) {
                try {
                    $conflictValidation = $this->pricingService->validateRuleConflicts(
                        $request->rule_type,
                        $request->start_date,
                        $request->end_date
                    );

                    if (!$conflictValidation['valid']) {
                        \Log::warning('Rule conflict detected:', $conflictValidation);
                        return response()->json([
                            'success' => false,
                            'message' => $conflictValidation['message'],
                            'conflicts' => $conflictValidation['conflicts'] ?? []
                        ], 422);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error in conflict validation:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue without conflict validation if service fails
                }
            }

            try {
                DB::beginTransaction();

                // Prepare base data
                $data = [
                    'room_type_id' => $request->room_type_id ?: null,
                    'rule_type' => $request->rule_type,
                    'price_adjustment' => $request->price_adjustment,
                    'start_date' => $request->start_date ?: null,
                    'end_date' => $request->end_date ?: null,
                    'is_active' => $request->is_active ?? true,
                    'priority' => $request->priority ?? 5,
                    'is_exclusive' => $request->is_exclusive ?? false
                ];

                \Log::info('Base data prepared:', $data);

                // Add rule-specific data
                switch ($request->rule_type) {
                    case 'weekend':
                        if ($request->has('days_of_week') && is_array($request->days_of_week)) {
                            $data['days_of_week'] = json_encode($request->days_of_week);
                            \Log::info('Weekend days added:', $request->days_of_week);
                        } else {
                            throw new \Exception('Days of week is required for weekend rules');
                        }
                        break;

                    case 'event':
                        if (!$request->event_id) {
                            throw new \Exception('Event ID is required for event rules');
                        }

                        // Verify event exists
                        $event = \App\Models\Event::find($request->event_id);
                        if (!$event) {
                            throw new \Exception('Selected event does not exist');
                        }

                        $data['event_id'] = $request->event_id;
                        // Get dates from event if not provided
                        if (!$data['start_date']) {
                            $data['start_date'] = $event->start_date;
                        }
                        if (!$data['end_date']) {
                            $data['end_date'] = $event->end_date;
                        }
                        \Log::info('Event data added:', ['event_id' => $request->event_id, 'event' => $event->toArray()]);
                        break;

                    case 'holiday':
                        if (!$request->holiday_id) {
                            throw new \Exception('Holiday ID is required for holiday rules');
                        }

                        // Verify holiday exists
                        $holiday = \App\Models\Holiday::find($request->holiday_id);
                        if (!$holiday) {
                            throw new \Exception('Selected holiday does not exist');
                        }

                        $data['holiday_id'] = $request->holiday_id;
                        // Get dates from holiday if not provided
                        if (!$data['start_date']) {
                            $data['start_date'] = $holiday->start_date;
                        }
                        if (!$data['end_date']) {
                            $data['end_date'] = $holiday->end_date;
                        }
                        \Log::info('Holiday data added:', ['holiday_id' => $request->holiday_id, 'holiday' => $holiday->toArray()]);
                        break;

                    case 'season':
                        if (!$request->season_name) {
                            throw new \Exception('Season name is required for season rules');
                        }
                        if (!$data['start_date'] || !$data['end_date']) {
                            throw new \Exception('Start date and end date are required for season rules');
                        }
                        $data['season_name'] = $request->season_name;
                        \Log::info('Season data added:', ['season_name' => $request->season_name]);
                        break;

                    default:
                        throw new \Exception('Invalid rule type: ' . $request->rule_type);
                }

                \Log::info('Final data to insert:', $data);

                // Create the rule
                $rule = FlexiblePricingRule::create($data);
                \Log::info('Rule created successfully:', ['rule_id' => $rule->rule_id]);

                // Clear pricing cache for affected room types
                try {
                    $this->clearAffectedCache($rule);
                    \Log::info('Cache cleared successfully');
                } catch (\Exception $e) {
                    \Log::warning('Failed to clear cache:', ['error' => $e->getMessage()]);
                    // Don't fail the entire operation if cache clearing fails
                }

                DB::commit();
                \Log::info('Transaction committed successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'Thêm quy tắc giá thành công',
                    'data' => $rule
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Database transaction failed:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $request->all()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi thêm quy tắc giá: ' . $e->getMessage(),
                    'debug' => config('app.debug') ? [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ] : null
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('General error in store method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi hệ thống xảy ra: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
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
        // Log request data để debug
        \Log::info('FlexiblePricing Update Request:', [
            'id' => $id,
            'request_data' => $request->all()
        ]);

        try {
            // Kiểm tra rule có tồn tại không
            $rule = FlexiblePricingRule::find($id);
            if (!$rule) {
                \Log::error('Rule not found:', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy quy tắc giá'
                ], 404);
            }

            \Log::info('Found existing rule:', $rule->toArray());

            $validator = $this->validateData($request);

            if ($validator->fails()) {
                \Log::error('Update validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate rule conflicts for events and holidays
            if (in_array($request->rule_type, ['event', 'holiday'])) {
                try {
                    $conflictValidation = $this->pricingService->validateRuleConflicts(
                        $request->rule_type,
                        $request->start_date,
                        $request->end_date,
                        $id // Exclude current rule from conflict check
                    );

                    if (!$conflictValidation['valid']) {
                        \Log::warning('Update rule conflict detected:', $conflictValidation);
                        return response()->json([
                            'success' => false,
                            'message' => $conflictValidation['message'],
                            'conflicts' => $conflictValidation['conflicts'] ?? []
                        ], 422);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error in update conflict validation:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue without conflict validation if service fails
                }
            }

            try {
                DB::beginTransaction();

                // Prepare update data
                $data = [
                    'room_type_id' => $request->room_type_id ?: null,
                    'rule_type' => $request->rule_type,
                    'price_adjustment' => $request->price_adjustment,
                    'start_date' => $request->start_date ?: null,
                    'end_date' => $request->end_date ?: null,
                    'is_active' => $request->is_active ?? true,
                    'priority' => $request->priority ?? $rule->priority,
                    'is_exclusive' => $request->is_exclusive ?? $rule->is_exclusive
                ];

                \Log::info('Base update data prepared:', $data);

                // Clear previous rule-specific data
                $data['days_of_week'] = null;
                $data['event_id'] = null;
                $data['holiday_id'] = null;
                $data['season_name'] = null;

                // Add new rule-specific data
                switch ($request->rule_type) {
                    case 'weekend':
                        if ($request->has('days_of_week') && is_array($request->days_of_week)) {
                            $data['days_of_week'] = json_encode($request->days_of_week);
                            \Log::info('Weekend days updated:', $request->days_of_week);
                        } else {
                            throw new \Exception('Days of week is required for weekend rules');
                        }
                        break;

                    case 'event':
                        if (!$request->event_id) {
                            throw new \Exception('Event ID is required for event rules');
                        }

                        // Verify event exists
                        $event = \App\Models\Event::find($request->event_id);
                        if (!$event) {
                            throw new \Exception('Selected event does not exist');
                        }

                        $data['event_id'] = $request->event_id;
                        // Get dates from event if not provided
                        if (!$data['start_date']) {
                            $data['start_date'] = $event->start_date;
                        }
                        if (!$data['end_date']) {
                            $data['end_date'] = $event->end_date;
                        }
                        \Log::info('Event data updated:', ['event_id' => $request->event_id, 'event' => $event->toArray()]);
                        break;

                    case 'holiday':
                        if (!$request->holiday_id) {
                            throw new \Exception('Holiday ID is required for holiday rules');
                        }

                        // Verify holiday exists
                        $holiday = \App\Models\Holiday::find($request->holiday_id);
                        if (!$holiday) {
                            throw new \Exception('Selected holiday does not exist');
                        }

                        $data['holiday_id'] = $request->holiday_id;
                        // Get dates from holiday if not provided
                        if (!$data['start_date']) {
                            $data['start_date'] = $holiday->start_date;
                        }
                        if (!$data['end_date']) {
                            $data['end_date'] = $holiday->end_date;
                        }
                        \Log::info('Holiday data updated:', ['holiday_id' => $request->holiday_id, 'holiday' => $holiday->toArray()]);
                        break;

                    case 'season':
                        if (!$request->season_name) {
                            throw new \Exception('Season name is required for season rules');
                        }
                        if (!$data['start_date'] || !$data['end_date']) {
                            throw new \Exception('Start date and end date are required for season rules');
                        }
                        $data['season_name'] = $request->season_name;
                        \Log::info('Season data updated:', ['season_name' => $request->season_name]);
                        break;

                    default:
                        throw new \Exception('Invalid rule type: ' . $request->rule_type);
                }

                \Log::info('Final update data:', $data);

                // Update the rule
                $rule->update($data);
                \Log::info('Rule updated successfully:', ['rule_id' => $rule->rule_id]);

                // Clear pricing cache for affected room types
                try {
                    $this->clearAffectedCache($rule);
                    \Log::info('Cache cleared successfully after update');
                } catch (\Exception $e) {
                    \Log::warning('Failed to clear cache after update:', ['error' => $e->getMessage()]);
                    // Don't fail the entire operation if cache clearing fails
                }

                DB::commit();
                \Log::info('Update transaction committed successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật quy tắc giá thành công',
                    'data' => $rule->fresh() // Get updated data
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Database transaction failed during update:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request_data' => $request->all(),
                    'rule_id' => $id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi cập nhật quy tắc giá: ' . $e->getMessage(),
                    'debug' => config('app.debug') ? [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ] : null
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('General error in update method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'rule_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi hệ thống xảy ra khi cập nhật: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
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
