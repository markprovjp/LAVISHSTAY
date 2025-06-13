<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Holiday;
use App\Models\Room;
use App\Models\RoomPricing;
use App\Models\WeekendDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomPriceEventFestivalController extends Controller
{
    /**
     * Display the main page
     */
    public function index()
    {
        return view('admin.room_prices.event_festival.index');
    }

    /**
     * Get statistics data
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'events' => RoomPricing::whereNotNull('event_id')->distinct('event_id')->count(),
                'holidays' => RoomPricing::whereNotNull('holiday_id')->distinct('holiday_id')->count(),
                'weekends' => RoomPricing::where('is_weekend', true)->count(),
                'active' => RoomPricing::active()->count()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }

    /**
     * Get paginated pricing data
     */
    public function getData(Request $request)
    {
        try {
            $query = RoomPricing::with(['room', 'event', 'holiday'])
                ->select('room_pricing.*')
                ->leftJoin('rooms', 'room_pricing.room_id', '=', 'rooms.room_id')
                ->leftJoin('events', 'room_pricing.event_id', '=', 'events.event_id')
                ->leftJoin('holidays', 'room_pricing.holiday_id', '=', 'holidays.holiday_id')
                ->addSelect([
                    'rooms.room_name',
                    'rooms.room_type',
                    'events.name as event_name',
                    'holidays.name as holiday_name'
                ]);

            // Apply filters
            if ($request->filled('type')) {
                $query->byType($request->type);
            }

            if ($request->filled('start_date') || $request->filled('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            // Order by start date descending
            $query->orderBy('room_pricing.start_date', 'desc');

            $pricings = $query->paginate(10);

            return response()->json($pricings);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    public function getEvents()
{
    try {
        \Log::info('getEvents method called');
        
        // Kiểm tra bảng events có tồn tại không
        if (!\Schema::hasTable('events')) {
            \Log::error('Events table does not exist');
            return response()->json(['error' => 'Events table not found'], 500);
        }
        
        $events = \DB::table('events')
            ->select('event_id', 'name')
            ->where('is_active', '1')
            ->orderBy('name')
            ->get();
        
        \Log::info('Events found:', ['count' => $events->count(), 'data' => $events->toArray()]);
        
        return response()->json($events);
        
    } catch (\Exception $e) {
        \Log::error('Error in getEvents:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Failed to load events', 'message' => $e->getMessage()], 500);
    }
}

public function getHolidays()
{
    try {
        \Log::info('getHolidays method called');
        
        // Kiểm tra bảng holidays có tồn tại không
        if (!\Schema::hasTable('holidays')) {
            \Log::error('Holidays table does not exist');
            return response()->json(['error' => 'Holidays table not found'], 500);
        }
        
        $holidays = \DB::table('holidays')
            ->select('holiday_id', 'name')
            ->where('is_active', '1')
            ->orderBy('name')
            ->get();
        
        \Log::info('Holidays found:', ['count' => $holidays->count(), 'data' => $holidays->toArray()]);
        
        return response()->json($holidays);
        
    } catch (\Exception $e) {
        \Log::error('Error in getHolidays:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Failed to load holidays', 'message' => $e->getMessage()], 500);
    }
}

public function getRooms()
{
    try {
        \Log::info('getRooms method called');
        
        // Kiểm tra bảng rooms có tồn tại không
        if (!\Schema::hasTable('rooms')) {
            \Log::error('Rooms table does not exist');
            return response()->json(['error' => 'Rooms table not found'], 500);
        }
        
        $rooms = \DB::table('rooms')
            ->select('room_id', 'room_name', 'room_type')
            ->where('is_active', '1')
            ->orderBy('room_name')
            ->get();
        
        \Log::info('Rooms found:', ['count' => $rooms->count(), 'data' => $rooms->toArray()]);
        
        return response()->json($rooms);
        
    } catch (\Exception $e) {
        \Log::error('Error in getRooms:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Failed to load rooms', 'message' => $e->getMessage()], 500);
    }
}

    /**
     * Store a new pricing
     */
    public function store(Request $request)
    {
        $validator = $this->validatePricing($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $data = $request->only([
                'room_id', 'start_date', 'end_date', 'price_vnd', 'reason'
            ]);

            // Set type-specific fields
            switch ($request->pricing_type) {
                case 'event':
                    $data['event_id'] = $request->event_id;
                    $data['is_weekend'] = false;
                    break;
                case 'holiday':
                                        $data['holiday_id'] = $request->holiday_id;
                    $data['is_weekend'] = false;
                    break;
                case 'weekend':
                    $data['is_weekend'] = true;
                    break;
            }

            // Check for overlapping pricing
            if ($this->hasOverlappingPricing($data, null)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã tồn tại giá cho khoảng thời gian này'
                ], 422);
            }

            $pricing = RoomPricing::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm giá thành công',
                'data' => $pricing
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm giá'
            ], 500);
        }
    }

    /**
     * Show specific pricing
     */
    public function show($id)
    {
        try {
            $pricing = RoomPricing::with(['room', 'event', 'holiday'])
                ->findOrFail($id);

            return response()->json($pricing);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dữ liệu'
            ], 404);
        }
    }

    /**
     * Update pricing
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validatePricing($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $pricing = RoomPricing::findOrFail($id);

            $data = $request->only([
                'room_id', 'start_date', 'end_date', 'price_vnd', 'reason'
            ]);

            // Reset type-specific fields
            $data['event_id'] = null;
            $data['holiday_id'] = null;
            $data['is_weekend'] = false;

            // Set type-specific fields
            switch ($request->pricing_type) {
                case 'event':
                    $data['event_id'] = $request->event_id;
                    break;
                case 'holiday':
                    $data['holiday_id'] = $request->holiday_id;
                    break;
                case 'weekend':
                    $data['is_weekend'] = true;
                    break;
            }

            // Check for overlapping pricing (exclude current record)
            if ($this->hasOverlappingPricing($data, $id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã tồn tại giá cho khoảng thời gian này'
                ], 422);
            }

            $pricing->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giá thành công',
                'data' => $pricing
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật giá'
            ], 500);
        }
    }

    /**
     * Delete pricing
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $pricing = RoomPricing::findOrFail($id);
            $pricing->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa giá thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa giá'
            ], 500);
        }
    }

    /**
     * Validate pricing data
     */
    private function validatePricing(Request $request)
    {
        $rules = [
            'pricing_type' => 'required|in:event,holiday,weekend',
            'room_id' => 'required|exists:rooms,room_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_vnd' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255'
        ];

        // Add conditional validation based on pricing type
        if ($request->pricing_type === 'event') {
            $rules['event_id'] = 'required|exists:events,event_id';
        } elseif ($request->pricing_type === 'holiday') {
            $rules['holiday_id'] = 'required|exists:holidays,holiday_id';
        }

        $messages = [
            'pricing_type.required' => 'Vui lòng chọn loại giá',
            'pricing_type.in' => 'Loại giá không hợp lệ',
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
            'event_id.required' => 'Vui lòng chọn sự kiện',
            'event_id.exists' => 'Sự kiện không tồn tại',
            'holiday_id.required' => 'Vui lòng chọn lễ hội',
            'holiday_id.exists' => 'Lễ hội không tồn tại',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Check for overlapping pricing
     */
    private function hasOverlappingPricing($data, $excludeId = null)
    {
        $query = RoomPricing::where('room_id', $data['room_id'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                  ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('start_date', '<=', $data['start_date'])
                         ->where('end_date', '>=', $data['end_date']);
                  });
            });

        // Add type-specific conditions
        if (isset($data['event_id'])) {
            $query->where('event_id', $data['event_id']);
        } elseif (isset($data['holiday_id'])) {
            $query->where('holiday_id', $data['holiday_id']);
        } elseif (isset($data['is_weekend']) && $data['is_weekend']) {
            $query->where('is_weekend', true);
        }

        if ($excludeId) {
            $query->where('pricing_id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Bulk delete pricing
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:room_pricing,pricing_id'
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

            RoomPricing::whereIn('pricing_id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa thành công ' . count($request->ids) . ' bản ghi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa dữ liệu'
            ], 500);
        }
    }

    /**
     * Export pricing data
     */
    public function export(Request $request)
    {
        try {
            $query = RoomPricing::with(['room', 'event', 'holiday'])
                ->select('room_pricing.*')
                ->leftJoin('rooms', 'room_pricing.room_id', '=', 'rooms.room_id')
                ->leftJoin('events', 'room_pricing.event_id', '=', 'events.event_id')
                ->leftJoin('holidays', 'room_pricing.holiday_id', '=', 'holidays.holiday_id')
                ->addSelect([
                    'rooms.room_name',
                    'rooms.room_type',
                    'events.name as event_name',
                    'holidays.name as holiday_name'
                ]);

            // Apply filters
            if ($request->filled('type')) {
                $query->byType($request->type);
            }

            if ($request->filled('start_date') || $request->filled('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            $pricings = $query->orderBy('room_pricing.start_date', 'desc')->get();

            // Generate CSV
            $filename = 'room_pricing_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($pricings) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                fputcsv($file, [
                    'ID',
                    'Loại',
                    'Tên sự kiện/lễ hội',
                    'Phòng',
                    'Loại phòng',
                    'Ngày bắt đầu',
                    'Ngày kết thúc',
                    'Giá (VND)',
                    'Lý do',
                    'Trạng thái'
                ]);

                // Data
                foreach ($pricings as $pricing) {
                    $type = '';
                    $name = '';
                    
                    if ($pricing->event_id) {
                        $type = 'Sự kiện';
                        $name = $pricing->event_name;
                    } elseif ($pricing->holiday_id) {
                        $type = 'Lễ hội';
                        $name = $pricing->holiday_name;
                    } elseif ($pricing->is_weekend) {
                        $type = 'Cuối tuần';
                        $name = 'Giá cuối tuần';
                    }

                    $status = '';
                    $now = now()->toDateString();
                    if ($now < $pricing->start_date) {
                        $status = 'Sắp diễn ra';
                    } elseif ($now >= $pricing->start_date && $now <= $pricing->end_date) {
                        $status = 'Đang áp dụng';
                    } else {
                        $status = 'Đã kết thúc';
                    }

                    fputcsv($file, [
                        $pricing->pricing_id,
                        $type,
                        $name,
                        $pricing->room_name,
                        $pricing->room_type,
                        $pricing->start_date,
                        $pricing->end_date,
                        number_format($pricing->price_vnd, 0, ',', '.'),
                        $pricing->reason,
                        $status
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xuất dữ liệu'
            ], 500);
        }
    }
}

