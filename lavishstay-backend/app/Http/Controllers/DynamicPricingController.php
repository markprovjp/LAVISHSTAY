<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DynamicPricingRule;
use App\Models\RoomType;
use App\Services\DynamicPricingService;
use App\Services\RoomOccupancyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DynamicPricingController extends Controller
{
    protected DynamicPricingService $pricingService;
    protected RoomOccupancyService $occupancyService;

    public function __construct(DynamicPricingService $pricingService, RoomOccupancyService $occupancyService)
    {
        $this->pricingService = $pricingService;
        $this->occupancyService = $occupancyService;
    }

    /**
     * Display the dynamic pricing management page
     */
    public function index()
    {
        return view('admin.pricing.dynamic_price');
    }

    /**
     * Get dynamic pricing rules data for DataTable
     */
    public function getData(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            // Get rules with current status
            $rulesWithStatus = $this->pricingService->getRulesWithStatus();
            
            // Convert to collection for pagination
            $collection = collect($rulesWithStatus);
            $total = $collection->count();
            
            // Manual pagination
            $offset = ($page - 1) * $perPage;
            $items = $collection->slice($offset, $perPage)->values();
            
            // Add additional room information
            $items = $items->map(function ($rule) {
                $roomType = RoomType::find($rule['room_type_id']);
                if ($roomType) {
                    $occupancyStats = $this->occupancyService->getOccupancyStats();
                    $roomStats = collect($occupancyStats)->firstWhere('room_type_id', $rule['room_type_id']);
                    
                    $rule['total_rooms'] = $roomStats['total_rooms'] ?? $roomType->total_room;
                    $rule['available_rooms'] = $roomStats['available_rooms'] ?? 0;
                }
                
                return $rule;
            });
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'total' => $total,
                'current_page' => $page,
                'per_page' => $perPage,
                'last_page' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting dynamic pricing data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải dữ liệu: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get occupancy statistics
     */
    public function getOccupancyStats()
    {
        try {
            $stats = $this->occupancyService->getOccupancyStats();
            
            // Add active and triggered rules count for each room type
            $statsWithRules = collect($stats)->map(function ($stat) {
                $rules = DynamicPricingRule::where('room_type_id', $stat['room_type_id'])->get();
                $activeRules = $rules->where('is_active', true);
                $triggeredRules = $activeRules->where('occupancy_threshold', '<=', $stat['occupancy_rate']);
                
                $stat['active_rules'] = $activeRules->count();
                $stat['triggered_rules'] = $triggeredRules->count();
                
                return $stat;
            });

            return response()->json([
                'success' => true,
                'data' => $statsWithRules->toArray()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting occupancy stats', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải thống kê lấp đầy: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get room types for select options
     */
    public function getRoomTypes()
    {
        try {
            $roomTypes = RoomType::select('room_type_id', 'name', 'base_price', 'total_room')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $roomTypes
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting room types', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tải danh sách loại phòng: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Store a new dynamic pricing rule
     */
    public function store(Request $request)
    {
        try {
            // Validate request data
            $validator = Validator::make($request->all(), [
                'room_type_id' => 'required|exists:room_types,room_type_id',
                'occupancy_threshold' => 'required|numeric|min:0|max:100',
                'price_adjustment' => 'required|numeric|min:-100|max:500',
                'is_active' => 'boolean'
            ], [
                'room_type_id.required' => 'Vui lòng chọn loại phòng',
                'room_type_id.exists' => 'Loại phòng không tồn tại',
                'occupancy_threshold.required' => 'Vui lòng nhập ngưỡng lấp đầy',
                'occupancy_threshold.numeric' => 'Ngưỡng lấp đầy phải là số',
                'occupancy_threshold.min' => 'Ngưỡng lấp đầy phải >= 0',
                'occupancy_threshold.max' => 'Ngưỡng lấp đầy phải <= 100',
                'price_adjustment.required' => 'Vui lòng nhập tỷ lệ điều chỉnh giá',
                'price_adjustment.numeric' => 'Tỷ lệ điều chỉnh giá phải là số',
                'price_adjustment.min' => 'Tỷ lệ điều chỉnh giá phải >= -100%',
                'price_adjustment.max' => 'Tỷ lệ điều chỉnh giá phải <= 500%'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Additional validation using service
            $serviceErrors = $this->pricingService->validateRule($request->all());
            if (!empty($serviceErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $serviceErrors
                ], 422);
            }

            // Check for duplicate rules (same room type and threshold)
            $existingRule = DynamicPricingRule::where('room_type_id', $request->room_type_id)
                ->where('occupancy_threshold', $request->occupancy_threshold)
                ->first();

            if ($existingRule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã tồn tại quy tắc với ngưỡng lấp đầy này cho loại phòng đã chọn'
                ], 422);
            }

            // Create new rule
            $rule = DynamicPricingRule::create([
                'room_type_id' => $request->room_type_id,
                'occupancy_threshold' => $request->occupancy_threshold,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->boolean('is_active', true),
                'priority' => $request->get('priority', 1),
                'is_exclusive' => $request->boolean('is_exclusive', false)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm quy tắc giá động thành công',
                'data' => $rule
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating dynamic pricing rule', [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo quy tắc: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a specific dynamic pricing rule
     */
    public function show($id)
    {
        try {
            $rule = DynamicPricingRule::with('roomType')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $rule
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting dynamic pricing rule', [
                'rule_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy quy tắc'
            ], 404);
        }
    }

    /**
     * Update a dynamic pricing rule
     */
    public function update(Request $request, $id)
    {
        try {
            $rule = DynamicPricingRule::findOrFail($id);

            // Validate request data
            $validator = Validator::make($request->all(), [
                'room_type_id' => 'required|exists:room_types,room_type_id',
                'occupancy_threshold' => 'required|numeric|min:0|max:100',
                'price_adjustment' => 'required|numeric|min:-100|max:500',
                'is_active' => 'boolean'
            ], [
                'room_type_id.required' => 'Vui lòng chọn loại phòng',
                'room_type_id.exists' => 'Loại phòng không tồn tại',
                'occupancy_threshold.required' => 'Vui lòng nhập ngưỡng lấp đầy',
                'occupancy_threshold.numeric' => 'Ngưỡng lấp đầy phải là số',
                'occupancy_threshold.min' => 'Ngưỡng lấp đầy phải >= 0',
                'occupancy_threshold.max' => 'Ngưỡng lấp đầy phải <= 100',
                'price_adjustment.required' => 'Vui lòng nhập tỷ lệ điều chỉnh giá',
                'price_adjustment.numeric' => 'Tỷ lệ điều chỉnh giá phải là số',
                'price_adjustment.min' => 'Tỷ lệ điều chỉnh giá phải >= -100%',
                'price_adjustment.max' => 'Tỷ lệ điều chỉnh giá phải <= 500%'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check for duplicate rules (excluding current rule)
            $existingRule = DynamicPricingRule::where('room_type_id', $request->room_type_id)
                ->where('occupancy_threshold', $request->occupancy_threshold)
                ->where('rule_id', '!=', $id)
                ->first();

            if ($existingRule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã tồn tại quy tắc với ngưỡng lấp đầy này cho loại phòng đã chọn'
                ], 422);
            }

            // Update rule
            $rule->update([
                'room_type_id' => $request->room_type_id,
                'occupancy_threshold' => $request->occupancy_threshold,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->boolean('is_active', true),
                'priority' => $request->get('priority', $rule->priority),
                'is_exclusive' => $request->boolean('is_exclusive', $rule->is_exclusive)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật quy tắc giá động thành công',
                'data' => $rule
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating dynamic pricing rule', [
                'rule_id' => $id,
                'request_data' => $request->all(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật quy tắc: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle rule status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $rule = DynamicPricingRule::findOrFail($id);
            $rule->is_active = !$rule->is_active;
            $rule->save();

            $status = $rule->is_active ? 'kích hoạt' : 'tạm dừng';

            return response()->json([
                'success' => true,
                'message' => "Đã {$status} quy tắc thành công",
                'data' => $rule
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling rule status', [
                'rule_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thay đổi trạng thái quy tắc'
            ], 500);
        }
    }

    /**
     * Delete a dynamic pricing rule
     */
    public function destroy($id)
    {
        try {
            $rule = DynamicPricingRule::findOrFail($id);
            $rule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa quy tắc giá động thành công'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting dynamic pricing rule', [
                'rule_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa quy tắc'
            ], 500);
        }
    }

    /**
     * Sync occupancy data manually
     */
    public function syncOccupancy()
    {
        try {
            $results = $this->occupancyService->updateAllRoomOccupancy();
            
            $successCount = collect($results)->where('success', true)->count();
            $totalCount = count($results);

            return response()->json([
                'success' => true,
                'message' => "Đồng bộ thành công {$successCount}/{$totalCount} loại phòng",
                'data' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing occupancy data', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đồng bộ dữ liệu lấp đầy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate occupancy percentage for a room type and date
     * Updated to use the new service
     */
    public function calculateOccupancyPercent($roomTypeId, $checkIn)
    {
        try {
            $date = Carbon::parse($checkIn);
            $occupancyRate = $this->occupancyService->getCurrentOccupancyRate($roomTypeId, $date);
            
            return $occupancyRate;

        } catch (\Exception $e) {
            Log::error('Error calculating occupancy percent', [
                'room_type_id' => $roomTypeId,
                'check_in' => $checkIn,
                'error' => $e->getMessage()
            ]);
            
            return 0.0;
        }
    }

    /**
     * Calculate adjusted price for a room type
     * Updated to use the new service
     */
    public function calculateAdjustedPrice($roomTypeId, Request $request)
    {
        try {
            $checkIn = $request->get('check_in') ? Carbon::parse($request->get('check_in')) : Carbon::today();
            $basePrice = $request->get('base_price');
            
            $priceData = $this->pricingService->calculateAdjustedPrice($roomTypeId, $checkIn, $basePrice);
            
            return $priceData['adjusted_price'];

        } catch (\Exception $e) {
            Log::error('Error calculating adjusted price', [
                'room_type_id' => $roomTypeId,
                'request_data' => $request->all(),
                'error' => $e->getMessage()
            ]);
            
            // Return base price as fallback
            $roomType = RoomType::find($roomTypeId);
            return $roomType ? $roomType->base_price : 0;
        }
    }

    /**
     * Get pricing analysis for a room type
     */
    public function getPricingAnalysis(Request $request, $roomTypeId)
    {
        try {
            $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::today();
            $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::today()->addDays(7);
            
            $analysis = $this->pricingService->getPricingAnalysis($roomTypeId, $startDate, $endDate);
            
            return response()->json([
                'success' => true,
                'data' => $analysis
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting pricing analysis', [
                'room_type_id' => $roomTypeId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi phân tích giá: ' . $e->getMessage()
            ], 500);
        }
    }
}