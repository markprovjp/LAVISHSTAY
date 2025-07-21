<?php

namespace App\Services;

use App\Models\RoomType;
use App\Models\FlexiblePricingRule;
use App\Models\DynamicPricingRule;
use App\Models\PricingConfig;
use App\Models\RoomOccupancy;
use App\Models\RoomPriceHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PricingService
{
    private $pricingConfig;
    
    public function __construct()
    {
        $this->pricingConfig = $this->getPricingConfig();
    }

    
    /**
     * Refresh pricing config cache
     */
    public function refreshPricingConfig()
    {
        Cache::forget('pricing_config');
        $this->pricingConfig = $this->getPricingConfig();
        return $this->pricingConfig;
    }

    /**
     * Get room types with pricing info
     */
    public function getRoomTypesWithPricing()
    {
        return RoomType::where('is_active', 1)
            ->select('room_type_id', 'name', 'base_price', 'description')
            ->orderBy('name')
            ->get();
    }

    /**
     * Bulk update occupancy data
     */
    public function bulkUpdateOccupancy($date = null)
    {
        $date = Carbon::parse($date)->toDateString();
        $roomTypes = RoomType::where('is_active', 1)->pluck('room_type_id');
        
        foreach ($roomTypes as $roomTypeId) {
            $this->updateOccupancyData($roomTypeId, $date);
        }
        
        // Clear occupancy cache
        foreach ($roomTypes as $roomTypeId) {
            Cache::forget("occupancy_{$roomTypeId}_{$date}");
        }
    }

    /**
     * Get pricing summary for dashboard
     */
    public function getPricingSummary($days = 7)
    {
        $startDate = now()->toDateString();
        $endDate = now()->addDays($days - 1)->toDateString();
        
        $roomTypes = RoomType::where('is_active', 1)->get();
        $summary = [];
        
        foreach ($roomTypes as $roomType) {
            $roomTypeSummary = [
                'room_type_id' => $roomType->room_type_id,
                'room_type_name' => $roomType->name,
                'base_price' => $roomType->base_price,
                'daily_prices' => []
            ];
            
            $current = now();
            for ($i = 0; $i < $days; $i++) {
                $dateString = $current->toDateString();
                $nightPrice = $this->calculateNightPrice(
                    $roomType->room_type_id,
                    $current,
                    $roomType->base_price
                );
                
                $roomTypeSummary['daily_prices'][] = [
                    'date' => $dateString,
                    'day_name' => $current->format('l'),
                    'base_price' => $roomType->base_price,
                    'adjusted_price' => $nightPrice['adjusted_price'],
                    'price_difference' => $nightPrice['adjusted_price'] - $roomType->base_price,
                    'price_adjustment_percentage' => $nightPrice['price_adjustment_total'],
                    'applied_rules_count' => count($nightPrice['applied_rules']),
                    'is_capped' => $nightPrice['capped_by_percentage'] || $nightPrice['capped_by_absolute']
                ];
                
                $current->addDay();
            }
            
            $summary[] = $roomTypeSummary;
        }
        
        return $summary;
    }

    /**
     * Get active rules summary
     */
    public function getActiveRulesSummary()
    {
        $flexibleRules = FlexiblePricingRule::where('is_active', 1)
            ->with(['roomType', 'event', 'holiday'])
            ->get()
            ->groupBy('rule_type');
            
        $dynamicRules = DynamicPricingRule::where('is_active', 1)
            ->with('roomType')
            ->get();
            
        return [
            'flexible_rules' => $flexibleRules,
            'dynamic_rules' => $dynamicRules,
            'total_flexible_rules' => FlexiblePricingRule::where('is_active', 1)->count(),
            'total_dynamic_rules' => DynamicPricingRule::where('is_active', 1)->count()
        ];
    }

    /**
     * Simulate pricing for date range
     */
    public function simulatePricing($roomTypeId, $startDate, $endDate, $scenarios = [])
    {
        $results = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $basePrice = $this->getBasePrice($roomTypeId);
        
        while ($current->lte($end)) {
            $dateString = $current->toDateString();
            
            // Default calculation
            $defaultResult = $this->calculateNightPrice($roomTypeId, $current, $basePrice);
            
            $dayResult = [
                'date' => $dateString,
                'day_name' => $current->format('l'),
                'default' => $defaultResult
            ];
            
            // Test scenarios if provided
            foreach ($scenarios as $scenarioName => $scenarioConfig) {
                // Temporarily modify config for scenario
                $originalConfig = $this->pricingConfig;
                
                if (isset($scenarioConfig['config'])) {
                    foreach ($scenarioConfig['config'] as $key => $value) {
                        $this->pricingConfig->{$key} = $value;
                    }
                }
                
                $scenarioResult = $this->calculateNightPriceInternal($roomTypeId, $current, $basePrice);
                $dayResult['scenarios'][$scenarioName] = $scenarioResult;
                
                // Restore original config
                $this->pricingConfig = $originalConfig;
            }
            
            $results[] = $dayResult;
            $current->addDay();
        }
        
        return $results;
    }
    /**
     * Calculate price for a room type over a date range
     */
    public function calculatePrice($roomTypeId, $checkInDate, $checkOutDate, $basePrice = null)
    {
        $checkIn = Carbon::parse($checkInDate);
        $checkOut = Carbon::parse($checkOutDate);
        
        // Get base price if not provided
        if (!$basePrice) {
            $basePrice = $this->getBasePrice($roomTypeId);
        }

        $totalPrice = 0;
        $priceBreakdown = [];
        $current = $checkIn->copy();

        // Calculate price for each night
        while ($current->lt($checkOut)) {
            $nightPrice = $this->calculateNightPrice($roomTypeId, $current, $basePrice);
            $totalPrice += $nightPrice['adjusted_price'];
            $priceBreakdown[] = [
                'date' => $current->toDateString(),
                'base_price' => $basePrice,
                'adjusted_price' => $nightPrice['adjusted_price'],
                'applied_rules' => $nightPrice['applied_rules'],
                'price_adjustment_total' => $nightPrice['price_adjustment_total']
            ];
            
            $current->addDay();
        }

        return [
            'total_price' => $totalPrice,
            'base_price_per_night' => $basePrice,
            'nights' => $checkOut->diffInDays($checkIn),
            'price_breakdown' => $priceBreakdown,
            'average_price_per_night' => $totalPrice / $checkOut->diffInDays($checkIn)
        ];
    }

    /**
     * Calculate price for a single night
     */
    public function calculateNightPrice($roomTypeId, $date, $basePrice)
    {
        $dateString = $date->toDateString();
        $cacheKey = "pricing_{$roomTypeId}_{$dateString}_{$basePrice}";
        
        // Try cache first (5 minutes TTL for real-time but with some caching)
        return Cache::remember($cacheKey, 300, function () use ($roomTypeId, $date, $basePrice) {
            return $this->calculateNightPriceInternal($roomTypeId, $date, $basePrice);
        });
    }

    /**
     * Internal method to calculate night price
     */
    private function calculateNightPriceInternal($roomTypeId, $date, $basePrice)
    {
        // Get all applicable rules
        $applicableRules = $this->getApplicableRules($roomTypeId, $date);
        
        // Apply pricing mechanism based on config
        if ($this->pricingConfig->use_exclusive_rule) {
            $result = $this->applyRulePriority($basePrice, $applicableRules);
        } else {
            $result = $this->applyStackingWithCap($basePrice, $applicableRules);
        }

        // Save price history
        $this->savePriceHistory($roomTypeId, $date, $basePrice, $result['adjusted_price'], $result['applied_rules']);

        return $result;
    }

    /**
     * Get all applicable pricing rules for a specific date
     */
    public function getApplicableRules($roomTypeId, $date)
    {
        $rules = [];
        $dateString = $date->toDateString();
        $dayOfWeek = $date->format('l'); // Monday, Tuesday, etc.

        // 1. Get flexible pricing rules
        $flexibleRules = FlexiblePricingRule::where('is_active', 1)
            ->where(function ($query) use ($roomTypeId) {
                $query->whereNull('room_type_id')
                      ->orWhere('room_type_id', $roomTypeId);
            })
            ->where(function ($query) use ($dateString) {
                $query->where(function ($q) use ($dateString) {
                    // No date restriction
                    $q->whereNull('start_date')->whereNull('end_date');
                })->orWhere(function ($q) use ($dateString) {
                    // Within date range
                    $q->where('start_date', '<=', $dateString)
                      ->where('end_date', '>=', $dateString);
                })->orWhere(function ($q) use ($dateString) {
                    // Only start date specified
                    $q->where('start_date', '<=', $dateString)
                      ->whereNull('end_date');
                })->orWhere(function ($q) use ($dateString) {
                    // Only end date specified
                    $q->whereNull('start_date')
                      ->where('end_date', '>=', $dateString);
                });
            })
            ->with(['event', 'holiday'])
            ->get();

        // Track applied rule types to avoid duplicates
        $appliedRuleTypes = [];

        foreach ($flexibleRules as $rule) {
            if ($this->isFlexibleRuleApplicable($rule, $date, $dayOfWeek)) {
                // Avoid duplicate rule types - only take the first one found (highest priority)
                if (!in_array($rule->rule_type, $appliedRuleTypes)) {
                    $rules[] = [
                        'id' => $rule->rule_id,
                        'type' => 'flexible',
                        'rule_type' => $rule->rule_type,
                        'price_adjustment' => $rule->price_adjustment,
                        'priority' => $rule->priority,
                        'is_exclusive' => $rule->is_exclusive,
                        'details' => $this->getFlexibleRuleDetails($rule)
                    ];
                    $appliedRuleTypes[] = $rule->rule_type;
                }
            }
        }

        // 2. Get dynamic pricing rules (occupancy-based)
        $occupancyRate = $this->getOccupancyRate($roomTypeId, $date);
        
        $dynamicRules = DynamicPricingRule::where('is_active', 1)
            ->where(function ($query) use ($roomTypeId) {
                $query->whereNull('room_type_id')
                      ->orWhere('room_type_id', $roomTypeId);
            })
            ->where('occupancy_threshold', '<=', $occupancyRate)
            ->orderBy('occupancy_threshold', 'desc')
            ->first(); // Only take the highest applicable threshold
        if ($dynamicRules) {
            $rules[] = [
                'id' => $dynamicRules->rule_id,
                'type' => 'dynamic',
                'rule_type' => 'occupancy',
                'price_adjustment' => $dynamicRules->price_adjustment,
                'priority' => $dynamicRules->priority,
                'is_exclusive' => $dynamicRules->is_exclusive,
                'details' => [
                    'occupancy_threshold' => $dynamicRules->occupancy_threshold,
                    'current_occupancy' => $occupancyRate
                ]
            ];
        }

        // Sort by priority (1 = highest priority)
        usort($rules, function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });

        return $rules;
    }

            /**
             * Check if flexible rule is applicable for the given date
             */
            private function isFlexibleRuleApplicable($rule, $date, $dayOfWeek)
            {
                switch ($rule->rule_type) {
                    case 'weekend':
                        if ($rule->days_of_week) {
                            $applicableDays = json_decode($rule->days_of_week, true);
                            return in_array($dayOfWeek, $applicableDays);
                        }
                        return false;

                    case 'event':
                        if ($rule->event && $rule->event->is_active) {
                            $eventStart = Carbon::parse($rule->event->start_date);
                            $eventEnd = $rule->event->end_date ? 
                                Carbon::parse($rule->event->end_date) : $eventStart;
                            return $date->between($eventStart, $eventEnd);
                        }
                        return false;

                    case 'holiday':
                        if ($rule->holiday && $rule->holiday->is_active) {
                            $holidayStart = Carbon::parse($rule->holiday->start_date);
                            $holidayEnd = $rule->holiday->end_date ? 
                                Carbon::parse($rule->holiday->end_date) : $holidayStart;
                            return $date->between($holidayStart, $holidayEnd);
                        }
                        return false;

                    case 'season':
                        // Season rules already filtered by date range in main query
                        return true;

                    default:
                        return false;
                }
            }

            /**
             * Apply stacking with cap mechanism
             */
    public function applyStackingWithCap($basePrice, $rules)
    {
        $totalAdjustment = 0;
        $appliedRules = [];

        // Apply all rules and sum adjustments
        foreach ($rules as $rule) {
            $totalAdjustment += $rule['price_adjustment'];
            $appliedRules[] = [
                'rule_id' => $rule['id'],
                'type' => $rule['type'],
                'rule_type' => $rule['rule_type'],
                'price_adjustment' => $rule['price_adjustment'],
                'details' => $rule['details']
            ];
        }

        // Apply percentage cap BEFORE calculating final price
        $cappedByPercentage = false;
        if ($totalAdjustment > $this->pricingConfig->max_price_increase_percentage) {
            $totalAdjustment = $this->pricingConfig->max_price_increase_percentage;
            $cappedByPercentage = true;
        }

        // Calculate adjusted price
        $adjustedPrice = $basePrice + ($basePrice * $totalAdjustment / 100);

        // Apply absolute price cap
        $cappedByAbsolute = false;
        if ($adjustedPrice > $this->pricingConfig->max_absolute_price_vnd) {
            $adjustedPrice = $this->pricingConfig->max_absolute_price_vnd;
            $cappedByAbsolute = true;
        }

        return [
            'adjusted_price' => round($adjustedPrice, 0),
            'price_adjustment_total' => $totalAdjustment,
            'applied_rules' => $appliedRules,
            'capped_by_percentage' => $cappedByPercentage,
            'capped_by_absolute' => $cappedByAbsolute
        ];
    }

            /**
             * Apply rule priority mechanism
             */
    public function applyRulePriority($basePrice, $rules)
    {
        if (empty($rules)) {
            return [
                'adjusted_price' => $basePrice,
                'price_adjustment_total' => 0,
                'applied_rules' => [],
                'capped_by_percentage' => false,
                'capped_by_absolute' => false
            ];
        }

        // If exclusive rule type is set, only use rules of that type
        if ($this->pricingConfig->exclusive_rule_type) {
            $rules = array_filter($rules, function ($rule) {
                return $rule['rule_type'] === $this->pricingConfig->exclusive_rule_type;
            });
        }

        // Take the highest priority rule (lowest priority number)
        $selectedRule = $rules[0] ?? null;
        
        if (!$selectedRule) {
            return [
                'adjusted_price' => $basePrice,
                'price_adjustment_total' => 0,
                'applied_rules' => [],
                'capped_by_percentage' => false,
                'capped_by_absolute' => false
            ];
        }

        $adjustment = $selectedRule['price_adjustment'];
        $adjustedPrice = $basePrice + ($basePrice * $adjustment / 100);

        // Apply absolute price cap
        $cappedByAbsolute = false;
        if ($adjustedPrice > $this->pricingConfig->max_absolute_price_vnd) {
            $adjustedPrice = $this->pricingConfig->max_absolute_price_vnd;
            $cappedByAbsolute = true;
        }

        return [
            'adjusted_price' => round($adjustedPrice, 0),
            'price_adjustment_total' => $adjustment,
            'applied_rules' => [[
                'rule_id' => $selectedRule['id'],
                'type' => $selectedRule['type'],
                'rule_type' => $selectedRule['rule_type'],
                'price_adjustment' => $selectedRule['price_adjustment'],
                'details' => $selectedRule['details']
            ]],
            'capped_by_percentage' => false, // Priority mode doesn't use percentage cap
            'capped_by_absolute' => $cappedByAbsolute
        ];
    }

            /**
             * Get real-time occupancy rate
             */
            public function getOccupancyRate($roomTypeId, $date)
    {
        $dateString = $date->toDateString();
        $cacheKey = "occupancy_{$roomTypeId}_{$dateString}";
        
        return Cache::remember($cacheKey, 60, function () use ($roomTypeId, $dateString) {
            // Update occupancy data first
            $this->updateOccupancyData($roomTypeId, $dateString);
            
            $occupancy = RoomOccupancy::where('room_type_id', $roomTypeId)
                ->where('date', $dateString)
                ->first();
            
            if ($occupancy && $occupancy->total_rooms > 0) {
                return round(($occupancy->booked_rooms / $occupancy->total_rooms) * 100, 2);
            }
            
            // Return mock occupancy rate for testing if no data
            return $this->getMockOccupancyRate($roomTypeId, $dateString);
        });
    }
    private function getMockOccupancyRate($roomTypeId, $dateString)
    {
        $date = Carbon::parse($dateString);
        $dayOfWeek = $date->dayOfWeek;
        
        // Higher occupancy on weekends
        if (in_array($dayOfWeek, [5, 6, 0])) { // Friday, Saturday, Sunday
            return rand(70, 95);
        }
        
        // Medium occupancy on weekdays
        return rand(40, 70);
    }

            /**
             * Update occupancy data for a specific room type and date
             */
            public function updateOccupancyData($roomTypeId, $date)
    {
        try {
            // Get total rooms for this room type
            $totalRooms = DB::table('room')
                ->join('room_option', 'room.room_id', '=', 'room_option.room_id')
                ->join('room_availability', 'room_option.option_id', '=', 'room_availability.option_id')
                ->where('room.room_type_id', $roomTypeId)
                ->where('room_availability.date', $date)
                ->sum('room_availability.total_rooms');

            // If no rooms available, don't update occupancy to prevent division by zero in triggers
            if ($totalRooms == 0) {
                Log::info("Skipping occupancy update for room_type_id: {$roomTypeId} on date: {$date} because totalRooms is zero.");
                return;
            }

            $availableRooms = DB::table('room')
                ->join('room_option', 'room.room_id', '=', 'room_option.room_id')
                ->join('room_availability', 'room_option.option_id', '=', 'room_availability.option_id')
                ->where('room.room_type_id', $roomTypeId)
                ->where('room_availability.date', $date)
                ->sum('room_availability.available_rooms');

            $bookedRooms = $totalRooms - $availableRooms;

            RoomOccupancy::updateOrCreate(
                [
                    'room_type_id' => $roomTypeId,
                    'date' => $date
                ],
                [
                    'total_rooms' => $totalRooms,
                    'booked_rooms' => max(0, $bookedRooms), // Ensure non-negative
                    'occupancy_rate' => $totalRooms > 0 ? round(($bookedRooms / $totalRooms) * 100, 2) : 0
                ]
            );

        } catch (\Exception $e) {
            Log::error('Error updating occupancy data: ' . $e->getMessage(), [
                'room_type_id' => $roomTypeId,
                'date' => $date
            ]);
        }
    }


    /**
     * Get base price for room type
     */
    public function getBasePrice($roomTypeId)
    {
        $roomType = RoomType::find($roomTypeId);
        return $roomType ? $roomType->base_price : 0;
    }

    /**
     * Get pricing configuration
     */
    private function getPricingConfig()
    {
        return Cache::remember('pricing_config', 3600, function () {
            $config = PricingConfig::first();
            
            if (!$config) {
                // Create default config if not exists
                $config = PricingConfig::create([
                    'max_price_increase_percentage' => 40.00,
                    'max_absolute_price_vnd' => 3000000.00,
                    'use_exclusive_rule' => false,
                    'exclusive_rule_type' => null
                ]);
            }
            
            return $config;
        });
    }

    /**
     * Save price history
     */
    private function savePriceHistory($roomTypeId, $date, $basePrice, $adjustedPrice, $appliedRules)
    {
        RoomPriceHistory::updateOrCreate(
            [
                'room_type_id' => $roomTypeId,
                'date' => $date->toDateString()
            ],
            [
                'base_price' => $basePrice,
                'adjusted_price' => $adjustedPrice,
                'applied_rules' => json_encode($appliedRules)
            ]
        );
    }

    /**
     * Get flexible rule details for display
     */
    private function getFlexibleRuleDetails($rule)
    {
        $details = [
            'rule_type' => $rule->rule_type
        ];

        switch ($rule->rule_type) {
            case 'weekend':
                $details['days_of_week'] = $rule->days_of_week ? json_decode($rule->days_of_week, true) : [];
                break;
            case 'event':
                $details['event_name'] = $rule->event ? $rule->event->name : null;
                $details['event_dates'] = $rule->event ? [
                    'start_date' => $rule->event->start_date,
                    'end_date' => $rule->event->end_date
                ] : null;
                break;
            case 'holiday':
                $details['holiday_name'] = $rule->holiday ? $rule->holiday->name : null;
                $details['holiday_dates'] = $rule->holiday ? [
                    'start_date' => $rule->holiday->start_date,
                    'end_date' => $rule->holiday->end_date
                ] : null;
                break;
            case 'season':
                $details['season_name'] = $rule->season_name;
                $details['season_dates'] = [
                    'start_date' => $rule->start_date,
                    'end_date' => $rule->end_date
                ];
                break;
        }

        return $details;
    }

    /**
     * Clear pricing cache for specific room type and date range
     */
    public function clearPricingCache($roomTypeId, $startDate = null, $endDate = null)
{
    try {
        if (!$startDate) {
            // Clear cache for room type trong 30 ngày tới (vì không thể pattern match với file cache)
            $start = Carbon::now();
            $end = Carbon::now()->addDays(30);
            
            $current = $start->copy();
            while ($current->lte($end)) {
                $dateString = $current->toDateString();
                
                // Clear pricing cache với các base price phổ biến
                $commonPrices = [500000, 800000, 1000000, 1200000, 1500000, 2000000, 2500000, 3000000];
                foreach ($commonPrices as $price) {
                    Cache::forget("pricing_{$roomTypeId}_{$dateString}_{$price}");
                }
                
                // Clear occupancy cache
                Cache::forget("occupancy_{$roomTypeId}_{$dateString}");
                
                $current->addDay();
            }
            return;
        }

        $start = Carbon::parse($startDate);
        $end = $endDate ? Carbon::parse($endDate) : $start;
        $current = $start->copy();

        while ($current->lte($end)) {
            $dateString = $current->toDateString();
            
            // Clear pricing cache với các base price phổ biến
            $commonPrices = [500000, 800000, 1000000, 1200000, 1500000, 2000000, 2500000, 3000000];
            foreach ($commonPrices as $price) {
                Cache::forget("pricing_{$roomTypeId}_{$dateString}_{$price}");
            }
            
            // Clear occupancy cache
            Cache::forget("occupancy_{$roomTypeId}_{$dateString}");
            
            $current->addDay();
        }
        
        \Log::info('Pricing cache cleared successfully', [
            'room_type_id' => $roomTypeId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error clearing pricing cache: ' . $e->getMessage(), [
            'room_type_id' => $roomTypeId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        // Don't throw exception, just log it
    }
}

    /**
     * Get pricing preview for admin interface
     */
    public function getPricingPreview($roomTypeId, $startDate, $endDate)
    {
        $basePrice = $this->getBasePrice($roomTypeId);
        $result = $this->calculatePrice($roomTypeId, $startDate, $endDate, $basePrice);
        
        // Add additional info for preview
        $result['room_type'] = RoomType::find($roomTypeId);
        $result['pricing_config'] = $this->pricingConfig;
        
        return $result;
    }

    /**
     * Validate rule conflicts (prevent overlapping events/holidays)
     */
    public function validateRuleConflicts($ruleType, $startDate, $endDate, $excludeRuleId = null)
    {
        if (!in_array($ruleType, ['event', 'holiday'])) {
            return ['valid' => true];
        }

        $start = Carbon::parse($startDate);
        $end = $endDate ? Carbon::parse($endDate) : $start;

        // Check for overlapping rules of the same type
        $query = FlexiblePricingRule::where('rule_type', $ruleType)
            ->where('is_active', 1);

        if ($excludeRuleId) {
            $query->where('rule_id', '!=', $excludeRuleId);
        }

        // Check for date overlaps
        $conflicts = $query->where(function ($q) use ($start, $end) {
            $q->where(function ($subQ) use ($start, $end) {
                // Rule starts before our end and ends after our start
                $subQ->where('start_date', '<=', $end->toDateString())
                     ->where('end_date', '>=', $start->toDateString());
            })->orWhere(function ($subQ) use ($start, $end) {
                // Rule has no end date but starts before our end
                $subQ->where('start_date', '<=', $end->toDateString())
                     ->whereNull('end_date');
            })->orWhere(function ($subQ) use ($start, $end) {
                // Rule has no start date but ends after our start
                $subQ->whereNull('start_date')
                     ->where('end_date', '>=', $start->toDateString());
            })->orWhere(function ($subQ) {
                // Rule has no date restrictions (applies to all dates)
                $subQ->whereNull('start_date')
                     ->whereNull('end_date');
            });
        })->with([$ruleType])->get();

        if ($conflicts->count() > 0) {
            $conflictDetails = $conflicts->map(function ($rule) use ($ruleType) {
                $related = $rule->{$ruleType};
                return [
                    'rule_id' => $rule->rule_id,
                    'name' => $related ? $related->name : 'Unknown',
                    'start_date' => $rule->start_date,
                    'end_date' => $rule->end_date
                ];
            });

            return [
                'valid' => false,
                'message' => "Có {$conflicts->count()} " . ($ruleType === 'event' ? 'sự kiện' : 'lễ hội') . " khác trùng thời gian",
                'conflicts' => $conflictDetails
            ];
        }

        return ['valid' => true];
    }

    /**
     * Update pricing config
     */
    public function updatePricingConfig($data)
    {
        $config = PricingConfig::first();
        
        if (!$config) {
            $config = new PricingConfig();
        }

        $config->fill($data);
        $config->save();

        // Clear config cache
        Cache::forget('pricing_config');
        $this->pricingConfig = $config;

        return $config;
    }

    /**
     * Get pricing statistics
     */
    public function getPricingStatistics($roomTypeId = null, $startDate = null, $endDate = null)
    {
        $query = RoomPriceHistory::query();

        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        $history = $query->orderBy('date', 'desc')->get();

        $stats = [
            'total_records' => $history->count(),
            'average_base_price' => $history->avg('base_price'),
            'average_adjusted_price' => $history->avg('adjusted_price'),
            'max_adjusted_price' => $history->max('adjusted_price'),
            'min_adjusted_price' => $history->min('adjusted_price'),
            'average_adjustment_percentage' => 0,
            'most_applied_rules' => [],
            'price_trend' => []
        ];

        if ($history->count() > 0) {
            // Calculate average adjustment percentage
            $totalAdjustmentPercentage = $history->sum(function ($record) {
                if ($record->base_price > 0) {
                    return (($record->adjusted_price - $record->base_price) / $record->base_price) * 100;
                }
                return 0;
            });
            $stats['average_adjustment_percentage'] = $totalAdjustmentPercentage / $history->count();

            // Get most applied rules
            $ruleUsage = [];
            foreach ($history as $record) {
                $appliedRules = json_decode($record->applied_rules, true) ?: [];
                foreach ($appliedRules as $rule) {
                    $key = $rule['type'] . '_' . $rule['rule_type'];
                    $ruleUsage[$key] = ($ruleUsage[$key] ?? 0) + 1;
                }
            }
            arsort($ruleUsage);
            $stats['most_applied_rules'] = array_slice($ruleUsage, 0, 5, true);

            // Price trend (last 30 days)
            $trendData = $history->take(30)->groupBy('date')->map(function ($group) {
                return [
                    'date' => $group->first()->date,
                    'average_price' => $group->avg('adjusted_price'),
                    'count' => $group->count()
                ];
            })->values();
            $stats['price_trend'] = $trendData;
        }

        return $stats;
    }
}

