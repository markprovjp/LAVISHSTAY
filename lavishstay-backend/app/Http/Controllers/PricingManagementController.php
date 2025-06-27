<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use App\Models\RoomType;
use App\Models\PricingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PricingManagementController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Show pricing management dashboard
     */
    public function index()
    {
        return view('admin.pricing.index');
    }

    /**
     * Show pricing configuration page
     */
    public function config()
    {
        $config = PricingConfig::first();
        $roomTypes = RoomType::where('is_active', 1)->get();
        
        return view('admin.pricing.config', compact('config', 'roomTypes'));
    }

    /**
     * Update pricing configuration
     */
    public function updateConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'max_price_increase_percentage' => 'required|numeric|min:0|max:1000',
            'max_absolute_price_vnd' => 'required|numeric|min:0',
            'use_exclusive_rule' => 'boolean',
            'exclusive_rule_type' => 'nullable|in:event,holiday,season,weekend,occupancy'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $config = $this->pricingService->updatePricingConfig($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật cấu hình thành công',
                'data' => $config
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pricing preview for admin
     */
    public function getPricingPreview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->pricingService->getPricingPreview(
                $request->room_type_id,
                $request->start_date,
                $request->end_date
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pricing statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $stats = $this->pricingService->getPricingStatistics(
                $request->room_type_id,
                $request->start_date,
                $request->end_date
            );

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show pricing history
     */
    public function history()
    {
        return view('admin.pricing.history');
    }

    /**
     * Get pricing history data
     */
    public function getHistoryData(Request $request)
    {
        try {
            $query = \App\Models\RoomPriceHistory::with('roomType');

            if ($request->filled('room_type_id')) {
                $query->where('room_type_id', $request->room_type_id);
            }

            if ($request->filled('start_date')) {
                $query->where('date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->where('date', '<=', $request->end_date);
            }

            $data = $query->orderBy('date', 'desc')
                         ->paginate(20);

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show pricing calculator
     */
    public function calculator()
    {
        $roomTypes = RoomType::where('is_active', 1)->get();
        return view('admin.pricing.calculator', compact('roomTypes'));
    }

    /**
     * Clear pricing cache (admin action)
     */
    public function clearCache(Request $request)
    {
        try {
            if ($request->room_type_id) {
                $this->pricingService->clearPricingCache(
                    $request->room_type_id,
                    $request->start_date,
                    $request->end_date
                );
                $message = 'Đã xóa cache cho loại phòng được chọn';
            } else {
                // Clear all pricing cache
                $roomTypes = RoomType::pluck('room_type_id');
                foreach ($roomTypes as $roomTypeId) {
                    $this->pricingService->clearPricingCache($roomTypeId);
                }
                $message = 'Đã xóa toàn bộ cache giá phòng';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
        }

    /**
     * Test pricing mechanism
     */
    public function testPricing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'test_date' => 'required|date',
            'base_price' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $roomType = RoomType::find($request->room_type_id);
            $basePrice = $request->base_price ?: $roomType->base_price;

            // Get applicable rules
            $rules = $this->pricingService->getApplicableRules(
                $request->room_type_id,
                $request->test_date
            );

            // Test both mechanisms
            $stackingResult = $this->pricingService->applyStackingWithCap($basePrice, $rules);
            $priorityResult = $this->pricingService->applyRulePriority($basePrice, $rules);

            // Get occupancy rate
            $occupancyRate = $this->pricingService->getOccupancyRate(
                $request->room_type_id,
                $request->test_date
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'room_type' => $roomType,
                    'test_date' => $request->test_date,
                    'base_price' => $basePrice,
                    'occupancy_rate' => $occupancyRate,
                    'applicable_rules' => $rules,
                    'stacking_result' => $stackingResult,
                    'priority_result' => $priorityResult,
                    'current_mechanism' => $this->pricingService->getPricingConfig()->use_exclusive_rule ? 'priority' : 'stacking'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}

