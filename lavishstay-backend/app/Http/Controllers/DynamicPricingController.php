<?php

namespace App\Http\Controllers;

use App\Models\DynamicPricingRule;
use App\Models\RoomType;
use App\Services\DynamicPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DynamicPricingController extends Controller
{

        protected $dynamicPricingService;
    public function __construct(DynamicPricingService $dynamicPricingService)
    {
        $this->dynamicPricingService = $dynamicPricingService;
    }


    public function index()
    {
        return view('admin.room_prices.dynamic_price');
    }

    public function getData(Request $request)
    {
        try {
            $query = DynamicPricingRule::with('roomType')
                ->select('dynamic_pricing_rules.*')
                ->leftJoin('room_types', 'dynamic_pricing_rules.room_type_id', '=', 'room_types.room_type_id');

            // Add current occupancy rate for each rule
            $rules = $query->paginate(10);
            
            foreach ($rules as $rule) {
                $rule->room_type_name = $rule->roomType ? $rule->roomType->name : 'N/A';
                $rule->current_occupancy = $this->getCurrentOccupancyRate($rule->room_type_id);
                $rule->is_triggered = $rule->is_active && $rule->current_occupancy >= $rule->occupancy_threshold;
            }

            return response()->json($rules);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
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
            return response()->json(['error' => 'Failed to load room types'], 500);
        }
    }

    public function getOccupancyStats()
    {
        try {
            $today = now()->toDateString();
            
            // Lấy thống kê tỷ lệ lấp đầy cho từng loại phòng
            $stats = DB::table('room_types')
                ->select([
                    'room_types.room_type_id',
                    'room_types.name as room_type_name',
                    DB::raw('COALESCE(occupancy_data.total_rooms, 0) as total_rooms'),
                    DB::raw('COALESCE(occupancy_data.available_rooms, 0) as available_rooms'),
                    DB::raw('COALESCE(occupancy_data.occupancy_rate, 0) as occupancy_rate')
                ])
                ->leftJoin(DB::raw("(
                    SELECT 
                        r.room_type_id,
                        SUM(ra.total_rooms) as total_rooms,
                        SUM(ra.available_rooms) as available_rooms,
                        CASE 
                            WHEN SUM(ra.total_rooms) = 0 THEN 0
                            ELSE ROUND(((SUM(ra.total_rooms) - SUM(ra.available_rooms)) / SUM(ra.total_rooms)) * 100, 2)
                        END as occupancy_rate
                    FROM room r
                    INNER JOIN room_option ro ON r.room_id = ro.room_id
                    INNER JOIN room_availability ra ON ro.option_id = ra.option_id
                    WHERE ra.date = '{$today}'
                    GROUP BY r.room_type_id
                ) as occupancy_data"), 'room_types.room_type_id', '=', 'occupancy_data.room_type_id')
                ->orderBy('room_types.name')
                ->get();

            // Thêm status cho mỗi loại phòng
            foreach ($stats as $stat) {
                $stat->status = $this->getOccupancyStatus($stat->occupancy_rate);
            }

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Error getting occupancy stats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load occupancy stats'], 500);
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
            'price_adjustment' => 'required|numeric|min:-100|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
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

            DynamicPricingRule::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Thêm quy tắc giá động thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm quy tắc'
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
            'price_adjustment' => 'required|numeric|min:-100|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
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

            $rule->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật quy tắc giá động thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật quy tắc'
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
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái'
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
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa quy tắc'
            ], 500);
        }
    }

    // Helper methods
    private function getCurrentOccupancyRate($roomTypeId)
    {
        return $this->getOccupancyRateForDate($roomTypeId, now()->toDateString());
    }

    private function getOccupancyRateForDate($roomTypeId, $date)
    {
        $result = DB::table('room')
            ->join('room_option', 'room.room_id', '=', 'room_option.room_id')
            ->join('room_availability', 'room_option.option_id', '=', 'room_availability.option_id')
            ->where('room.room_type_id', $roomTypeId)
            ->where('room_availability.date', $date)
            ->selectRaw('SUM(room_availability.total_rooms) as total_rooms, SUM(room_availability.available_rooms) as available_rooms')
            ->first();

        if (!$result || $result->total_rooms == 0) {
            return 0;
        }

        $occupiedRooms = $result->total_rooms - $result->available_rooms;
        return round(($occupiedRooms / $result->total_rooms) * 100, 2);
    }

    private function getPriceAdjustment($roomTypeId, $occupancyRate)
    {
        $rule = DynamicPricingRule::where('room_type_id', $roomTypeId)
            ->where('is_active', 1)
            ->where('occupancy_threshold', '<=', $occupancyRate)
            ->orderBy('occupancy_threshold', 'desc')
            ->first();

        return $rule ? $rule->price_adjustment : 0;
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
