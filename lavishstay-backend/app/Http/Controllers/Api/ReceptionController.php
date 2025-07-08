<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
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
            $query = DB::table('room')
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
                    'bed_types.type_name as bed_type_name'
                ]);

            // Apply filters
            if ($request->has('status') && !empty($request->status)) {
                $statuses = is_array($request->status) ? $request->status : [$request->status];
                $query->whereIn('room.status', $statuses);
            }

            if ($request->has('room_type_id') && !empty($request->room_type_id)) {
                $roomTypes = is_array($request->room_type_id) ? $request->room_type_id : [$request->room_type_id];
                $query->whereIn('room.room_type_id', $roomTypes);
            }

            if ($request->has('floor') && !empty($request->floor)) {
                $floors = is_array($request->floor) ? $request->floor : [$request->floor];
                $query->whereIn('room.floor_id', $floors);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('room.name', 'LIKE', "%{$search}%")
                        ->orWhere('room_types.name', 'LIKE', "%{$search}%");
                });
            }

            // Order by floor and room name
            $query->orderBy('room.floor_id', 'asc')
                ->orderBy('room.name', 'asc');

            $rooms = $query->get();

            // Format response
            $formattedRooms = $rooms->map(function ($room) use ($request) {
                // Calculate adjusted price using the same logic as RoomTypeController
                $adjustedPrice = $this->calculateAdjustedPrice($room->room_type_id, $request);
                
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'status' => $room->status,
                    'floor' => $room->floor,
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at,
                    'room_type' => [
                        'id' => $room->room_type_id,
                        'name' => $room->room_type_name,
                        'description' => $room->room_type_description,
                        'base_price' => $room->base_price,
                        'adjusted_price' => $adjustedPrice, // Add adjusted price
                        'room_area' => $room->room_area,
                        'max_guests' => $room->max_guests,
                    ],
                    'bed_type_name' => $room->bed_type_name,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedRooms,
                'message' => 'Rooms retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting rooms: ' . $e->getMessage());
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
            $occupiedRooms = DB::table('room')->where('status', 'occupied')->count();
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
                    'room_types.adjusted_price',
                    'room_types.size',
                    'room_types.max_guests',
                    'bed_types.type_name as bed_type_name',
                    'floors.floor_name',
                    'floors.floor_type'
                ])
                ->where('room.room_id', $roomId)
                ->first();

            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found'
                ], 404);
            }

            // Get current booking if room is occupied
            $currentBooking = null;
            if ($room->status === 'occupied') {
                $currentBooking = DB::table('booking')
                    ->leftJoin('booking_rooms', 'booking.booking_id', '=', 'booking_rooms.booking_id')
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
                        'representatives.name as representative_name',
                        'representatives.phone as representative_phone'
                    ])
                    ->where('booking_rooms.room_id', $roomId)
                    ->where('booking.status', 'confirmed')
                    ->where('booking.check_in_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('booking.check_out_date', '>=', Carbon::now()->format('Y-m-d'))
                    ->first();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'room' => $room,
                    'current_booking' => $currentBooking
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

                // Update room statuses
                DB::table('room')
                    ->where('room_id', $request->old_room_id)
                    ->update(['status' => 'available']);

                DB::table('room')
                    ->where('room_id', $request->new_room_id)
                    ->update(['status' => 'occupied']);

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

                // Update room status
                DB::table('room')
                    ->where('room_id', $request->room_id)
                    ->update([
                        'status' => 'occupied',
                        'updated_at' => Carbon::now()
                    ]);

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

                // Update room status to cleaning
                DB::table('room')
                    ->where('room_id', $request->room_id)
                    ->update([
                        'status' => 'cleaning',
                        'updated_at' => Carbon::now()
                    ]);

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
            $query = DB::table('booking')
                ->leftJoin('booking_rooms', 'booking.booking_id', '=', 'booking_rooms.booking_id')
                ->leftJoin('room', 'booking_rooms.room_id', '=', 'room.room_id')
                ->leftJoin('room_types', 'room.room_type_id', '=', 'room_types.room_type_id')
                ->leftJoin('representatives', 'booking_rooms.representative_id', '=', 'representatives.id')
                ->select([
                    'booking.booking_id',
                    'booking.booking_code',
                    'booking.user_id',
                    'booking.option_id',
                    'booking.check_in_date',
                    'booking.check_out_date',
                    'booking.total_price_vnd',
                    'booking.guest_count',
                    'booking.adults',
                    'booking.children',
                    'booking.children_age',
                    'booking.status',
                    'booking.quantity',
                    'booking.created_at',
                    'booking.updated_at',
                    'booking.guest_name',
                    'booking.guest_email',
                    'booking.guest_phone',
                    'booking_rooms.room_id',
                    'room.name as room_name',
                    'room.floor_id as room_floor',
                    'room_types.name as room_type_name',
                    'room_types.base_price as room_type_price',
                    'room_types.max_guests as room_type_max_guests',
                    'representatives.full_name as representative_name'
                ]);

            // Apply filters
            if ($request->has('guest_name') && !empty($request->guest_name)) {
                $query->where('booking.guest_name', 'LIKE', '%' . $request->guest_name . '%');
            }

            if ($request->has('booking_code') && !empty($request->booking_code)) {
                $query->where('booking.booking_code', 'LIKE', '%' . $request->booking_code . '%');
            }

            // Lưu ý: Bảng booking không có trường payment_status theo schema
            // Cần thêm bảng payment để lấy thông tin thanh toán
            if ($request->has('payment_status') && !empty($request->payment_status)) {
                $query->leftJoin('payment', 'booking.booking_id', '=', 'payment.booking_id')
                    ->where('payment.status', $request->payment_status);
            }

            if ($request->has('booking_status') && !empty($request->booking_status)) {
                $query->where('booking.status', $request->booking_status);
            }

            if ($request->has('date_range') && is_array($request->date_range) && count($request->date_range) == 2) {
                $query->whereBetween('booking.check_in_date', $request->date_range);
            }

            if ($request->has('room_number') && !empty($request->room_number)) {
                $query->where('room.name', 'LIKE', '%' . $request->room_number . '%');
            }

            // Order by creation date (newest first)
            $query->orderBy('booking.created_at', 'desc');

            $bookings = $query->paginate($request->get('per_page', 20));

            // Transform data to return actual schema fields
            $transformedBookings = [];
            foreach ($bookings->items() as $booking) {
                // Lấy thông tin payment status từ bảng payment
                $paymentInfo = DB::table('payment')
                    ->where('booking_id', $booking->booking_id)
                    ->latest()
                    ->first();

                $transformedBookings[] = [
                    'booking_id' => $booking->booking_id,
                    'booking_code' => $booking->booking_code,
                    'user_id' => $booking->user_id,
                    'option_id' => $booking->option_id,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'total_price_vnd' => $booking->total_price_vnd,
                    'guest_count' => $booking->guest_count,
                    'adults' => (int)($booking->adults ?? 1), // Ensure integer
                    'children' => (int)($booking->children ?? 0), // Ensure integer
                    'children_age' => $booking->children_age ,
                    'status' => $booking->status,
                    'quantity' => $booking->quantity,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                    'guest_name' => $booking->guest_name,
                    'guest_email' => $booking->guest_email,
                    'guest_phone' => $booking->guest_phone,
                    'room_id' => $booking->room_id,
                    
                    // Compatibility fields for frontend
                    'id' => $booking->booking_id,
                    'total_amount' => $booking->total_price_vnd,
                    'booking_status' => $booking->status,
                    'payment_status' => $paymentInfo ? $paymentInfo->status : 'pending',
                    
                    'room' => $booking->room_id ? [
                        'id' => $booking->room_id,
                        'name' => $booking->room_name,
                        'floor' => $booking->room_floor,
                        // Flatten room_type data to avoid nested objects that cause Ant Design issues
                        'room_type_name' => $booking->room_type_name,
                        'room_type_price' => $booking->room_type_price,
                        'room_type_max_guests' => $booking->room_type_max_guests
                    ] : null
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $transformedBookings,
                'total' => $bookings->total(),
                'per_page' => $bookings->perPage(),
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
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

                if ($bookingRoom) {
                    // Update room status based on booking status
                    if ($request->status === 'confirmed') {
                        DB::table('room')
                            ->where('room_id', $bookingRoom->room_id)
                            ->update(['status' => 'occupied']);
                    } elseif ($request->status === 'completed') {
                        DB::table('room')
                            ->where('room_id', $bookingRoom->room_id)
                            ->update(['status' => 'cleaning']);
                    } elseif ($request->status === 'cancelled') {
                        DB::table('room')
                            ->where('room_id', $bookingRoom->room_id)
                            ->update(['status' => 'available']);
                    }
                }

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

            // Get all rooms for this booking
            $bookingRooms = DB::table('booking_rooms')
                ->leftJoin('room', 'booking_rooms.room_id', '=', 'room.room_id')
                ->leftJoin('room_types', 'room.room_type_id', '=', 'room_types.room_type_id')
                ->leftJoin('representatives', 'booking_rooms.representative_id', '=', 'representatives.id')
                ->where('booking_rooms.booking_id', $bookingId)
                ->select([
                    'booking_rooms.id as booking_room_id',
                    'booking_rooms.room_id',
                    'booking_rooms.price_per_night',
                    'booking_rooms.nights',
                    'booking_rooms.total_price',
                    'booking_rooms.check_in_date',
                    'booking_rooms.check_out_date',
                    'room.name as room_name',
                    'room.floor_id as room_floor',
                    'room.status as room_status',
                    'room_types.name as room_type_name',
                    'room_types.base_price as room_type_price',
                    'room_types.max_guests as room_type_max_guests',
                    'representatives.full_name as representative_name'
                ])
                ->get();

            $transformedBookingRooms = [];
            foreach ($bookingRooms as $room) {
                $transformedBookingRooms[] = [
                    'booking_room_id' => $room->booking_room_id,
                    'room_id' => $room->room_id,
                    'room_name' => $room->room_name,
                    'room_floor' => $room->room_floor,
                    'room_status' => $room->room_status,
                    'room_type_name' => $room->room_type_name,
                    'room_type_price' => $room->room_type_price,
                    'max_guests' => $room->room_type_max_guests,
                    'price_per_night' => $room->price_per_night,
                    'nights' => $room->nights,
                    'total_price' => $room->total_price,
                    'check_in_date' => $room->check_in_date,
                    'check_out_date' => $room->check_out_date,
                    'representative_name' => $room->representative_name
                ];
            }

            $bookingDetails = [
                'id' => $booking->booking_id,
                'booking_code' => $booking->booking_code,
                'guest_name' => $booking->guest_name,
                'guest_email' => $booking->guest_email,
                'guest_phone' => $booking->guest_phone,
                'guest_count' => $booking->guest_count,
                'adults' => $booking->adults ?? 1,
                'children' => $booking->children ?? 0,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'total_price_vnd' => $booking->total_price_vnd,
                'status' => $booking->status,
                'quantity' => $booking->quantity,
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at,
                'payment_status' => $paymentInfo ? $paymentInfo->status : 'pending',
                'payment_type' => $paymentInfo ? $paymentInfo->payment_type : null,
                'booking_rooms' => $transformedBookingRooms
            ];

            return response()->json([
                'success' => true,
                'data' => $bookingDetails
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching booking details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching booking details',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Create a new booking with multiple rooms and representatives
     */
    public function createBooking(Request $request): JsonResponse
    {
        try {
            // Log the incoming request for debugging
            Log::info('Incoming booking request:', $request->all());

            $validator = Validator::make($request->all(), [
                'rooms' => 'required|array|min:1',
                'rooms.*.id' => 'required|string',
                'rooms.*.name' => 'required|string',
                'rooms.*.room_type' => 'required|array',
                'rooms.*.room_type.adjusted_price' => 'required|numeric',
                'representatives' => 'required|array',
                'checkInDate' => 'required|date',
                'checkOutDate' => 'required|date|after:checkInDate',
                'guestCount' => 'required|integer|min:1',
                'subtotal' => 'required|numeric',
                'representativeMode' => 'required|in:individual,all',
                'bookingData' => 'sometimes|array',
                'bookingData.adults' => 'sometimes|integer|min:1',
                'bookingData.children' => 'sometimes|array',
                'bookingData.children.*.age' => 'sometimes|integer|min:0|max:12',
                'bookingData.notes' => 'sometimes|string|nullable'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Generate booking code
                $bookingCode = 'LVS' . date('YmdHis') . rand(100, 999);
                
                // Get main representative (for 'all' mode use 'all' key, for individual use first room)
                $mainRepresentative = $request->representativeMode === 'all' 
                    ? $request->representatives['all'] 
                    : reset($request->representatives);

                // Get booking data if available
                $bookingData = $request->bookingData ?? null;
                $adults = $bookingData['adults'] ?? 1;
                $children = $bookingData['children'] ?? [];
                $notes = $bookingData['notes'] ?? null;

                // Prepare children ages array
                $childrenAges = [];
                if ($bookingData && isset($bookingData['children']) && is_array($bookingData['children'])) {
                    foreach ($bookingData['children'] as $child) {
                        if (isset($child['age'])) {
                            $childrenAges[] = $child['age'];
                        }
                    }
                }
                
                // Debug logging
                Log::info('Quick booking creation - childrenAges data:', [
                    'bookingData' => $bookingData,
                    'childrenAges' => $childrenAges,
                    'childrenAges_json' => json_encode($childrenAges),
                    'childrenAges_isEmpty' => empty($childrenAges)
                ]);

                // Create main booking record
                $bookingId = DB::table('booking')->insertGetId([
                    'booking_code' => $bookingCode,
                    'guest_name' => $mainRepresentative['fullName'],
                    'guest_email' => $mainRepresentative['email'],
                    'guest_phone' => $mainRepresentative['phoneNumber'],
                    'guest_count' => $request->guestCount,
                    'check_in_date' => $request->checkInDate,
                    'check_out_date' => $request->checkOutDate,
                    'total_price_vnd' => $request->subtotal,
                    'status' => 'confirmed', // Set to confirmed since reception is creating it
                    'quantity' => count($request->rooms),
                    'adults' => $adults,
                    'children' => count($childrenAges),
                    'children_age' => !empty($childrenAges) ? implode(',', $childrenAges) : '', // Store as comma-separated string
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                // Create booking room records
                foreach ($request->rooms as $room) {
                    $representative = $request->representativeMode === 'all' 
                        ? $request->representatives['all'] 
                        : $request->representatives[$room['id']];

                    $nights = Carbon::parse($request->checkOutDate)->diffInDays(Carbon::parse($request->checkInDate));
                    $totalPrice = $room['room_type']['adjusted_price'] * $nights;

                    // First insert representative to get the ID
                    $representativeId = DB::table('representatives')->insertGetId([
                        'booking_id' => $bookingId,
                        'booking_code' => $bookingCode,
                        'room_id' => intval($room['id']), // Convert string to int
                        'full_name' => $representative['fullName'],
                        'phone_number' => $representative['phoneNumber'],
                        'email' => $representative['email'],
                        'id_card' => $representative['idCard'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    // Then insert into booking_rooms table with representative_id
                    DB::table('booking_rooms')->insert([
                        'booking_id' => $bookingId,
                        'booking_code' => $bookingCode,
                        'room_id' => intval($room['id']), // Convert string to int
                        'representative_id' => $representativeId,
                        'price_per_night' => $room['room_type']['adjusted_price'],
                        'nights' => $nights,
                        'total_price' => $totalPrice,
                        'check_in_date' => $request->checkInDate,
                        'check_out_date' => $request->checkOutDate,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }

                // Update room status to 'deposited'
                $roomIds = array_map('intval', array_column($request->rooms, 'id')); // Convert to int
                DB::table('room')
                    ->whereIn('room_id', $roomIds)
                    ->update(['status' => 'deposited', 'updated_at' => Carbon::now()]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Booking created successfully',
                    'booking_code' => $bookingCode,
                    'booking_id' => $bookingId,
                    'payment_content' => "LAVISHSTAY_$bookingCode"
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating booking',
                'error' => $e->getMessage()
            ], 500);
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