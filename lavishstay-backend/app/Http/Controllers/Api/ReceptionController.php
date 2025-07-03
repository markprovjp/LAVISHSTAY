<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReceptionController extends Controller
{
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
            $formattedRooms = $rooms->map(function ($room) {
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
                'status' => 'required|in:available,occupied,cleaning,maintenance'
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
}
