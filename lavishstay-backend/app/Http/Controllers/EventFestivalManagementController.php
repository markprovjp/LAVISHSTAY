<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Holiday;
<<<<<<< HEAD
use App\Models\RoomType;
=======
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD
        return view('admin.room_prices.event_festival');
    }

=======
       return view('admin.pricing.event_festival');
    }
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
    /**
     * Get statistics data
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_events' => Event::count(),
                'total_holidays' => Holiday::count(),
<<<<<<< HEAD
                'total_pricing_rules' => DB::table('flexible_pricing_rules')->count(),
                'active_rules' => DB::table('flexible_pricing_rules')->where('is_active', 1)->count(),
=======
                'active_total' => Event::where('is_active', 1)->count() + Holiday::where('is_active', 1)->count(),
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD
     * Get paginated pricing rules data
=======
     * Get paginated data
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
     */
    public function getData(Request $request)
    {
        try {
<<<<<<< HEAD
            $query = DB::table('flexible_pricing_rules as fpr')
                ->leftJoin('room_types as rt', 'fpr.room_type_id', '=', 'rt.room_type_id')
                ->leftJoin('events as e', 'fpr.event_id', '=', 'e.event_id')
                ->leftJoin('holidays as h', 'fpr.holiday_id', '=', 'h.holiday_id')
                ->select(
                    'fpr.rule_id',
                    'fpr.rule_type',
                    'fpr.room_type_id',
                    'rt.name as room_type_name',
                    'fpr.days_of_week',
                    'fpr.event_id',
                    'e.name as event_name',
                    'fpr.holiday_id',
                    'h.name as holiday_name',
                    'fpr.season_name',
                    'fpr.start_date',
                    'fpr.end_date',
                    'fpr.price_adjustment',
                    'fpr.is_active',
                    'fpr.created_at'
                );

            // Apply filters
            if ($request->filled('rule_type')) {
                $query->where('fpr.rule_type', $request->rule_type);
            }

            if ($request->filled('start_date')) {
                $query->where('fpr.start_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->where('fpr.end_date', '<=', $request->end_date);
            }

            if ($request->filled('is_active')) {
                $query->where('fpr.is_active', $request->is_active);
            }

            $data = $query->orderBy('fpr.created_at', 'desc')->paginate(10);

            return response()->json($data);
=======
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
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
<<<<<<< HEAD
     * Get room types for dropdown
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
     * Get events for dropdown
     */
    public function getEvents()
    {
        try {
            $events = Event::select('event_id', 'name', 'start_date', 'end_date')
                ->where('is_active', 1)
                ->orderBy('start_date', 'desc')
                ->get();

            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load events'], 500);
        }
    }

    /**
     * Get holidays for dropdown
     */
    public function getHolidays()
    {
        try {
            $holidays = Holiday::select('holiday_id', 'name', 'start_date', 'end_date')
                ->where('is_active', 1)
                ->orderBy('start_date', 'desc')
                ->get();

            return response()->json($holidays);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load holidays'], 500);
        }
    }

    /**
     * Store new pricing rule
=======
     * Store new event or holiday
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD
                'room_type_id' => $request->room_type_id ?: null,
                'rule_type' => $request->rule_type,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->is_active ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Set specific fields based on rule type
            switch ($request->rule_type) {
                case 'weekend':
                    $data['days_of_week'] = json_encode($request->days_of_week);
                    $data['start_date'] = $request->start_date ?: null;
                    $data['end_date'] = $request->end_date ?: null;
                    break;
                
                case 'event':
                    $data['event_id'] = $request->event_id;
                    // Get event dates
                    $event = Event::find($request->event_id);
                    if ($event) {
                        $data['start_date'] = $event->start_date;
                        $data['end_date'] = $event->end_date;
                    }
                    break;
                
                case 'holiday':
                    $data['holiday_id'] = $request->holiday_id;
                    // Get holiday dates
                    $holiday = Holiday::find($request->holiday_id);
                    if ($holiday) {
                        $data['start_date'] = $holiday->start_date;
                        $data['end_date'] = $holiday->end_date;
                    }
                    break;
                
                case 'season':
                    $data['season_name'] = $request->season_name;
                    $data['start_date'] = $request->start_date;
                    $data['end_date'] = $request->end_date;
                    break;
            }

            DB::table('flexible_pricing_rules')->insert($data);

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
     * Show pricing rule
     */
    public function show($id)
    {
        try {
            $rule = DB::table('flexible_pricing_rules as fpr')
                ->leftJoin('room_types as rt', 'fpr.room_type_id', '=', 'rt.room_type_id')
                ->leftJoin('events as e', 'fpr.event_id', '=', 'e.event_id')
                ->leftJoin('holidays as h', 'fpr.holiday_id', '=', 'h.holiday_id')
                ->select(
                    'fpr.*',
                    'rt.name as room_type_name',
                    'e.name as event_name',
                    'h.name as holiday_name'
                )
                ->where('fpr.rule_id', $id)
                ->first();

            if (!$rule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy quy tắc giá'
                ], 404);
            }

            // Decode JSON fields
            if ($rule->days_of_week) {
                $rule->days_of_week = json_decode($rule->days_of_week);
            }

            return response()->json([
                'success' => true,
                'data' => $rule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải dữ liệu'
            ], 500);
        }
    }

    /**
     * Update pricing rule
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

        try {
            DB::beginTransaction();

            $rule = DB::table('flexible_pricing_rules')->where('rule_id', $id)->first();
            if (!$rule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy quy tắc giá'
                ], 404);
            }

            $data = [
                'room_type_id' => $request->room_type_id ?: null,
                'rule_type' => $request->rule_type,
                'price_adjustment' => $request->price_adjustment,
                'is_active' => $request->is_active ?? 1,
                'updated_at' => now()
            ];

            // Reset all specific fields
            $data['days_of_week'] = null;
            $data['event_id'] = null;
            $data['holiday_id'] = null;
            $data['season_name'] = null;

            // Set specific fields based on rule type
            switch ($request->rule_type) {
                case 'weekend':
                    $data['days_of_week'] = json_encode($request->days_of_week);
                    $data['start_date'] = $request->start_date ?: null;
                    $data['end_date'] = $request->end_date ?: null;
                    break;
                
                case 'event':
                    $data['event_id'] = $request->event_id;
                    // Get event dates
                    $event = Event::find($request->event_id);
                    if ($event) {
                        $data['start_date'] = $event->start_date;
                        $data['end_date'] = $event->end_date;
                    }
                    break;
                
                case 'holiday':
                    $data['holiday_id'] = $request->holiday_id;
                    // Get holiday dates
                    $holiday = Holiday::find($request->holiday_id);
                    if ($holiday) {
                        $data['start_date'] = $holiday->start_date;
                        $data['end_date'] = $holiday->end_date;
                    }
                    break;
                
                case 'season':
                    $data['season_name'] = $request->season_name;
                    $data['start_date'] = $request->start_date;
                    $data['end_date'] = $request->end_date;
                    break;
            }

            DB::table('flexible_pricing_rules')->where('rule_id', $id)->update($data);

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
     * Delete pricing rule
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $rule = DB::table('flexible_pricing_rules')->where('rule_id', $id)->first();
            if (!$rule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy quy tắc giá'
                ], 404);
            }

            DB::table('flexible_pricing_rules')->where('rule_id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa quy tắc giá thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa quy tắc giá'
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

            $rule = DB::table('flexible_pricing_rules')->where('rule_id', $id)->first();
            if (!$rule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy quy tắc giá'
                ], 404);
            }

            DB::table('flexible_pricing_rules')
                ->where('rule_id', $id)
                ->update([
                    'is_active' => $request->is_active,
                    'updated_at' => now()
                ]);

            DB::commit();

            $message = $request->is_active ? 'Kích hoạt quy tắc giá thành công' : 'Tạm dừng quy tắc giá thành công';

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
     * Store new event or holiday
     */
    public function storeEventHoliday(Request $request)
    {
        $validator = $this->validateEventHolidayData($request);

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
=======
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD
        $validator = $this->validateEventHolidayData($request);
=======
        $validator = $this->validateData($request);
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7

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
<<<<<<< HEAD
        $validator = $this->validateEventHolidayData($request);
=======
        $validator = $this->validateData($request);
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7

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
<<<<<<< HEAD
            
            // Check if event is used in pricing rules
            $rulesCount = DB::table('flexible_pricing_rules')->where('event_id', $id)->count();
            if ($rulesCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa sự kiện này vì đang được sử dụng trong quy tắc giá'
                ], 400);
            }

