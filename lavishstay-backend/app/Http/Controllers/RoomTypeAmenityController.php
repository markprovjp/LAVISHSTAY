<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomTypeAmenityController extends Controller
{
    public function index(RoomType $roomType)
    {
        $roomType->load(['amenities' => function($query) {
            $query->withPivot('is_highlighted');
        }, 'images']);
        
        // Get available amenities (not yet assigned to this room type)
        $assignedAmenityIds = $roomType->amenities->pluck('amenity_id')->toArray();
        $availableAmenities = Amenity::whereNotIn('amenity_id', $assignedAmenityIds)
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('admin.room-types.amenities', compact('roomType', 'availableAmenities'));
    }

    public function store(Request $request, RoomType $roomType)
    {
        $request->validate([
            'amenity_ids' => 'required|array',
            'amenity_ids.*' => 'exists:amenities,amenity_id',
            'highlighted_ids' => 'array',
            'highlighted_ids.*' => 'exists:amenities,amenity_id'
        ]);

        try {
            DB::beginTransaction();

            $amenityIds = $request->amenity_ids;
            $highlightedIds = $request->highlighted_ids ?? [];

            // Prepare data for sync
            $syncData = [];
            foreach ($amenityIds as $amenityId) {
                $syncData[$amenityId] = [
                    'is_highlighted' => in_array($amenityId, $highlightedIds),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Attach new amenities
            $roomType->amenities()->attach($syncData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm ' . count($amenityIds) . ' tiện ích thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding amenities: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm tiện ích: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(RoomType $roomType, Amenity $amenity)
    {
        try {
            $detached = $roomType->amenities()->detach($amenity->amenity_id);
            
            if ($detached) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa tiện ích thành công!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiện ích không tồn tại trong loại phòng này!'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Error removing amenity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa tiện ích: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleHighlight(Request $request, RoomType $roomType, Amenity $amenity)
    {
        try {
            // Check if the amenity is attached to this room type
            $pivotRecord = DB::table('room_type_amenities')
                ->where('room_type_id', $roomType->room_type_id)
                ->where('amenity_id', $amenity->amenity_id)
                ->first();

            if (!$pivotRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiện ích không tồn tại trong loại phòng này!'
                ], 404);
            }

            $currentHighlight = (bool) $pivotRecord->is_highlighted;
            $newHighlight = !$currentHighlight;

            // Update the pivot record
            DB::table('room_type_amenities')
                ->where('room_type_id', $roomType->room_type_id)
                ->where('amenity_id', $amenity->amenity_id)
                ->update([
                    'is_highlighted' => $newHighlight,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => $newHighlight ? 'Đã đánh dấu nổi bật!' : 'Đã bỏ đánh dấu nổi bật!',
                'is_highlighted' => $newHighlight
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling highlight: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái nổi bật: ' . $e->getMessage()
            ], 500);
        }
    }

    public function highlightAll(Request $request, RoomType $roomType)
    {
        $request->validate([
            'is_highlighted' => 'required|boolean'
        ]);

        try {
            $isHighlighted = $request->is_highlighted;
            
            // Update all amenities for this room type
            $updated = DB::table('room_type_amenities')
                ->where('room_type_id', $roomType->room_type_id)
                ->update([
                    'is_highlighted' => $isHighlighted,
                    'updated_at' => now()
                ]);

            if ($updated > 0) {
                $message = $isHighlighted 
                    ? "Đã đánh dấu nổi bật {$updated} tiện ích!" 
                    : "Đã bỏ đánh dấu nổi bật {$updated} tiện ích!";

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'updated_count' => $updated
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có tiện ích nào để cập nhật!'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Error highlighting all amenities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật tiện ích: ' . $e->getMessage()
            ], 500);
        }
    }
}
