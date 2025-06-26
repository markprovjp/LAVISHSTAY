<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoomAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomAvailabilityController extends Controller
{
    protected $roomAvailabilityService;

    public function __construct(RoomAvailabilityService $roomAvailabilityService)
    {
        $this->roomAvailabilityService = $roomAvailabilityService;
    }

    public function getAvailableRooms(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'guest_count' => 'required|integer|min:1',
                'room_type_id' => 'nullable|integer|exists:room_types,room_type_id'
            ]);

            $availableRooms = $this->roomAvailabilityService->getAvailableRoomsWithPricing(
                $validated['check_in_date'],
                $validated['check_out_date'],
                $validated['guest_count'],
                $validated['room_type_id'] ?? null
            );

            return response()->json([
                'success' => true,
                'data' => $availableRooms,
                'message' => 'Danh sách phòng trống đã được tải thành công'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tìm kiếm phòng trống',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