=======
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD
            
            // Check if holiday is used in pricing rules
            $rulesCount = DB::table('flexible_pricing_rules')->where('holiday_id', $id)->count();
            if ($rulesCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa lễ hội này vì đang được sử dụng trong quy tắc giá'
                ], 400);
            }

=======
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD
=======
            // Similar to RoomPriceEventFestivalController export method
            
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD
     * Validate pricing rule data
=======
     * Validate request data
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
     */
    private function validateData(Request $request)
    {
        $rules = [
<<<<<<< HEAD
            'rule_type' => 'required|in:weekend,event,holiday,season',
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'price_adjustment' => 'required|numeric|min:-100|max:1000',
            'is_active' => 'boolean'
        ];

        // Add specific validation based on rule type
        switch ($request->rule_type) {
            case 'weekend':
                $rules['days_of_week'] = 'required|array|min:1';
                $rules['days_of_week.*'] = 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';
                $rules['start_date'] = 'nullable|date';
                $rules['end_date'] = 'nullable|date|after_or_equal:start_date';
                break;
            
            case 'event':
                $rules['event_id'] = 'required|exists:events,event_id';
                break;
            
            case 'holiday':
                $rules['holiday_id'] = 'required|exists:holidays,holiday_id';
                break;
            
            case 'season':
                $rules['season_name'] = 'required|string|max:50';
                $rules['start_date'] = 'required|date';
                $rules['end_date'] = 'required|date|after_or_equal:start_date';
                break;
        }

        $messages = [
            'rule_type.required' => 'Vui lòng chọn loại quy tắc',
            'rule_type.in' => 'Loại quy tắc không hợp lệ',
            'room_type_id.exists' => 'Loại phòng không tồn tại',
            'price_adjustment.required' => 'Vui lòng nhập tỷ lệ điều chỉnh giá',
            'price_adjustment.numeric' => 'Tỷ lệ điều chỉnh giá phải là số',
            'price_adjustment.min' => 'Tỷ lệ điều chỉnh giá không được nhỏ hơn -100%',
            'price_adjustment.max' => 'Tỷ lệ điều chỉnh giá không được lớn hơn 1000%',
            'days_of_week.required' => 'Vui lòng chọn ít nhất một ngày trong tuần',
            'days_of_week.array' => 'Ngày trong tuần phải là một mảng',
            'days_of_week.min' => 'Vui lòng chọn ít nhất một ngày trong tuần',
            'event_id.required' => 'Vui lòng chọn sự kiện',
            'event_id.exists' => 'Sự kiện không tồn tại',
            'holiday_id.required' => 'Vui lòng chọn lễ hội',
            'holiday_id.exists' => 'Lễ hội không tồn tại',
            'season_name.required' => 'Vui lòng nhập tên mùa',
            'season_name.max' => 'Tên mùa không được vượt quá 50 ký tự',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'is_active.boolean' => 'Trạng thái không hợp lệ'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Validate event/holiday data
     */
    private function validateEventHolidayData(Request $request)
    {
        $rules = [
=======
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
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
<<<<<<< HEAD

=======
>>>>>>> f237981f1940ac4c479ab29b040752ad63bfeab7
