<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics for rooms and room types
     */
    public function getRoomStatistics(): JsonResponse
    {
        try {
            // Get total room types
            $totalRoomTypes = RoomType::count();
            
            // Get total rooms
            $totalRooms = Room::count();
            
            // Get rooms by status
            $roomsByStatus = Room::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');
            
            // Get available rooms
            $availableRooms = $roomsByStatus['available'] ?? 0;
            $occupiedRooms = $roomsByStatus['occupied'] ?? 0;
            $maintenanceRooms = $roomsByStatus['maintenance'] ?? 0;
            $cleaningRooms = $roomsByStatus['cleaning'] ?? 0;
            
            // Get room types with room count
            $roomTypesWithCount = RoomType::withCount('rooms')
                ->with(['images' => function($query) {
                    $query->where('is_main', true);
                }])
                ->get()
                ->map(function($roomType) {
                    return [
                        'id' => $roomType->room_type_id,
                        'name' => $roomType->name,
                        'room_code' => $roomType->room_code,
                        'description' => $roomType->description,
                        'total_room' => $roomType->total_room,
                        'rooms_count' => $roomType->rooms_count,
                        'main_image' => $roomType->images->first()?->image_path,
                        'available_rooms' => $roomType->rooms->where('status', 'available')->count(),
                        'occupied_rooms' => $roomType->rooms->where('status', 'occupied')->count()
                    ];
                });
            
            // Get price statistics
            $priceStats = Room::selectRaw('
                MIN(base_price_vnd) as min_price,
                MAX(base_price_vnd) as max_price,
                AVG(base_price_vnd) as avg_price
            ')->first();
            
            // Get recent rooms (last 10)
            $recentRooms = Room::with('roomType')
                ->orderBy('room_id', 'desc')
                ->limit(10)
                ->get()
                ->map(function($room) {
                    return [
                        'id' => $room->room_id,
                        'name' => $room->name,
                        'floor' => $room->floor,
                        'status' => $room->status,
                        'base_price_vnd' => $room->base_price_vnd,
                        'room_type' => [
                            'id' => $room->roomType->room_type_id,
                            'name' => $room->roomType->name,
                            'room_code' => $room->roomType->room_code
                        ]
                    ];
                });
            
            // Calculate occupancy rate
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => [
                        'total_room_types' => $totalRoomTypes,
                        'total_rooms' => $totalRooms,
                        'available_rooms' => $availableRooms,
                        'occupied_rooms' => $occupiedRooms,
                        'maintenance_rooms' => $maintenanceRooms,
                        'cleaning_rooms' => $cleaningRooms,
                        'occupancy_rate' => $occupancyRate
                    ],
                    'room_types' => $roomTypesWithCount,
                    'price_statistics' => [
                        'min_price' => $priceStats->min_price ?? 0,
                        'max_price' => $priceStats->max_price ?? 0,
                        'avg_price' => round($priceStats->avg_price ?? 0, 0)
                    ],
                    'recent_rooms' => $recentRooms,
                    'status_distribution' => [
                        'available' => $availableRooms,
                        'occupied' => $occupiedRooms,
                        'maintenance' => $maintenanceRooms,
                        'cleaning' => $cleaningRooms
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thống kê',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get filter options for frontend
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            // Get unique views
            $views = Room::distinct()
                ->whereNotNull('view')
                ->pluck('view')
                ->filter()
                ->sort()
                ->values();
            
            // Get status options
            $statusOptions = [
                'available' => 'Có sẵn',
                'occupied' => 'Đã đặt',
                'maintenance' => 'Bảo trì',
                'cleaning' => 'Đang dọn dẹp'
            ];
            
            // Get room types for filter
            $roomTypes = RoomType::select('room_type_id as id', 'name', 'room_code')
                ->orderBy('name')
                ->get();
            
            // Get price range
            $priceRange = [
                'min' => Room::min('base_price_vnd') ?? 0,
                'max' => Room::max('base_price_vnd') ?? 10000000
            ];
            
            // Get size range
            $sizeRange = [
                'min' => Room::min('size') ?? 0,
                'max' => Room::max('size') ?? 200
            ];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'views' => $views,
                    'status_options' => $statusOptions,
                    'room_types' => $roomTypes,
                    'price_range' => $priceRange,
                    'size_range' => $sizeRange,
                    'sort_options' => [
                        'name' => 'Tên phòng',
                        'base_price_vnd' => 'Giá phòng',
                        'size' => 'Diện tích',
                        'max_guests' => 'Số khách tối đa',
                        'rating' => 'Đánh giá',
                        'floor' => 'Tầng'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy filter options',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
