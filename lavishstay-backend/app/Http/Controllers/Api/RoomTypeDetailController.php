<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoomTypeDetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class RoomTypeDetailController extends Controller
{
    protected $roomTypeDetailService;

    public function __construct(RoomTypeDetailService $roomTypeDetailService)
    {
        $this->roomTypeDetailService = $roomTypeDetailService;
    }

    /**
     * Get room type details by slug
     *
     * @param string $slug
     * @param Request $request
     * @return JsonResponse
     */
    public function show(string $slug, Request $request): JsonResponse
    {
        try {
            // Validate query parameters
            $validator = Validator::make($request->all(), [
                'locale' => 'sometimes|string|in:en,vi',
                'currency' => 'sometimes|string|in:VND,USD',
                'reviews_page' => 'sometimes|integer|min:1',
                'reviews_per_page' => 'sometimes|integer|min:1|max:50',
                'related_limit' => 'sometimes|integer|min:1|max:20',
                'include' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $filters = [
                'locale' => $request->get('locale', 'vi'),
                'currency' => $request->get('currency', 'VND'),
                'reviews_page' => $request->get('reviews_page', 1),
                'reviews_per_page' => $request->get('reviews_per_page', 10),
                'related_limit' => $request->get('related_limit', 6),
                'include' => explode(',', $request->get('include', ''))
            ];

            // Generate cache key
            $cacheKey = 'room_type_detail_' . $slug . '_' . md5(serialize($filters));

            // Try to get from cache
            $roomTypeDetail = Cache::remember($cacheKey, 60, function () use ($slug, $filters) {
                return $this->roomTypeDetailService->getRoomTypeDetail($slug, $filters);
            });

            if (!$roomTypeDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room type not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $roomTypeDetail
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Clear cache for room type details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            $slug = $request->get('slug');
            
            if ($slug) {
                // Clear cache for specific slug
                $pattern = 'room_type_detail_' . $slug . '_*';
                $keys = Cache::getRedis()->keys($pattern);
                if (!empty($keys)) {
                    Cache::getRedis()->del($keys);
                }
            } else {
                // Clear all room type detail cache
                $pattern = 'room_type_detail_*';
                $keys = Cache::getRedis()->keys($pattern);
                if (!empty($keys)) {
                    Cache::getRedis()->del($keys);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }
}
