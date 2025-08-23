<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Booking;
use App\Services\RoomTypeOverviewService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class RoomTypeOverviewController extends Controller
{
    protected $roomTypeOverviewService;

    public function __construct(RoomTypeOverviewService $roomTypeOverviewService)
    {
        $this->roomTypeOverviewService = $roomTypeOverviewService;
    }

    /**
     * Get room types overview for homepage
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function overview(Request $request): JsonResponse
    {
        try {
            // Validate query parameters
            $validator = Validator::make($request->all(), [
                'featured' => 'nullable|boolean',
                'popular' => 'nullable|boolean',
                'min_price' => 'nullable|numeric|min:0',
                'max_price' => 'nullable|numeric|min:0',
                'limit' => 'nullable|integer|min:1|max:50',
                'page' => 'nullable|integer|min:1',
                'locale' => 'nullable|string|in:en,vi',
                'currency' => 'nullable|string|in:VND,USD',
                'check_date' => 'nullable|date_format:Y-m-d'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Extract parameters with defaults
            $filters = [
                'featured' => $request->boolean('featured'),
                'popular' => $request->boolean('popular'),
                'min_price' => $request->input('min_price'),
                'max_price' => $request->input('max_price'),
                'limit' => $request->input('limit', 12),
                'page' => $request->input('page', 1),
                'locale' => $request->input('locale', 'vi'),
                'currency' => $request->input('currency', 'VND'),
                'check_date' => $request->input('check_date', now()->format('Y-m-d'))
            ];

            // Generate cache key
            $cacheKey = 'room_types_overview_' . md5(serialize($filters));

            // Try to get from cache
            $result = Cache::remember($cacheKey, 60, function () use ($filters) {
                return $this->roomTypeOverviewService->getRoomTypesOverview($filters);
            });

            return response()->json([
                'success' => true,
                'meta' => [
                    'total' => $result['total'],
                    'page' => $filters['page'],
                    'per_page' => $filters['limit'],
                    'total_pages' => ceil($result['total'] / $filters['limit'])
                ],
                'data' => $result['data']
            ]);

        } catch (\Exception $e) {
            \Log::error('Room Type Overview API Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching room types',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Clear room types overview cache
     * 
     * @return JsonResponse
     */
    public function clearCache(): JsonResponse
    {
        try {
            // Clear all room type overview cache entries
            $pattern = 'room_types_overview_*';
            $keys = Cache::getStore()->getRedis()->keys($pattern);
            
            if (!empty($keys)) {
                foreach ($keys as $key) {
                    Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Room types overview cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
