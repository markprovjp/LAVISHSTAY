<?php

namespace App\Http\Controllers;

use App\Models\DynamicPricingRule;
use App\Models\RoomType;
use App\Services\DynamicPricingService;
use App\Services\RoomOccupancyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DynamicPricingController extends Controller
{
    protected $dynamicPricingService;
    protected $occupancyService;

    public function __construct(DynamicPricingService $dynamicPricingService, RoomOccupancyService $occupancyService)
    {
        $this->dynamicPricingService = $dynamicPricingService;
        $this->occupancyService = $occupancyService;
    }

    public function index()
    {
        return view('admin.pricing.dynamic_price');
    }

    public function getData(Request $request)
    {
        try {
            $query = DynamicPricingRule::with('roomType')
                ->select('dynamic_pricing_rules.*')
                ->leftJoin('room_types', 'dynamic_pricing_rules.room_type_id', '=', 'room_types.room_type_id');

            $rules = $query->paginate(10);
            
            foreach ($rules as $rule) {
                $rule->room_type_name = $rule->roomType ? $rule->roomType->name : 'Tất cả loại phòng';
                
                // Get current occupancy data
                $occupancyData = $this->getOccupancyDataForRule($rule->room_type_id);
                $rule->current_occupancy = $occupancyData['occupancy_rate'];
                $rule->total_rooms = $occupancyData['total_rooms'];
                $rule->booked_rooms = $occupancyData['booked_rooms'];
                $rule->available_rooms = $occupancyData['available_rooms'];
                
                // Check if rule is triggered
                $rule->is_triggered = $rule->is_active && $rule->current_occupancy >= $rule->occupancy_threshold;
            }

            return response()->json($rules);
        } catch (\Exception $e) {
            \Log::error('Error in getData: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data: ' . $e->getMessage()], 500);
        }
    }

    public function getRoomTypes()
    {
        try {
            $roomTypes = RoomType::select('room_type_id', 'name')
                ->orderBy('name')
                ->get();
            
            return response()->json($roomTypes);
        } catch (\Exception $e) {
            \Log::error('Error loading room types: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load room types'], 500);
        }
    }

    public function getOccupancyStats()
    {
        try {
            $today = now()->toDateString();
            
            // Get all room types
            $roomTypes = RoomType::select('room_type_id', 'name')->get();
            
            $stats = [];
            
            foreach ($roomTypes as $roomType) {
                $occupancyData = $this->getOccupancyDataForRule($roomType->room_type_id);
                
                $stats[] = [
                    'room_type_id' => $roomType->room_type_id,
                    'room_type_name' => $roomType->name,
                    'total_rooms' => $occupancyData['total_rooms'],
                    'available_rooms' => $occupancyData['available_rooms'],
                    'booked_rooms' => $occupancyData['booked_rooms'],
                    'occupancy_rate' => $occupancyData['occupancy_rate'],
                    'status' => $this->getOccupancyStatus($occupancyData['occupancy_rate'])
                ];
            }

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error getting occupancy stats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load occupancy stats: ' . $e->getMessage()], 500);
        }
    }

    public function calculateDynamicPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'base_price' => 'required|numeric|min:0',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $occupancyRate = $this->getOccupancyRateForDate($request->room_type_id, $request->date);
            $priceAdjustment = $this->getPriceAdjustment($request->room_type_id, $occupancyRate);
            
            $basePrice = floatval($request->base_price);
            $adjustedPrice = $basePrice + ($basePrice * $priceAdjustment / 100);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'base_price' => $basePrice,
                    'occupancy_rate' => $occupancyRate,
                    'price_adjustment' => $priceAdjustment,
                    'adjusted_price' => $adjustedPrice,
                    'price_difference' => $adjustedPrice - $basePrice
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error calculating dynamic price: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính toán giá động'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'occupancy_threshold' => 'required|numeric|min:0|max:100',
            'price_adjustment' => 'required|numeric|min:-100|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Kiểm tra trùng lặp
            $exists = DynamicPricingRule::where('room_type_id', $request->room_type_id)
                ->where('occupancy_threshold', $request->occupancy_threshold)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã tồn tại quy tắc với ngưỡng này cho loại phòng đã chọn'
                ], 422);
            }

            DynamicPricingRule::create([
                'room_type_id' => $request->room_type_id,
                'occupancy_threshold' => $request->occupancy_threshold,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->is_active ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm quy tắc giá động thành công'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error storing dynamic rule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm quy tắc: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $rule = DynamicPricingRule::with('roomType')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $rule
            ]);
        } catch (\Exception $e) {
            \Log::error('Error showing dynamic rule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy quy tắc'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'occupancy_threshold' => 'required|numeric|min:0|max:100',
            'price_adjustment' => 'required|numeric|min:-100|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $rule = DynamicPricingRule::findOrFail($id);

            // Kiểm tra trùng lặp (trừ bản ghi hiện tại)
            $exists = DynamicPricingRule::where('room_type_id', $request->room_type_id)
                ->where('occupancy_threshold', $request->occupancy_threshold)
                ->where('rule_id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã tồn tại quy tắc với ngưỡng này cho loại phòng đã chọn'
                ], 422);
            }

            $rule->update([
                'room_type_id' => $request->room_type_id,
                'occupancy_threshold' => $request->occupancy_threshold,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->is_active ?? $rule->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật quy tắc giá động thành công'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating dynamic rule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật quy tắc: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $rule = DynamicPricingRule::findOrFail($id);
            $rule->is_active = !$rule->is_active;
            $rule->save();

            $status = $rule->is_active ? 'kích hoạt' : 'vô hiệu hóa';

            return response()->json([
                'success' => true,
                'message' => "Đã {$status} quy tắc thành công"
            ]);

        } catch (\Exception $e) {
            \Log::error('Error toggling dynamic rule status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

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
            \Log::error('Error deleting dynamic rule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa quy tắc: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncOccupancy(Request $request)
    {
        try {
            $date = $request->input('date', now()->toDateString());
            
            $this->occupancyService->syncOccupancyForDate($date);
            
            return response()->json([
                'success' => true,
                'message' => 'Đồng bộ dữ liệu lấp đầy thành công'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error syncing occupancy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đồng bộ dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function getOccupancyDataForRule($roomTypeId)
    {
        try {
            return $this->occupancyService->calculateOccupancyFromBookings($roomTypeId, now()->toDateString());
        } catch (\Exception $e) {
            \Log::error('Error getting occupancy data for rule: ' . $e->getMessage());
            return [
                'total_rooms' => 0,
                'available_rooms' => 0,
                'booked_rooms' => 0,
                'occupancy_rate' => 0
            ];
        }
    }

    private function getOccupancyRateForDate($roomTypeId, $date)
    {
        try {
            $occupancyData = $this->occupancyService->calculateOccupancyFromBookings($roomTypeId, $date);
            return $occupancyData['occupancy_rate'];
        } catch (\Exception $e) {
            \Log::error('Error getting occupancy rate for date: ' . $e->getMessage());
            return 0;
        }
    }

    private function getPriceAdjustment($roomTypeId, $occupancyRate)
    {
        try {
            $rule = DynamicPricingRule::where('room_type_id', $roomTypeId)
                ->where('is_active', 1)
                ->where('occupancy_threshold', '<=', $occupancyRate)
                ->orderBy('occupancy_threshold', 'desc')
                ->first();

            return $rule ? $rule->price_adjustment : 0;
        } catch (\Exception $e) {
            \Log::error('Error getting price adjustment: ' . $e->getMessage());
            return 0;
        }
    }

    private function getOccupancyStatus($occupancy)
    {
        if ($occupancy >= 90) return 'Rất cao';
        if ($occupancy >= 70) return 'Cao';
        if ($occupancy >= 50) return 'Trung bình';
        if ($occupancy >= 30) return 'Thấp';
        return 'Rất thấp';
    }
}
