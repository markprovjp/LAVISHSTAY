<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DynamicPricingRule;
use App\Models\FlexiblePricingRule;
use App\Services\PricingService;
use App\Models\RoomType;
use App\Models\PricingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
        try {
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

            $roomType = RoomType::find($request->room_type_id);
            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại phòng không tồn tại'
                ], 404);
            }

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $previewData = [];

            // Generate pricing preview for each day using PricingService
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $basePrice = floatval($roomType->base_price ?? 1000000);
                
                // Use PricingService to calculate night price
                $nightPricing = $this->pricingService->calculateNightPrice(
                    $request->room_type_id,
                    $date,
                    $basePrice
                );

                $previewData[] = [
                    'date' => $date->format('Y-m-d'),
                    'base_price' => $basePrice,
                    'adjusted_price' => $nightPricing['adjusted_price'],
                    'price_adjustment_total' => $nightPricing['price_adjustment_total'],
                    'applied_rules' => $this->formatAppliedRules($nightPricing['applied_rules']),
                    'capped_by_percentage' => $nightPricing['capped_by_percentage'],
                    'capped_by_absolute' => $nightPricing['capped_by_absolute']
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $previewData
            ]);

        } catch (\Exception $e) {
            Log::error('Pricing preview error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatAppliedRules($appliedRules)
    {
        $formatted = [];
        
        foreach ($appliedRules as $rule) {
            $formatted[] = [
                'rule_type' => $rule['rule_type'],
                'type' => $rule['type'],
                'adjustment_type' => 'percentage', // Assuming percentage for display
                'adjustment_value' => $rule['price_adjustment'],
                'details' => $rule['details'] ?? []
            ];
        }
        
        return $formatted;
    }
    private function calculateAdjustedPrice($basePrice, $appliedRules)
    {
        $adjustedPrice = $basePrice;
        
        if (empty($appliedRules)) {
            return $adjustedPrice;
        }

        Log::info('Calculating price adjustment', [
            'base_price' => $basePrice,
            'applied_rules' => $appliedRules
        ]);

        // Apply each rule
        foreach ($appliedRules as $rule) {
            $adjustmentValue = floatval($rule['adjustment_value']);
            
            if ($rule['adjustment_type'] === 'percentage') {
                $adjustment = ($basePrice * $adjustmentValue / 100);
                $adjustedPrice += $adjustment;
                
                Log::info('Applied percentage rule', [
                    'rule_type' => $rule['rule_type'],
                    'adjustment_value' => $adjustmentValue,
                    'adjustment_amount' => $adjustment,
                    'new_price' => $adjustedPrice
                ]);
                
            } elseif ($rule['adjustment_type'] === 'fixed') {
                $adjustedPrice += $adjustmentValue;
                
                Log::info('Applied fixed rule', [
                    'rule_type' => $rule['rule_type'],
                    'adjustment_value' => $adjustmentValue,
                    'new_price' => $adjustedPrice
                ]);
            }
        }

        // Ensure price doesn't go below base price * 0.5
        $adjustedPrice = max($adjustedPrice, $basePrice * 0.5);
        
        // Cap at reasonable maximum (base price * 3)
        $adjustedPrice = min($adjustedPrice, $basePrice * 3);

        Log::info('Final adjusted price', [
            'original_base_price' => $basePrice,
            'final_adjusted_price' => $adjustedPrice
        ]);

        return round($adjustedPrice, 0); // Round to nearest VND
    }

    

    private function getApplicableRulesForDate($roomTypeId, $date)
    {
        $rules = [];
        $carbonDate = Carbon::parse($date);
        
        try {
            // Check flexible pricing rules from database (if table exists)
            if (Schema::hasTable('flexible_pricing_rules')) {
                $flexibleRules = FlexiblePricingRule::where('is_active', 1)
                    ->where(function($query) use ($roomTypeId) {
                        $query->whereNull('room_type_id')
                              ->orWhere('room_type_id', $roomTypeId);
                    })
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->get();

                foreach ($flexibleRules as $rule) {
                    $rules[] = [
                        'rule_type' => ucfirst($rule->rule_type),
                        'adjustment_type' => $rule->adjustment_type,
                        'adjustment_value' => floatval($rule->adjustment_value)
                    ];
                }
            }

            // Add weekend rule if applicable
            $dayOfWeek = $carbonDate->dayOfWeek;
            if (in_array($dayOfWeek, [0, 6])) { // Sunday = 0, Saturday = 6
                $rules[] = [
                    'rule_type' => 'Weekend',
                    'adjustment_type' => 'percentage',
                    'adjustment_value' => 15.0 // 15% increase for weekend
                ];
            }

            // Add sample seasonal rule for demonstration
            $month = $carbonDate->month;
            if (in_array($month, [12, 1, 2, 6, 7, 8])) { // Winter and Summer seasons
                $rules[] = [
                    'rule_type' => 'Season',
                    'adjustment_type' => 'percentage',
                    'adjustment_value' => 20.0 // 20% increase for peak season
                ];
            }

            // Add sample event rule for demonstration - make it more frequent for testing
            if ($carbonDate->day % 3 === 0) { // Every 3rd day as sample event
                $rules[] = [
                    'rule_type' => 'Event',
                    'adjustment_type' => 'percentage',
                    'adjustment_value' => 30.0 // 30% increase for events
                ];
            }

            Log::info('Rules applied for date', [
                'date' => $date,
                'room_type_id' => $roomTypeId,
                'day_of_week' => $dayOfWeek,
                'month' => $month,
                'day' => $carbonDate->day,
                'rules_count' => count($rules),
                'rules' => $rules
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting applicable rules: ' . $e->getMessage());
        }

        return $rules;
    }
    /**
     * Get pricing statistics
     */
    public function getStatistics()
    {
        try {
            // Count flexible pricing rules
            $flexibleRulesCount = FlexiblePricingRule::where('is_active', 1)->count();
            
            // Count dynamic pricing rules
            $dynamicRulesCount = DynamicPricingRule::where('is_active', 1)->count();
            
            // Calculate average occupancy rate (mock data for now)
            $avgOccupancyRate = 75; // You can implement actual calculation later
            
            // Count active rules (both flexible and dynamic)
            $activeRulesCount = $flexibleRulesCount + $dynamicRulesCount;

            $stats = [
                'flexible_rules_count' => $flexibleRulesCount,
                'dynamic_rules_count' => $dynamicRulesCount,
                'avg_occupancy_rate' => $avgOccupancyRate,
                'active_rules_count' => $activeRulesCount
            ];

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

    public function getRoomTypes()
    {
        try {
            $roomTypes = RoomType::where('is_active', 1)
                ->select('room_type_id', 'name')
                ->get();

            return response()->json($roomTypes);

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

