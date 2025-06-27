<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class PricingController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Calculate price for booking
     */
    public function calculatePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'base_price' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->pricingService->calculatePrice(
                $request->room_type_id,
                $request->check_in_date,
                $request->check_out_date,
                $request->base_price
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pricing preview for a single night
     */
    public function calculateNightPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'date' => 'required|date',
            'base_price' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $basePrice = $request->base_price ?: $this->pricingService->getBasePrice($request->room_type_id);
            
            $result = $this->pricingService->calculateNightPrice(
                $request->room_type_id,
                $request->date,
                $basePrice
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get applicable rules for a specific date
     */
    public function getApplicableRules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $rules = $this->pricingService->getApplicableRules(
                $request->room_type_id,
                $request->date
            );

            return response()->json([
                'success' => true,
                'data' => $rules
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get occupancy rate for room type and date
     */
    public function getOccupancyRate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $date = Carbon::parse($request->date);

        try {
            $occupancyRate = $this->pricingService->getOccupancyRate(
                $request->room_type_id,
                $date 
        );      

            return response()->json([
                'success' => true,
                'data' => [
                    'room_type_id' => $request->room_type_id,
                    'date' => $date,
                    'occupancy_rate' => $occupancyRate
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate rule conflicts
     */
    public function validateRuleConflicts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rule_type' => 'required|in:event,holiday',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'exclude_rule_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->pricingService->validateRuleConflicts(
                $request->rule_type,
                $request->start_date,
                $request->end_date,
                $request->exclude_rule_id
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
     * Clear pricing cache
     */
    public function clearCache(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->room_type_id) {
                $this->pricingService->clearPricingCache(
                    $request->room_type_id,
                    $request->start_date,
                    $request->end_date
                );
            } else {
                // Clear all pricing cache
                $roomTypes = RoomType::pluck('room_type_id');
                foreach ($roomTypes as $roomTypeId) {
                    $this->pricingService->clearPricingCache($roomTypeId);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache đã được xóa thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
