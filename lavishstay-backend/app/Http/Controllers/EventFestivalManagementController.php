<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventFestivalManagementController extends Controller
{
    /**
     * Display the main page
     */
    public function index()
    {
       return view('admin.pricing.event_festival');
    }
    /**
     * Get statistics data
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_events' => Event::count(),
                'total_holidays' => Holiday::count(),
                'active_total' => Event::where('is_active', 1)->count() + Holiday::where('is_active', 1)->count(),
                'upcoming_total' => $this->getUpcomingCount()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }

    /**
     * Get upcoming events and holidays
     */
    public function getUpcoming()
    {
        try {
            $upcoming = collect();
            $now = Carbon::now();
            $futureLimit = Carbon::now()->addDays(30);

            // Get upcoming events
            $events = Event::where('is_active', 1)
                ->where('start_date', '>=', $now->toDateString())
                ->where('start_date', '<=', $futureLimit->toDateString())
                ->orderBy('start_date')
                ->get()
                ->map(function ($event) use ($now) {
                    $startDate = Carbon::parse($event->start_date);
                    $diff = $startDate->diffInDays($now, false);
                    return [
                        'id' => $event->event_id,
                        'name' => $event->name,
                        'type' => 'event',
                        'start_date' => $event->start_date,
                        'end_date' => $event->end_date,
                        'days_until' => (int)abs($diff),
                        'time_until' => $startDate->format('H:i')
                    ];
                });

            // Get upcoming holidays
            $holidays = Holiday::where('is_active', 1)
                ->where('start_date', '>=', $now->toDateString())
                ->where('start_date', '<=', $futureLimit->toDateString())
                ->orderBy('start_date')
                ->get()
                ->map(function ($holiday) use ($now) {
                    $startDate = Carbon::parse($holiday->start_date);
                    $diff = $startDate->diffInDays($now, false);
                    return [
                        'id' => $holiday->holiday_id,
                        'name' => $holiday->name,
                        'type' => 'holiday',
                        'start_date' => $holiday->start_date,
                        'end_date' => $holiday->end_date,
                        'days_until' => (int)abs($diff),
                        'time_until' => $startDate->format('H:i')
                    ];
                });

            $upcoming = $events->concat($holidays)->sortBy('start_date')->take(5);

            return response()->json($upcoming->values());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load upcoming events'], 500);
        }
    }

    /**
     * Get paginated data
     */
    public function getData(Request $request)
    {
        try {
            $data = collect();

            // Get events
            $eventsQuery = Event::select('event_id as id', 'name', 'description', 'start_date', 'end_date', 'is_active', 'created_at')
                ->selectRaw("'event' as type");

            // Get holidays
            $holidaysQuery = Holiday::select('holiday_id as id', 'name', 'description', 'start_date', 'end_date', 'is_active', 'created_at')
                ->selectRaw("'holiday' as type");

            // Apply filters
            if ($request->filled('type')) {
                if ($request->type === 'events') {
                    $holidaysQuery = $holidaysQuery->whereRaw('1 = 0'); // Exclude holidays
                } elseif ($request->type === 'holidays') {
                    $eventsQuery = $eventsQuery->whereRaw('1 = 0'); // Exclude events
                }
            }

            if ($request->filled('start_date')) {
                $eventsQuery->where('start_date', '>=', $request->start_date);
                $holidaysQuery->where('start_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $eventsQuery->where('start_date', '<=', $request->end_date);
                $holidaysQuery->where('start_date', '<=', $request->end_date);
            }

            // Combine queries
            $combined = $eventsQuery->union($holidaysQuery)
                ->orderBy('start_date', 'desc')
                ->paginate(10);

            return response()->json($combined);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Store new event or holiday
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

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? 1
            ];

            if ($request->type === 'event') {
                Event::create($data);
                $message = 'Thêm sự kiện thành công';
            } else {
                Holiday::create($data);
                $message = 'Thêm lễ hội thành công';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm dữ liệu'
            ], 500);
        }
    }

    /**
     * Show event
     */
    public function showEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $event
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sự kiện'
            ], 404);
        }
    }

    /**
     * Show holiday
     */
    public function showHoliday($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $holiday
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lễ hội'
            ], 404);
        }
    }

    /**
     * Update event
     */
    public function updateEvent(Request $request, $id)
    {
        $validator = $this->validateData($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);
            $event->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? 1
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sự kiện thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật sự kiện'
            ], 500);
        }
    }

    /**
     * Update holiday
     */
    public function updateHoliday(Request $request, $id)
    {
        $validator = $this->validateData($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $holiday = Holiday::findOrFail($id);
            $holiday->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? 1
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật lễ hội thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật lễ hội'
            ], 500);
        }
    }

    /**
     * Delete event
     */
    public function destroyEvent($id)
    {
        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);
            $event->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa sự kiện thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa sự kiện'
            ], 500);
        }
    }

    /**
     * Delete holiday
     */
    public function destroyHoliday($id)
    {
        try {
            DB::beginTransaction();

            $holiday = Holiday::findOrFail($id);
            $holiday->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa lễ hội thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa lễ hội'
            ], 500);
        }
    }

    /**
     * Export data
     */
    public function export(Request $request)
    {
        try {
            // Implementation for export functionality
            // Similar to RoomPriceEventFestivalController export method
            
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
            'type' => 'required|in:event,holiday',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ];

        $messages = [
            'type.required' => 'Vui lòng chọn loại',
            'type.in' => 'Loại không hợp lệ',
            'name.required' => 'Vui lòng nhập tên',
            'name.max' => 'Tên không được vượt quá 100 ký tự',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'is_active.boolean' => 'Trạng thái không hợp lệ'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Get upcoming count
     */
    private function getUpcomingCount()
    {
        $now = Carbon::now();
        $futureLimit = Carbon::now()->addDays(30);

        $eventsCount = Event::where('is_active', 1)
            ->where('start_date', '>=', $now->toDateString())
            ->where('start_date', '<=', $futureLimit->toDateString())
            ->count();

        $holidaysCount = Holiday::where('is_active', 1)
            ->where('start_date', '>=', $now->toDateString())
            ->where('start_date', '<=', $futureLimit->toDateString())
            ->count();

        return $eventsCount + $holidaysCount;
    }
}
