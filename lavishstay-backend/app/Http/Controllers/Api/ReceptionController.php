<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use App\Models\BookingRoomChildren;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReceptionController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }
    
   /**
     * Get all rooms with filters for room management dashboard
     */
    public function getRooms(Request $request): JsonResponse
    {
        try {
            $today = Carbon::now()->toDateString();

            // Truy vấn tất cả phòng
            $baseRooms = DB::table('room')
                ->leftJoin('room_types', 'room.room_type_id', '=', 'room_types.room_type_id')
                ->leftJoin('bed_types', 'room.bed_type_fixed', '=', 'bed_types.id')
                ->select([
                    'room.room_id as id',
                    'room.name',
                    'room.status',
                    'room.floor_id as floor',
                    'room.created_at',
                    'room.updated_at',
                    'room_types.room_type_id',
                    'room_types.name as room_type_name',
                    'room_types.description as room_type_description',
                    'room_types.base_price',
                    'room_types.room_area',
                    'room_types.max_guests',
                    'bed_types.type_name as bed_type_name',
                ]);

            // Apply filters
            if ($request->has('status') && !empty($request->status)) {
                $statuses = is_array($request->status) ? $request->status : [$request->status];
                $baseRooms->whereIn('room.status', $statuses);
            }
            if ($request->has('room_type_id') && !empty($request->room_type_id)) {
                $roomTypes = is_array($request->room_type_id) ? $request->room_type_id : [$request->room_type_id];
                $baseRooms->whereIn('room.room_type_id', $roomTypes);
            }
            if ($request->has('floor') && !empty($request->floor)) {
                $floors = is_array($request->floor) ? $request->floor : [$request->floor];
                $baseRooms->whereIn('room.floor_id', $floors);
            }
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $baseRooms->where(function ($q) use ($search) {
                    $q->where('room.name', 'LIKE', "%{$search}%")
                      ->orWhere('room_types.name', 'LIKE', "%{$search}%");
                });
            }

            $baseRooms->orderBy('room.floor_id', 'asc')->orderBy('room.name', 'asc');
            $rooms = $baseRooms->get();

            // Lấy booking hiện tại cho từng phòng
            $roomIds = $rooms->pluck('id')->toArray();
            $bookings = DB::table('booking_rooms as br')
                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                ->select(
                    'br.room_id',
                    'b.guest_name',
                    'b.check_in_date',
                    'b.check_out_date',
                    'br.adults',
                    'br.children'
                )
                ->whereIn('br.room_id', $roomIds)
                ->where('b.check_in_date', '<=', $today)
                ->where('b.check_out_date', '>=', $today)
                ->get();

            // Map booking theo room_id
            $bookingMap = [];
            foreach ($bookings as $booking) {
                $bookingMap[$booking->room_id] = [
                    'guest_name' => $booking->guest_name,
                    'check_in' => $booking->check_in_date,
                    'check_out' => $booking->check_out_date,
                    'adults' => $booking->adults,
                    'children' => $booking->children,
                    'total_guests' => ($booking->adults ?? 0) + ($booking->children ?? 0)
                ];
            }

            $formattedRooms = $rooms->map(function ($room) use ($request, $bookingMap) {
                $adjustedPrice = $this->calculateAdjustedPrice($room->room_type_id, $request);
                $bookingInfo = $bookingMap[$room->id] ?? null;
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'status' => $room->status,
                    'floor' => $room->floor,
                    'booking_info' => $bookingInfo,
                    'room_type' => [
                        'id' => $room->room_type_id,
                        'name' => $room->room_type_name,
                        'description' => $room->room_type_description,
                        'base_price' => $room->base_price,
                        'adjusted_price' => $adjustedPrice,
                        'room_area' => $room->room_area,
                        'max_guests' => $room->max_guests,
                    ],
                    'bed_type_name' => $room->bed_type_name,
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at,
                ];
            });
            Log::info('Retrieved rooms for management dashboard', [
                'count' => $formattedRooms->count(),
                'filters' => $request->all()
            ]);
            return response()->json([
                'success' => true,
                'data' => $formattedRooms,
                'message' => 'Rooms retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting rooms: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving rooms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room statistics for dashboard
     */
    public function getRoomStatistics(Request $request): JsonResponse
    {
        try {
            $date = $request->get('date', Carbon::now()->format('Y-m-d'));
            
            // Get room count by status
            $statusStats = DB::table('room')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Get occupancy rate
            $totalRooms = DB::table('room')->count();
            // $occupiedRooms = DB::table('room')->where('status', 'occupied')->count();
            $availableRooms = DB::table('room')->where('status', 'available')->count();
            $cleaningRooms = DB::table('room')->where('status', 'cleaning')->count();
            $maintenanceRooms = DB::table('room')->where('status', 'maintenance')->count();

            $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

            // Get room stats by floor
            $floorStats = DB::table('room')
                ->select([
                    'room.floor_id',
                    'room.status',
                    DB::raw('count(*) as count')
                ])
                ->groupBy('room.floor_id', 'room.status')
                ->orderBy('room.floor_id')
                ->get();

            $floorStatsFormatted = [];
            foreach ($floorStats as $stat) {
                $floorId = $stat->floor_id;
                if (!isset($floorStatsFormatted[$floorId])) {
                    $floorStatsFormatted[$floorId] = [
                        'floor_id' => $floorId,
                        'floor_name' => "Tầng {$floorId}",
                        'available' => 0,
                        'occupied' => 0,
                        'cleaning' => 0,
                        'maintenance' => 0,
                        'total' => 0
                    ];
                }
                $floorStatsFormatted[$floorId][$stat->status] = $stat->count;
                $floorStatsFormatted[$floorId]['total'] += $stat->count;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'total_rooms' => $totalRooms,
                    'available_rooms' => $availableRooms,
                    'occupied_rooms' => $occupiedRooms,
                    'cleaning_rooms' => $cleaningRooms,
                    'maintenance_rooms' => $maintenanceRooms,
                    'occupancy_rate' => round($occupancyRate, 2),
                    'status_breakdown' => $statusStats,
                    'floor_breakdown' => array_values($floorStatsFormatted)
                ],
                'message' => 'Room statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting room statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving room statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update room status
     */
    public function updateRoomStatus(Request $request, $roomId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:available,occupied,cleaning,maintenance,deposited,no_show,check_in,check_out'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $room = DB::table('room')->where('room_id', $roomId)->first();
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found'
                ], 404);
            }

            DB::table('room')
                ->where('room_id', $roomId)
                ->update([
                    'status' => $request->status,
                    'updated_at' => Carbon::now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Room status updated successfully',
                'data' => [
                    'room_id' => $roomId,
                    'status' => $request->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating room status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating room status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room details with current booking info
     */
    public function getRoomDetails($roomId): JsonResponse
    {
        try {
            $room = DB::table('room')
                ->leftJoin('room_types', 'room.room_type_id', '=', 'room_types.room_type_id')
                ->leftJoin('bed_types', 'room.bed_type_fixed', '=', 'bed_types.id')
                ->leftJoin('floors', 'room.floor_id', '=', 'floors.floor_number')
                ->select([
                    'room.*',
                    'room_types.name as room_type_name',
                    'room_types.description as room_type_description',
                    'room_types.base_price',
                    'room_types.room_area',
                    'room_types.max_guests',
                    'bed_types.type_name as bed_type_name',
                    'floors.floor_name',
                    'floors.floor_type'
                ])
                ->where('room.room_id', $roomId)
                ->first();

            if (!$room) {
                return response()->json(['success' => false, 'message' => 'Room not found'], 404);
            }

            // Lấy toàn bộ lịch sử đặt phòng của phòng này
            $bookingHistory = DB::table('booking_rooms as br')
                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                ->leftJoin('representatives as rep', 'br.representative_id', '=', 'rep.id')
                ->where('br.room_id', $roomId)
                ->select([
                    'b.booking_id',
                    'b.booking_code',
                    'b.guest_name',
                    'b.check_in_date',
                    'b.check_out_date',
                    'b.status as booking_status',
                    'br.adults',
                    'br.children',
                    'rep.full_name as representative_name'
                ])
                ->orderBy('b.check_in_date', 'desc') // Sắp xếp mới nhất trước
                ->get();

            // Tìm đơn đặt phòng hiện tại từ lịch sử
            $today = Carbon::now();
            $currentBooking = $bookingHistory->first(function ($booking) use ($today) {
                return $today->between(Carbon::parse($booking->check_in_date), Carbon::parse($booking->check_out_date));
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'room' => $room,
                    'current_booking' => $currentBooking,
                    'booking_history' => $bookingHistory
                ],
                'message' => 'Room details retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting room details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving room details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room bookings for timeline view
     */
    public function getRoomBookings(Request $request): JsonResponse
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->addDays(30)->format('Y-m-d'));

            $bookings = DB::table('booking')
                ->join('booking_rooms', 'booking.booking_id', '=', 'booking_rooms.booking_id')
                ->leftJoin('room', 'booking_rooms.room_id', '=', 'room.room_id')
                ->leftJoin('representatives', 'booking_rooms.representative_id', '=', 'representatives.id')
                ->select([
                    'booking.booking_id',
                    'booking.booking_code',
                    'booking.guest_name',
                    'booking.guest_email',
                    'booking.guest_phone',
                    'booking.check_in_date',
                    'booking.check_out_date',
                    'booking.guest_count',
                    'booking.status as booking_status',
                    'booking_rooms.room_id',
                    'room.name as room_name',
                    'room.floor_id',
                    'representatives.name as representative_name'
                ])
                ->where('booking.status', 'confirmed')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('booking.check_in_date', [$startDate, $endDate])
                        ->orWhereBetween('booking.check_out_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('booking.check_in_date', '<=', $startDate)
                                ->where('booking.check_out_date', '>=', $endDate);
                        });
                })
                ->orderBy('booking.check_in_date')
                ->get();

            // Format for FullCalendar
            $events = $bookings->map(function ($booking) {
                return [
                    'id' => $booking->booking_id,
                    'title' => $booking->guest_name ?: 'Khách hàng',
                    'start' => $booking->check_in_date,
                    'end' => $booking->check_out_date,
                    'resourceId' => $booking->room_id,
                    'extendedProps' => [
                        'booking_code' => $booking->booking_code,
                        'guest_name' => $booking->guest_name,
                        'guest_email' => $booking->guest_email,
                        'guest_phone' => $booking->guest_phone,
                        'guest_count' => $booking->guest_count,
                        'room_name' => $booking->room_name,
                        'floor_id' => $booking->floor_id,
                        'representative_name' => $booking->representative_name,
                        'booking_status' => $booking->booking_status
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $events,
                'message' => 'Room bookings retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting room bookings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving room bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transfer booking to another room
     */
    public function transferBooking(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|integer',
                'old_room_id' => 'required|integer',
                'new_room_id' => 'required|integer',
                'reason' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Check if booking exists
                $booking = DB::table('booking')->where('booking_id', $request->booking_id)->first();
                if (!$booking) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking not found'
                    ], 404);
                }

                // Check if new room is available
                $newRoom = DB::table('room')->where('room_id', $request->new_room_id)->first();
                if (!$newRoom || $newRoom->status !== 'available') {
                    return response()->json([
                        'success' => false,
                        'message' => 'New room is not available'
                    ], 400);
                }

                // Update booking_rooms table
                DB::table('booking_rooms')
                    ->where('booking_id', $request->booking_id)
                    ->where('room_id', $request->old_room_id)
                    ->update(['room_id' => $request->new_room_id]);
                // Đã xoá đoạn update status phòng để tránh lỗi SQL khi giá trị không hợp lệ
                // Log the transfer
                DB::table('room_transfers')->insert([
                    'booking_id' => $request->booking_id,
                    'old_room_id' => $request->old_room_id,
                    'new_room_id' => $request->new_room_id,
                    'reason' => $request->reason,
                    'created_at' => Carbon::now()
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Booking transferred successfully',
                    'data' => [
                        'booking_id' => $request->booking_id,
                        'old_room_id' => $request->old_room_id,
                        'new_room_id' => $request->new_room_id
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error transferring booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error transferring booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get floors for filtering
     */
    public function getFloors(): JsonResponse
    {
        try {
            $floors = DB::table('floors')
                ->select('floor_number as id', 'floor_name as name', 'floor_type', 'description')
                ->where('is_active', 1)
                ->orderBy('floor_number')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $floors,
                'message' => 'Floors retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting floors: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving floors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room types for filtering
     */
    public function getRoomTypes(): JsonResponse
    {
        try {
            $roomTypes = DB::table('room_types')
                ->select([
                    'room_type_id as id',
                    'name',
                    'description',
                    'base_price',
                    'room_area',
                    'max_guests'
                ])
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $roomTypes,
                'message' => 'Room types retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting room types: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving room types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check-in guest
     */
    public function checkIn(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|integer',
                'room_id' => 'required|integer',
                'actual_check_in_time' => 'nullable|date_format:Y-m-d H:i:s'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Update booking status
                DB::table('booking')
                    ->where('booking_id', $request->booking_id)
                    ->update([
                        'status' => 'confirmed',
                        'updated_at' => Carbon::now()
                    ]);
                // Đã xoá đoạn update status phòng để tránh lỗi SQL khi giá trị không hợp lệ
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Check-in completed successfully',
                    'data' => [
                        'booking_id' => $request->booking_id,
                        'room_id' => $request->room_id,
                        'check_in_time' => $request->actual_check_in_time ?: Carbon::now()->format('Y-m-d H:i:s')
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error checking in: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing check-in',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check-out guest
     */
    public function checkOut(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|integer',
                'room_id' => 'required|integer',
                'actual_check_out_time' => 'nullable|date_format:Y-m-d H:i:s'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Update booking status
                DB::table('booking')
                    ->where('booking_id', $request->booking_id)
                    ->update([
                        'status' => 'completed',
                        'updated_at' => Carbon::now()
                    ]);
                // Đã xoá đoạn update status phòng để tránh lỗi SQL khi giá trị không hợp lệ
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Check-out completed successfully',
                    'data' => [
                        'booking_id' => $request->booking_id,
                        'room_id' => $request->room_id,
                        'check_out_time' => $request->actual_check_out_time ?: Carbon::now()->format('Y-m-d H:i:s')
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error checking out: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error processing check-out',
                'error' => $e->getMessage()
            ], 500);
        }
    }







    /**
     * Get all bookings with filters for booking management
     */
    public function getBookings(Request $request): JsonResponse
    {
        try {
                       
            $query = DB::table('booking as b')
                ->leftJoin('payment as p', 'b.booking_id', '=', 'p.booking_id')
                ->leftJoin('booking_rooms as br', 'b.booking_id', '=', 'br.booking_id')
                ->leftJoin('room as r', 'br.room_id', '=', 'r.room_id')
                ->leftJoin('room_types as rt', 'r.room_type_id', '=', 'rt.room_type_id')
                ->leftJoin('representatives as rep', 'b.booking_id', '=', 'rep.booking_id')
                ->select([
                    'b.booking_id',
                    'b.booking_code',
                    'b.guest_name',
                    'b.guest_email',
                    'b.guest_phone',
                    'b.check_in_date',
                    'b.check_out_date',
                    'b.total_price_vnd',
                    'b.guest_count',
                    'b.status as booking_status',
                    'b.notes',
                    'b.created_at',
                    'b.updated_at',
                    'p.amount_vnd as payment_amount',
                    'p.payment_type',
                    'p.status as payment_status',
                    'p.transaction_id',
                    DB::raw('COUNT(br.id) as total_rooms'),
                    DB::raw('GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ", ") as room_names'),
                    DB::raw('GROUP_CONCAT(DISTINCT rt.name ORDER BY rt.name SEPARATOR ", ") as room_type_names'),
                    DB::raw('COALESCE(SUM(br.adults), 0) as total_adults'),
                    DB::raw('COALESCE(SUM(br.children), 0) as total_children'),
                    // hiển thị tên gói option_name của booking_rooms
                    DB::raw('GROUP_CONCAT(DISTINCT br.option_name ORDER BY br.option_name SEPARATOR ", ") as option_names'),
                    
                    'rep.full_name as representative_name',
                    'rep.phone_number as representative_phone',
                    'rep.email as representative_email'
                ])
                ->groupBy([
                    'b.booking_id', 'b.booking_code', 'b.guest_name', 'b.guest_email', 'b.guest_phone',
                    'b.check_in_date', 'b.check_out_date', 'b.total_price_vnd',
                    'b.status', 'b.notes', 'b.created_at', 'b.updated_at',
                    'p.amount_vnd', 'p.payment_type', 'p.status', 'p.transaction_id',
                    'rep.full_name', 'rep.phone_number', 'rep.email'
                ]);

            // Apply filters
            if ($request->has('guest_name') && !empty($request->guest_name)) {
                $query->where('b.guest_name', 'LIKE', '%' . $request->guest_name . '%');
            }

            if ($request->has('booking_code') && !empty($request->booking_code)) {
                $query->where('b.booking_code', 'LIKE', '%' . $request->booking_code . '%');
            }

            if ($request->has('payment_status') && !empty($request->payment_status)) {
                $query->where('p.status', $request->payment_status);
            }

            if ($request->has('booking_status') && !empty($request->booking_status)) {
                $query->where('b.status', $request->booking_status);
            }

            if ($request->has('date_range') && is_array($request->date_range) && count($request->date_range) == 2) {
                $query->whereBetween('b.check_in_date', $request->date_range);
            }

            if ($request->has('room_number') && !empty($request->room_number)) {
                $query->where('r.name', 'LIKE', '%' . $request->room_number . '%');
            }

            // Order by creation date (newest first)
            $query->orderBy('b.created_at', 'desc');

$bookings = $query->paginate($request->get('per_page', 500));

            // Transform data for frontend
            $transformedBookings = [];
            foreach ($bookings->items() as $booking) {
                // Get children ages from booking_room_children table
                $allChildrenAges = DB::table('booking_room_children')
                    ->join('booking_rooms', 'booking_room_children.booking_room_id', '=', 'booking_rooms.id')
                    ->where('booking_rooms.booking_id', $booking->booking_id)
                    ->orderBy('booking_room_children.booking_room_id')
                    ->orderBy('booking_room_children.child_index')
                    ->pluck('age')
                    ->toArray();
                // list người lớn
                
                $transformedBookings[] = [
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'guest_name' => $booking->guest_name,
                    'guest_email' => $booking->guest_email,
                    'guest_phone' => $booking->guest_phone,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'total_price_vnd' => (float) $booking->total_price_vnd,
                    'guest_count' => (int) $booking->guest_count, // Sửa từ guest_count thành total_guests
                    // hiển thị số lượng người lớn và trẻ em từ booking_rooms
                    'adults' => (int) $booking->total_adults,
                    'children' => (int) $booking->total_children,
                    'children_age' => $allChildrenAges, // Lấy danh sách độ tuổi
                    'status' => $booking->booking_status,
                    'notes' => $booking->notes,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                    'total_rooms' => (int) $booking->total_rooms,
                    'room_names' => $booking->room_names,
                    'room_type_names' => $booking->room_type_names,
                    'payment_status' => $booking->payment_status ?: 'pending',
                    'payment_type' => $booking->payment_type,
                    'payment_amount' => (float) ($booking->payment_amount ?: 0),
                    'transaction_id' => $booking->transaction_id,
                    'representative_name' => $booking->representative_name,
                    'representative_phone' => $booking->representative_phone,
                    'representative_email' => $booking->representative_email,
                    'option_names' => $booking->option_names, // Hiển thị tên gói option_name của booking_rooms
                    // Compatibility fields for frontend
                    'id' => $booking->booking_id,
                    'total_amount' => (float) $booking->total_price_vnd,
                    'booking_status' => $booking->booking_status,
                ];
            }
            Log::info('Bookings retrieved successfully', ['data' => $transformedBookings]);
            return response()->json([
                'success' => true,
                'message' => 'Bookings retrieved successfully',
                'data' => [
                    'data' => $transformedBookings,
                    'current_page' => $bookings->currentPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                    'last_page' => $bookings->lastPage()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
    * Get booking statistics for dashboard
    */
    public function getBookingStatistics(): JsonResponse
    {
        try {
            $statistics = [
                'total_bookings' => DB::table('booking')->count(),
                'pending_bookings' => DB::table('booking')->where('status', 'pending')->count(),
                'confirmed_bookings' => DB::table('booking')->where('status', 'confirmed')->count(),
                'completed_bookings' => DB::table('booking')->where('status', 'completed')->count(), // Sửa từ checked_in/checked_out
                'cancelled_bookings' => DB::table('booking')->where('status', 'cancelled')->count(),
                'total_revenue' => DB::table('booking')
                    ->join('payment', 'booking.booking_id', '=', 'payment.booking_id')
                    ->where('payment.status', 'completed')
                    ->sum('payment.amount_vnd'),
                'pending_revenue' => DB::table('booking')
                    ->where('status', 'pending')
                    ->sum('total_price_vnd'), // Sửa từ total_amount thành total_price_vnd
                'confirmed_revenue' => DB::table('booking')
                    ->where('status', 'confirmed')
                    ->sum('total_price_vnd') // Sửa từ total_amount thành total_price_vnd
            ];

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching booking statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching booking statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
    * Update booking status
    */
    public function updateBookingStatus(Request $request, $bookingId): JsonResponse
    {
        try {
            // Debug log the incoming request
            Log::info('Update booking status request', [
                'booking_id' => $bookingId,
                'request_data' => $request->all(),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]);

            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,confirmed,completed,cancelled,checked_in,checked_out,no_show'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed for booking status update', [
                    'booking_id' => $bookingId,
                    'request_data' => $request->all(),
                    'errors' => $validator->errors()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $booking = DB::table('booking')->where('booking_id', $bookingId)->first();
                
                if (!$booking) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking not found'
                    ], 404);
                }

                // Update booking status
                DB::table('booking')
                    ->where('booking_id', $bookingId)
                    ->update([
                        'status' => $request->status,
                        'updated_at' => Carbon::now()
                    ]);

                // Get room_id from booking_rooms table
                $bookingRoom = DB::table('booking_rooms')
                    ->where('booking_id', $bookingId)
                    ->first();

                // Đã xoá đoạn update status phòng để tránh lỗi SQL khi giá trị không hợp lệ

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Booking status updated successfully',
                    'data' => [
                        'booking_id' => $bookingId,
                        'status' => $request->status
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error updating booking status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
    * Cancel booking
    */
    public function cancelBooking($bookingId): JsonResponse
    {
        try {
            DB::beginTransaction();

            try {
                $booking = DB::table('booking')->where('booking_id', $bookingId)->first();
                
                if (!$booking) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking not found'
                    ], 404);
                }

                if ($booking->status === 'completed') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot cancel completed booking'
                    ], 400);
                }

                // Update booking status to cancelled
                DB::table('booking')
                    ->where('booking_id', $bookingId)
                    ->update([
                        'status' => 'cancelled',
                        'updated_at' => Carbon::now()
                    ]);

                // Get room_id from booking_rooms table and make room available again
                $bookingRoom = DB::table('booking_rooms')
                    ->where('booking_id', $bookingId)
                    ->first();

                if ($bookingRoom) {
                    DB::table('room')
                        ->where('room_id', $bookingRoom->room_id)
                        ->update(['status' => 'available']);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Booking cancelled successfully',
                    'data' => [
                        'booking_id' => $bookingId,
                        'status' => 'cancelled'
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error cancelling booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }









    /**
    * Get detailed booking information with all rooms
    */
    public function getBookingDetails($bookingId): JsonResponse
    {
        try {
            // Get booking information
            $booking = DB::table('booking')
                ->where('booking_id', $bookingId)
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Get payment information
            $paymentInfo = DB::table('payment')
                ->where('booking_id', $bookingId)
                ->latest()
                ->first();

            // Get all rooms for this booking with detailed information
         
            $bookingRooms = DB::table('booking_rooms')
                ->leftJoin('room', 'booking_rooms.room_id', '=', 'room.room_id')
                ->leftJoin('room_types', 'room.room_type_id', '=', 'room_types.room_type_id')
                ->leftJoin('representatives', 'booking_rooms.representative_id', '=', 'representatives.id')
                ->where('booking_rooms.booking_id', $bookingId)
                ->select([
                    'booking_rooms.id as booking_room_id',
                    'booking_rooms.room_id',
                    // 'booking.room_type_id', // <-- XÓA DÒNG NÀY
                    'booking_rooms.option_name',
                    'booking_rooms.option_price',
                    'booking_rooms.price_per_night',
                    'booking_rooms.nights',
                    'booking_rooms.total_price',
                    'booking_rooms.check_in_date',
                    'booking_rooms.check_out_date',
                    'booking_rooms.adults',
                    'booking_rooms.children',
                    'booking_rooms.representative_id',
                    'room.name as room_name',
                    'room.floor_id as room_floor',
                    'room.status as room_status',
                    'room.bed_type_fixed',
                    'room_types.room_type_id', // <-- LẤY room_type_id TỪ BẢNG room_types
                    'room_types.name as room_type_name',
                    'room_types.description as room_type_description',
                    'room_types.base_price as room_type_price',
                    'room_types.max_guests as room_type_max_guests',
                    'room_types.room_area',
                    'representatives.full_name as representative_name',
                    'representatives.phone_number as representative_phone',
                    'representatives.email as representative_email',
                    'representatives.id_card as representative_id_card'
                ])
                ->get();

            // Get all representatives for this booking (for additional context)
            $allRepresentatives = DB::table('representatives')
                ->where('booking_id', $bookingId)
                ->select([
                    'id',
                    'full_name',
                    'phone_number',
                    'email',
                    'id_card',
                    'room_id'
                ])
                ->get();
                
               

            $transformedBookingRooms = [];
                       
            foreach ($bookingRooms as $room) {
                      $childrenAges = DB::table('booking_room_children')
                    ->where('booking_room_id', $room->booking_room_id)
                    ->orderBy('child_index')
                    ->pluck('age')
                    ->toArray();
                $transformedBookingRooms[] = [
                    'booking_room_id' => $room->booking_room_id,
                    'room_id' => $room->room_id,
                    'room_name' => $room->room_name,
                    'room_floor' => $room->room_floor,
                    'room_status' => $room->room_status,
                    'option_name' => $room->option_name,
                    'option_price' => (float) $room->option_price,
                    'room_type' => [
                        'id' => $room->room_type_id,
                        'name' => $room->room_type_name,
                        'description' => $room->room_type_description,
                        'base_price' => (float) $room->room_type_price,
                        'max_guests' => (int) $room->room_type_max_guests,
                        'room_area' => $room->room_area
                    ],
                    'bed_type' => $room->bed_type_fixed,
                    'price_per_night' => (float) $room->price_per_night,
                    'nights' => (int) $room->nights,
                    'total_price' => (float) $room->total_price,
                    'check_in_date' => $room->check_in_date,
                    'check_out_date' => $room->check_out_date,
                    'adults' => (int) $room->adults,
                    'children' => (int) $room->children,
                    'children_age' => $childrenAges,
                    'representative' => [
                        'id' => $room->representative_id,
                        'name' => $room->representative_name,
                        'phone' => $room->representative_phone,
                        'email' => $room->representative_email,
                        'identity_number' => $room->representative_id_card
                    ]
                ];
            }

            $transformedRepresentatives = [];
            foreach ($allRepresentatives as $rep) {
                $transformedRepresentatives[] = [
                    'id' => $rep->id,
                    'full_name' => $rep->full_name,
                    'phone_number' => $rep->phone_number,
                    'email' => $rep->email,
                    'identity_number' => $rep->id_card,
                    'room_id' => $rep->room_id
                ];
            }
$allChildrenAges = DB::table('booking_room_children')
    ->join('booking_rooms', 'booking_room_children.booking_room_id', '=', 'booking_rooms.id')
    ->where('booking_rooms.booking_id', $bookingId)
    ->orderBy('booking_room_children.booking_room_id')
    ->orderBy('booking_room_children.child_index')
    ->pluck('age')
    ->toArray();

            $bookingDetails = [
                'booking_id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'guest_name' => $booking->guest_name,
                'guest_email' => $booking->guest_email,
                'guest_phone' => $booking->guest_phone,
                'guest_count' => (int) $booking->guest_count,
                'adults' => (int) ($booking->adults ?? 1),
                'children' => (int) ($booking->children ?? 0),
                'children_age' => $allChildrenAges,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'total_price_vnd' => (float) $booking->total_price_vnd,
                'status' => $booking->status,
                'notes' => $booking->notes,
                'room_type_id' => $booking->room_type_id,
                'quantity' => (int) ($booking->quantity ?? count($transformedBookingRooms)),
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at,
                
                // Payment information
                'payment' => $paymentInfo ? [
                    'amount_vnd' => (float) $paymentInfo->amount_vnd,
                    'payment_type' => $paymentInfo->payment_type,
                    'status' => $paymentInfo->status,
                    'transaction_id' => $paymentInfo->transaction_id,
                    'created_at' => $paymentInfo->created_at
                ] : null,
                
                // Room details
                'booking_rooms' => $transformedBookingRooms,
                'total_rooms' => count($transformedBookingRooms),
                
                // All representatives
                'representatives' => $transformedRepresentatives,
                
                // Compatibility fields for frontend
                'id' => $booking->booking_id,
                'payment_status' => $paymentInfo ? $paymentInfo->status : 'pending',
                'payment_type' => $paymentInfo ? $paymentInfo->payment_type : null,
                'total_amount' => (float) $booking->total_price_vnd,
                'booking_status' => $booking->status
            ];  
            
            // Trả về mảng chứa một phần tử duy nhất thay vì object
            $responseData = [$bookingDetails];
            
            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'Booking details retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching booking details: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching booking details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available rooms for a specific booking room entry.
     */

    public function getAssignmentPreview(Request $request, $bookingId): JsonResponse
    {
        try {
            $booking = DB::table('booking')->where('booking_id', $bookingId)->first();
            if (!$booking) {
                return response()->json(['success' => false, 'message' => 'Booking not found.'], 404);
            }
    
            // Lấy danh sách booking_rooms của booking này
            $bookingRooms = DB::table('booking_rooms')
                ->where('booking_id', $bookingId)
                ->select(['id', 'room_id', 'option_name'])
                ->get();
            // Kiểm tra xem có booking_rooms nào không
            Log::info('Booking rooms for booking ID ' . $bookingId, ['booking_rooms' => $bookingRooms]);
            if ($bookingRooms->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No rooms associated with this booking.'], 404);
            }
            Log::error('error in getAssignmentPreview for booking ID ' . $bookingId, ['booking' => $booking]);
            $roomTypeId = $booking->room_type_id;
            $assignmentPreview = [];
            $checkInDate = $booking->check_in_date;
            $checkOutDate = $booking->check_out_date;
    
            // Tìm các phòng bị trùng lịch
            $conflictingRoomIds = DB::table('booking_rooms as br')
                ->join('booking as b', 'br.booking_id', '=', 'b.booking_id')
                ->whereIn('b.status', ['Confirmed', 'Operational'])
                ->where('b.booking_id', '!=', $bookingId)
                ->whereNotNull('br.room_id')
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where('br.check_in_date', '<', $checkOutDate)
                          ->where('br.check_out_date', '>', $checkInDate);
                })
                ->pluck('br.room_id');
    
            // Lấy thông tin loại phòng
            $roomType = DB::table('room_types')->where('room_type_id', $roomTypeId)->first();
            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => "Room type '{$roomTypeId}' not found.",
                    'assignment_options' => []
                ]);
            }
    
            $roomsNeededCount = $bookingRooms->count();
    
            // Lấy danh sách phòng còn trống theo loại phòng
            $availableRooms = DB::table('room')
                ->where('room_type_id', $roomTypeId)
                ->whereNotIn('room_id', $conflictingRoomIds)
                ->orderBy('floor_id', 'asc')
                ->orderBy('name', 'asc')
                ->select(['room_id as id', 'name', 'floor_id as floor', 'room_type_id']) // Sửa lại ở đây
                ->get();
    
            $assignmentPreview[] = [
                'room_type_id' => $roomTypeId,
                'room_type_name' => $roomType->name,
                'rooms_needed' => $roomsNeededCount,
                'available_rooms' => $availableRooms,
                'booking_room_ids' => $bookingRooms->pluck('id')->toArray(),
            ];
    
            return response()->json([
                'success' => true,
                'data' => [
                    'booking_id' => $bookingId,
                    'check_in_date' => $checkInDate,
                    'check_out_date' => $checkOutDate,
                    'assignment_options' => $assignmentPreview,
                ]
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error getting assignment preview: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the assignment preview.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Assign multiple rooms to booking_room entries.
     */
    public function assignMultipleRoomsToBooking(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'assignments' => 'required|array|min:1',
            'assignments.*.booking_room_id' => 'required|integer|exists:booking_rooms,id',
            'assignments.*.room_id' => 'required|integer|exists:room,room_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $assignedCount = 0;
            foreach ($request->assignments as $assignment) {
                // Update booking_rooms table
                DB::table('booking_rooms')
                    ->where('id', $assignment['booking_room_id'])
                    ->update([
                        'room_id' => $assignment['room_id'],
                        'updated_at' => Carbon::now()
                    ]);
                
                // Update the booking status to 'operational' 
                DB::table('booking')
                    ->where('booking_id', $assignment['booking_room_id'])
                    ->update(['status' => 'Operational']);

                $assignedCount++;
            }

            DB::commit();
            Log::info('Successfully assigned multiple rooms to booking', [
                'assignments' => $request->assignments,
                'assigned_count' => $assignedCount
            ]);
            return response()->json(['success' => true, 'message' => "Successfully assigned {$assignedCount} rooms."]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error assigning multiple rooms to booking: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to assign rooms.', 'error' => $e->getMessage()], 500);
        }
    }


    

       public function createBooking(Request $request): JsonResponse
    {
        Log::info('Reception: Create Booking V3 Request Received:', $request->all());

        $validator = Validator::make($request->all(), [
            'booking_details.check_in_date' => 'required|date',
            'booking_details.check_out_date' => 'required|date|after:booking_details.check_in_date',
            'booking_details.total_price' => 'required|numeric|min:0',
            'representative_info' => 'required|array',
            'rooms' => 'required|array|min:1',
            'payment_method' => 'required|string|in:vietqr,pay_at_hotel', // Accept 'pay_at_hotel' from frontend
        ]);

        if ($validator->fails()) {
            Log::error('Reception V3: Booking validation failed', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ.', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $bookingDetails = $request->input('booking_details');
            $representativeInfo = $request->input('representative_info');
            $roomData = $request->input('rooms');
            $paymentMethod = $request->input('payment_method');

            // 1. Create main booking record
            $booking = Booking::create([
                'booking_code' => '', // Will be updated
                'guest_name' => $representativeInfo['details']['fullName'] ?? 'Guest',
                'guest_email' => $representativeInfo['details']['email'] ?? null,
                'guest_phone' => $representativeInfo['details']['phoneNumber'] ?? null,
                'check_in_date' => Carbon::parse($bookingDetails['check_in_date']),
                'check_out_date' => Carbon::parse($bookingDetails['check_out_date']),
                'guest_count' => $bookingDetails['adults'] + count($bookingDetails['children']),
                'total_price_vnd' => $bookingDetails['total_price'],
                'status' => 'pending', // Start as pending
                'notes' => $bookingDetails['notes'] ?? null,
                'room_type_id' => $roomData[0]['room_type_id'] ?? null,
            ]);

            $bookingCode = 'LVS' . $booking->booking_id . now()->format('His');
            $booking->booking_code = $bookingCode;
            $booking->save();

            // 2. Create related records (representatives, booking_rooms, etc.)
            foreach ($roomData as $room) {
                $repDetails = ($representativeInfo['mode'] === 'all')
                    ? $representativeInfo['details']
                    : ($representativeInfo['details'][$room['room_id']] ?? null);

                if (!$repDetails) {
                    throw new \Exception("Missing representative details for room " . $room['room_id']);
                }

                $representativeId = DB::table('representatives')->insertGetId([
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $bookingCode,
                    'room_id' => $room['room_id'] ?? null,
                    'full_name' => $repDetails['fullName'],
                    'phone_number' => $repDetails['phoneNumber'],
                    'email' => $repDetails['email'],
                    'id_card' => $repDetails['idCard'] ?? null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $package = DB::table('room_type_package')->where('package_id', $room['package_id'])->first();
                $nights = Carbon::parse($bookingDetails['check_out_date'])->diffInDays(Carbon::parse($bookingDetails['check_in_date']));

                $bookingRoomId = DB::table('booking_rooms')->insertGetId([
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $bookingCode,
                    'room_id' => $room['room_id'] ?? null,
                    'representative_id' => $representativeId,
                    'option_name' => $package->package_name ?? 'Default Package',
                    'price_per_night' => $package->price_per_room_per_night ?? 0,
                    'nights' => $nights,
                    'total_price' => ($package->price_per_room_per_night ?? 0) * $nights,
                    'check_in_date' => $bookingDetails['check_in_date'],
                    'check_out_date' => $bookingDetails['check_out_date'],
                    'adults' => $room['adults'],
                    'children' => count($room['children']),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                if (!empty($room['children'])) {
                    $childrenData = array_map(function($child, $index) use ($bookingRoomId) {
                        return [
                            'booking_room_id' => $bookingRoomId,
                            'child_index' => $index + 1,
                            'age' => $child['age'],
                        ];
                    }, $room['children'], array_keys($room['children']));
                    DB::table('booking_room_children')->insert($childrenData);
                }
            }

            // 3. Handle status based on payment method
            if ($paymentMethod === 'pay_at_hotel') {
                $checkInDate = Carbon::parse($booking->check_in_date);
                $newStatus = $checkInDate->isToday() ? 'operational' : 'confirmed';
                $booking->status = $newStatus;
                $booking->save();

                // Create a pending payment record for pay_at_hotel
                Payment::create([
                    'booking_id' => $booking->booking_id,
                    'amount_vnd' => $booking->total_price_vnd,
                    'payment_type' => 'at_hotel', // Correct ENUM value
                    'status' => 'pending', // It's pending until they actually pay
                    'transaction_id' => 'RECEPTION_' . $bookingCode,
                ]);

                Log::info('Reception V3: Full booking created for pay_at_hotel.', ['booking_code' => $bookingCode]);

            } else { // For 'vietqr'
                // Create a pending payment record for vietqr
                Payment::create([
                    'booking_id' => $booking->booking_id,
                    'amount_vnd' => $booking->total_price_vnd,
                    'payment_type' => 'vietqr',
                    'status' => 'pending',
                ]);
                Log::info('Reception V3: Temporary booking created for VietQR.', ['booking_code' => $bookingCode]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã xử lý yêu cầu đặt phòng thành công!',
                'booking_id' => $booking->booking_id,
                'booking_code' => $bookingCode,
                'final_status' => $booking->status
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reception V3: Error creating booking: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi khi tạo đặt phòng.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirm a booking after successful online payment.
     */
    public function confirmBooking(Request $request): JsonResponse
    {
        Log::info('Confirm paid booking request received:', $request->all());

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer|exists:booking,booking_id',
            'transaction_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Paid booking confirmation validation failed:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => 'Dữ liệu xác nhận không hợp lệ.', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $bookingId = $request->input('booking_id');
            $booking = Booking::where('booking_id', $bookingId)->lockForUpdate()->first();

            if ($booking->status !== 'pending') {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Đặt phòng này đã được xử lý hoặc không ở trạng thái chờ.', 'current_status' => $booking->status], 409);
            }

            // 1. Update booking status
            $checkInDate = Carbon::parse($booking->check_in_date);
            $newStatus = $checkInDate->isToday() ? 'operational' : 'confirmed';
            $booking->status = $newStatus;
            $booking->updated_at = Carbon::now();
            $booking->save();

            // 2. Update payment status

            DB::commit();

            Log::info('Booking confirmed successfully after payment.', ['booking_code' => $booking->booking_code]);

            return response()->json([
                'success' => true,
                'message' => 'Đặt phòng đã được xác nhận thành công!',
                'booking_code' => $booking->booking_code,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming paid booking: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi khi xác nhận đặt phòng.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Calculate adjusted price for room type based on current date or provided date
     * Same logic as RoomTypeController
     */
    private function calculateAdjustedPrice($roomTypeId, $request)
    {
        try {
            $roomType = DB::table('room_types')->where('room_type_id', $roomTypeId)->first();
            if (!$roomType) {
                return 1200000; // Fallback price
            }

            $basePrice = $roomType->base_price;
            
            // Use provided date or default to tomorrow
            $targetDate = $request->date ?? $request->check_in_date ?? Carbon::now()->addDay()->format('Y-m-d');
            $date = Carbon::parse($targetDate);

            // Calculate night price using PricingService
            $nightPrice = $this->pricingService->calculateNightPrice(
                $roomTypeId,
                $date,
                $basePrice
            );

            return $nightPrice['adjusted_price'] ?? $basePrice;

        } catch (\Exception $e) {
            Log::error('Error calculating adjusted price: ' . $e->getMessage(), [
                'room_type_id' => $roomTypeId,
                'date' => $targetDate ?? 'not provided'
            ]);

            // Fallback to base price
            $roomType = DB::table('room_types')->where('room_type_id', $roomTypeId)->first();
            return $roomType ? $roomType->base_price : 1200000;
        }
    }

}