<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RoomPricing;
use App\Models\Room;
use App\Models\Event;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomPriceController extends Controller
{

    public function index()
    {
        return view('admin.room_prices.event_festival.index');
    }
    public function event_festival ()
    {
        return view('admin.room_prices.event_festival.index');
    }

    public function getStatistics()
    {
        try {
            $stats = [
                'events' => RoomPricing::whereNotNull('event_id')->distinct('event_id')->count(),
                'holidays' => RoomPricing::whereNotNull('holiday_id')->distinct('holiday_id')->count(),
                'weekends' => RoomPricing::where('is_weekend', true)->count(),
                'active' => RoomPricing::where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->count()
            ];
            
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }

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
                switch ($request->type) {
                    case 'event':
                        $query->whereNotNull('room_pricing.event_id');
                        break;
                    case 'holiday':
                        $query->whereNotNull('room_pricing.holiday_id');
                        break;
                    case 'weekend':
                        $query->where('room_pricing.is_weekend', true);
                        break;
                }
            }

            if ($request->filled('start_date')) {
                $query->where('room_pricing.start_date', '>=', $request->start_date);
            }

                       if ($request->filled('end_date')) {
                $query->where('room_pricing.end_date', '<=', $request->end_date);
            }

            // Order by latest
            $query->orderBy('room_pricing.created_at', 'desc');

            // Paginate
            $perPage = $request->get('per_page', 15);
            $data = $query->paginate($perPage);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    public function getRooms()
    {
        try {
            $rooms = Room::select('room_id', 'room_name', 'room_type')
                ->where('status', 'active')
                ->orderBy('room_name')
                ->get();
            
            return response()->json($rooms);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load rooms'], 500);
        }
    }

    public function getEvents()
    {
        try {
            $events = Event::select('event_id', 'name')
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
            
            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load events'], 500);
        }
    }

    public function getHolidays()
    {
        try {
            $holidays = Holiday::select('holiday_id', 'name')
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
            
            return response()->json($holidays);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load holidays'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pricing_type' => 'required|in:event,holiday,weekend',
            'room_id' => 'required|exists:rooms,room_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_vnd' => 'required|numeric|min:0',
            'event_id' => 'required_if:pricing_type,event|exists:events,event_id',
            'holiday_id' => 'required_if:pricing_type,holiday|exists:holidays,holiday_id',
            'reason' => 'nullable|string|max:500'
        ], [
            'pricing_type.required' => 'Vui lòng chọn loại giá',
            'pricing_type.in' => 'Loại giá không hợp lệ',
            'room_id.required' => 'Vui lòng chọn phòng',
            'room_id.exists' => 'Phòng không tồn tại',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'price_vnd.required' => 'Vui lòng nhập giá',
            'price_vnd.numeric' => 'Giá phải là số',
            'price_vnd.min' => 'Giá phải lớn hơn 0',
            'event_id.required_if' => 'Vui lòng chọn sự kiện',
            'event_id.exists' => 'Sự kiện không tồn tại',
            'holiday_id.required_if' => 'Vui lòng chọn lễ hội',
            'holiday_id.exists' => 'Lễ hội không tồn tại'
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

            // Check for overlapping pricing
            $overlappingQuery = RoomPricing::where('room_id', $request->room_id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_date', '<=', $request->start_date)
                              ->where('end_date', '>=', $request->end_date);
                        });
                });

            if ($overlappingQuery->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã có giá được thiết lập cho phòng này trong khoảng thời gian đã chọn'
                ], 422);
            }

            $data = [
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_vnd' => $request->price_vnd,
                'reason' => $request->reason,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Set specific fields based on pricing type
            switch ($request->pricing_type) {
                case 'event':
                    $data['event_id'] = $request->event_id;
                    $data['holiday_id'] = null;
                    $data['is_weekend'] = false;
                    break;
                case 'holiday':
                    $data['holiday_id'] = $request->holiday_id;
                    $data['event_id'] = null;
                    $data['is_weekend'] = false;
                    break;
                case 'weekend':
                    $data['is_weekend'] = true;
                    $data['event_id'] = null;
                    $data['holiday_id'] = null;
                    break;
            }

            RoomPricing::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm giá thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu dữ liệu'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pricing = RoomPricing::with(['room', 'event', 'holiday'])->findOrFail($id);
            
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pricing_type' => 'required|in:event,holiday,weekend',
            'room_id' => 'required|exists:rooms,room_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'price_vnd' => 'required|numeric|min:0',
            'event_id' => 'required_if:pricing_type,event|exists:events,event_id',
            'holiday_id' => 'required_if:pricing_type,holiday|exists:holidays,holiday_id',
            'reason' => 'nullable|string|max:500'
        ], [
            'pricing_type.required' => 'Vui lòng chọn loại giá',
            'pricing_type.in' => 'Loại giá không hợp lệ',
            'room_id.required' => 'Vui lòng chọn phòng',
            'room_id.exists' => 'Phòng không tồn tại',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'price_vnd.required' => 'Vui lòng nhập giá',
            'price_vnd.numeric' => 'Giá phải là số',
            'price_vnd.min' => 'Giá phải lớn hơn 0',
            'event_id.required_if' => 'Vui lòng chọn sự kiện',
            'event_id.exists' => 'Sự kiện không tồn tại',
            'holiday_id.required_if' => 'Vui lòng chọn lễ hội',
            'holiday_id.exists' => 'Lễ hội không tồn tại'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pricing = RoomPricing::findOrFail($id);

            DB::beginTransaction();

            // Check for overlapping pricing (exclude current record)
            $overlappingQuery = RoomPricing::where('room_id', $request->room_id)
                ->where('pricing_id', '!=', $id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_date', '<=', $request->start_date)
                              ->where('end_date', '>=', $request->end_date);
                        });
                });

            if ($overlappingQuery->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã có giá được thiết lập cho phòng này trong khoảng thời gian đã chọn'
                ], 422);
            }

            $data = [
                'room_id' => $request->room_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price_vnd' => $request->price_vnd,
                'reason' => $request->reason,
                'updated_by' => auth()->id(),
                'updated_at' => now()
            ];

            // Set specific fields based on pricing type
            switch ($request->pricing_type) {
                case 'event':
                    $data['event_id'] = $request->event_id;
                    $data['holiday_id'] = null;
                    $data['is_weekend'] = false;
                    break;
                case 'holiday':
                    $data['holiday_id'] = $request->holiday_id;
                    $data['event_id'] = null;
                    $data['is_weekend'] = false;
                    break;
                case 'weekend':
                    $data['is_weekend'] = true;
                    $data['event_id'] = null;
                    $data['holiday_id'] = null;
                    break;
            }

            $pricing->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giá thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật dữ liệu'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pricing = RoomPricing::findOrFail($id);
            
            // Check if pricing is currently active
            $now = now();
            if ($pricing->start_date <= $now && $pricing->end_date >= $now) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa giá đang được áp dụng'
                ], 422);
            }

            $pricing->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa giá thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa dữ liệu'
            ], 500);
        }
    }

    public function exportCsv(Request $request)
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

            // Apply same filters as getData method
            if ($request->filled('type')) {
                switch ($request->type) {
                    case 'event':
                        $query->whereNotNull('room_pricing.event_id');
                        break;
                    case 'holiday':
                        $query->whereNotNull('room_pricing.holiday_id');
                        break;
                    case 'weekend':
                        $query->where('room_pricing.is_weekend', true);
                        break;
                }
            }

            if ($request->filled('start_date')) {
                $query->where('room_pricing.start_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->where('room_pricing.end_date', '<=', $request->end_date);
            }

            $data = $query->orderBy('room_pricing.created_at', 'desc')->get();

            $filename = 'room-prices-' . date('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // CSV headers
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
                    'Trạng thái',
                    'Ngày tạo'
                ]);

                foreach ($data as $row) {
                    $type = '';
                    $name = '';
                    
                    if ($row->event_id) {
                        $type = 'Sự kiện';
                        $name = $row->event_name;
                    } elseif ($row->holiday_id) {
                        $type = 'Lễ hội';
                        $name = $row->holiday_name;
                    } elseif ($row->is_weekend) {
                        $type = 'Cuối tuần';
                        $name = 'Thứ 7 & Chủ nhật';
                    }

                    $now = now();
                    $status = ($row->start_date <= $now && $row->end_date >= $now) ? 'Đang áp dụng' : 'Chưa áp dụng';

                    fputcsv($file, [
                        $row->pricing_id,
                        $type,
                        $name,
                        $row->room_name,
                        $row->room_type,
                        $row->start_date,
                        $row->end_date,
                        number_format($row->price_vnd, 0, ',', '.'),
                        $row->reason,
                        $status,
                        $row->created_at->format('d/m/Y H:i')
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

            