<?php

namespace App\Services;

use App\Models\DynamicPricingRule;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class DynamicPricingService
{
    protected RoomOccupancyService $occupancyService;

    public function __construct(RoomOccupancyService $occupancyService)
    {
        $this->occupancyService = $occupancyService;
    }

    /**
     * Calculate adjusted price for a room type based on occupancy and dynamic pricing rules
     */
    public function calculateAdjustedPrice(int $roomTypeId, Carbon $date = null, float $basePrice = null): array
    {
        $date = $date ?? Carbon::today();
        
        try {
            // Get room type and base price
            $roomType = RoomType::find($roomTypeId);
            if (!$roomType) {
                throw new \Exception("Room type not found: {$roomTypeId}");
            }

            $basePrice = $basePrice ?? (float) $roomType->base_price;
            
            // Get current occupancy rate
            $occupancyRate = $this->occupancyService->getCurrentOccupancyRate($roomTypeId, $date);
            
            // Get applicable pricing rules
            $applicableRules = $this->getApplicableRules($roomTypeId, $occupancyRate);
            
            // Calculate price adjustment
            $priceAdjustment = $this->calculatePriceAdjustment($applicableRules);
            
            // Calculate final price
            $adjustedPrice = $basePrice * (1 + ($priceAdjustment / 100));
            $adjustedPrice = max(0, $adjustedPrice); // Ensure price is not negative
            
            return [
                'base_price' => $basePrice,
                'occupancy_rate' => $occupancyRate,
                'price_adjustment' => $priceAdjustment,
                'adjusted_price' => round($adjustedPrice, 2),
                'applied_rules' => $applicableRules->map(function ($rule) {
                    return [
                        'rule_id' => $rule->rule_id,
                        'occupancy_threshold' => $rule->occupancy_threshold,
                        'price_adjustment' => $rule->price_adjustment,
                        'priority' => $rule->priority,
                        'is_exclusive' => $rule->is_exclusive
                    ];
                })->toArray()
            ];

        } catch (\Exception $e) {
            Log::error("Error calculating adjusted price", [
                'room_type_id' => $roomTypeId,
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            // Return base price as fallback
            return [
                'base_price' => $basePrice ?? 0,
                'occupancy_rate' => 0,
                'price_adjustment' => 0,
                'adjusted_price' => $basePrice ?? 0,
                'applied_rules' => []
            ];
        }
    }

    /**
     * Get applicable pricing rules for a room type and occupancy rate
     */
    private function getApplicableRules(int $roomTypeId, float $occupancyRate): Collection
    {
        try {
            // Get all active rules for this room type that meet the occupancy threshold
            $rules = DynamicPricingRule::where('room_type_id', $roomTypeId)
                ->where('is_active', true)
                ->where('occupancy_threshold', '<=', $occupancyRate)
                ->orderBy('priority', 'asc') // Higher priority first (lower number = higher priority)
                ->orderBy('occupancy_threshold', 'desc') // Higher threshold first for same priority
                ->get();

            if ($rules->isEmpty()) {
                return collect();
            }

            // Check for exclusive rules
            $exclusiveRule = $rules->where('is_exclusive', true)->first();
            if ($exclusiveRule) {
                // If there's an exclusive rule, only apply that rule
                return collect([$exclusiveRule]);
            }

            // Apply non-exclusive rules based on priority
            return $this->filterRulesByPriority($rules);

        } catch (\Exception $e) {
            Log::error("Error getting applicable rules", [
                'room_type_id' => $roomTypeId,
                'occupancy_rate' => $occupancyRate,
                'error' => $e->getMessage()
            ]);
            
            return collect();
        }
    }

    /**
     * Filter rules by priority - only apply rules with the highest priority
     */
    private function filterRulesByPriority(Collection $rules): Collection
    {
        if ($rules->isEmpty()) {
            return collect();
        }

        // Get the highest priority (lowest number)
        $highestPriority = $rules->min('priority');
        
        // Return only rules with the highest priority
        return $rules->where('priority', $highestPriority);
    }

    /**
     * Calculate total price adjustment from applicable rules
     */
    private function calculatePriceAdjustment(Collection $rules): float
    {
        if ($rules->isEmpty()) {
            return 0.0;
        }

        // Sum all price adjustments from applicable rules
        return $rules->sum('price_adjustment');
    }

    /**
     * Get pricing analysis for a room type
     */
    public function getPricingAnalysis(int $roomTypeId, Carbon $startDate = null, Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::today();
        $endDate = $endDate ?? Carbon::today()->addDays(7);
        
        try {
            $roomType = RoomType::find($roomTypeId);
            if (!$roomType) {
                throw new \Exception("Room type not found: {$roomTypeId}");
            }

            $analysis = [];
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                $priceData = $this->calculateAdjustedPrice($roomTypeId, $currentDate);
                
                $analysis[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'day_of_week' => $currentDate->format('l'),
                    'base_price' => $priceData['base_price'],
                    'occupancy_rate' => $priceData['occupancy_rate'],
                    'price_adjustment' => $priceData['price_adjustment'],
                    'adjusted_price' => $priceData['adjusted_price'],
                    'applied_rules_count' => count($priceData['applied_rules']),
                    'applied_rules' => $priceData['applied_rules']
                ];
                
                $currentDate->addDay();
            }
            
            return [
                'room_type_id' => $roomTypeId,
                'room_type_name' => $roomType->name,
                'analysis_period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ],
                'daily_analysis' => $analysis,
                'summary' => $this->calculateAnalysisSummary($analysis)
            ];

        } catch (\Exception $e) {
            Log::error("Error getting pricing analysis", [
                'room_type_id' => $roomTypeId,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            return [
                'room_type_id' => $roomTypeId,
                'error' => $e->getMessage(),
                'daily_analysis' => [],
                'summary' => []
            ];
        }
    }

    /**
     * Calculate summary statistics for pricing analysis
     */
    private function calculateAnalysisSummary(array $analysis): array
    {
        if (empty($analysis)) {
            return [];
        }

        $prices = array_column($analysis, 'adjusted_price');
        $occupancyRates = array_column($analysis, 'occupancy_rate');
        $adjustments = array_column($analysis, 'price_adjustment');
        
        return [
            'avg_price' => round(array_sum($prices) / count($prices), 2),
            'min_price' => min($prices),
            'max_price' => max($prices),
            'avg_occupancy_rate' => round(array_sum($occupancyRates) / count($occupancyRates), 2),
            'avg_price_adjustment' => round(array_sum($adjustments) / count($adjustments), 2),
            'days_with_adjustment' => count(array_filter($adjustments, fn($adj) => $adj != 0)),
            'total_days' => count($analysis)
        ];
    }

    /**
     * Get all active rules with their current trigger status
     */
    public function getRulesWithStatus(Carbon $date = null): array
    {
        $date = $date ?? Carbon::today();
        
        try {
            $rules = DynamicPricingRule::with('roomType')
                ->where('is_active', true)
                ->orderBy('room_type_id')
                ->orderBy('priority')
                ->get();

            $rulesWithStatus = [];
            
            foreach ($rules as $rule) {
                $occupancyRate = $this->occupancyService->getCurrentOccupancyRate($rule->room_type_id, $date);
                $isTriggered = $occupancyRate >= $rule->occupancy_threshold;
                
                $rulesWithStatus[] = [
                    'rule_id' => $rule->rule_id,
                    'room_type_id' => $rule->room_type_id,
                    'room_type_name' => $rule->roomType->name ?? 'Unknown',
                    'occupancy_threshold' => $rule->occupancy_threshold,
                    'price_adjustment' => $rule->price_adjustment,
                    'priority' => $rule->priority,
                    'is_exclusive' => $rule->is_exclusive,
                    'current_occupancy' => $occupancyRate,
                    'is_triggered' => $isTriggered,
                    'status' => $isTriggered ? 'Đang kích hoạt' : 'Chưa kích hoạt'
                ];
            }
            
            return $rulesWithStatus;

        } catch (\Exception $e) {
            Log::error("Error getting rules with status", [
                'date' => $date->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }

    /**
     * Validate pricing rule before saving
     */
    public function validateRule(array $ruleData): array
    {
        $errors = [];
        
        // Check if room type exists
        if (!RoomType::find($ruleData['room_type_id'] ?? null)) {
            $errors['room_type_id'] = ['Loại phòng không tồn tại'];
        }
        
        // Validate occupancy threshold
        $threshold = $ruleData['occupancy_threshold'] ?? null;
        if ($threshold === null || $threshold < 0 || $threshold > 100) {
            $errors['occupancy_threshold'] = ['Ngưỡng lấp đầy phải từ 0 đến 100'];
        }
        
        // Validate price adjustment
        $adjustment = $ruleData['price_adjustment'] ?? null;
        if ($adjustment === null || $adjustment < -100 || $adjustment > 500) {
            $errors['price_adjustment'] = ['Điều chỉnh giá phải từ -100% đến 500%'];
        }
        
        return $errors;
    }
}