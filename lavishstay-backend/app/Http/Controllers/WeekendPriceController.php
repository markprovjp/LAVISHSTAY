<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WeekendDay;
use App\Models\Room;
use App\Models\RoomPricing;
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
     * Get paginated weekend pricing data
     */
    public function getData(Request $request)
    {
        try {
            $query = RoomPricing::with(['room'])
                ->where('is_weekend', 1)
                ->leftJoin('room', 'room_pricing.room_id', '=', 'room.room_id')
                ->select('room_pricing.*', 'room.name as room_name');

            $pricings = $query->orderBy('room_pricing.start_date', 'desc')->paginate(10);

            return response()->json($pricings);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Get rooms for dropdown
     */
    public function getRooms()
    {
        try {
            $rooms = Room::select('room_id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($rooms);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load rooms'], 500);
        }
    }

    /**
     * Store new weekend pricing
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:room,room_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_vnd' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255'
        ], [
            'room_id.required' => 'Vui lòng chọn phòng',
            'room_id.exists' => 'Phòng không tồn tại',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'price_vnd.required' => 'Vui lòng nhập giá phòng',
            'price_vnd.numeric' => 'Giá phòng phải là số',
            'price_vnd.min' => 'Giá phòng phải lớn hơn 0',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự'
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

            RoomPricing::create([
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_vnd' => $request->price_vnd,
                'reason' => $request->reason,
                'is_weekend' => 1
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm giá cuối tuần thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm giá cuối tuần'
            ], 500);
        }
    }

    /**
     * Show specific weekend pricing
     */
    public function show($id)
    {
        try {
            $pricing = RoomPricing::with(['room'])
                ->where('is_weekend', 1)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pricing
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dữ liệu'
            ], 404);
        }
    }

    /**
     * Update weekend pricing
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:room,room_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_vnd' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255'
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

            $pricing = RoomPricing::where('is_weekend', 1)->findOrFail($id);
            $pricing->update([
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_vnd' => $request->price_vnd,
                'reason' => $request->reason
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giá cuối tuần thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật giá cuối tuần'
            ], 500);
        }
    }

    /**
     * Delete weekend pricing
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pricing = RoomPricing::where('is_weekend', 1)->findOrFail($id);
            $pricing->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa giá cuối tuần thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa giá cuối tuần'
            ], 500);
        }
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
}
