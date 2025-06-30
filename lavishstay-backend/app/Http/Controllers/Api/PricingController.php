<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PricingController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Calculate price for booking with detailed breakdown
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
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $checkInDate = Carbon::parse($request->check_in_date);
            $checkOutDate = Carbon::parse($request->check_out_date);
            $nights = $checkInDate->diffInDays($checkOutDate);

            if ($nights <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số đêm phải lớn hơn 0'
                ], 422);
            }

            // Get room type info
            $roomType = RoomType::find($request->room_type_id);
            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại phòng không tồn tại'
                ], 404);
            }

            // Use PricingService's calculatePrice method
            $result = $this->pricingService->calculatePrice(
                $request->room_type_id,
                $request->check_in_date,
                $request->check_out_date,
                $request->base_price
            );

            // Add additional info for API response
            $result['room_type'] = [
                'id' => $roomType->room_type_id,
                'name' => $roomType->name,
                'slug' => $roomType->slug ?? null,
                'description' => $roomType->description ?? null
            ];
            
            $result['booking_info'] = [
                'check_in_date' => $checkInDate->format('Y-m-d'),
                'check_out_date' => $checkOutDate->format('Y-m-d'),
                'nights' => $nights,
                'check_in_day' => $checkInDate->format('l'),
                'check_out_day' => $checkOutDate->format('l')
            ];

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Pricing calculation error: ' . $e->getMessage(), [
                'room_type_id' => $request->room_type_id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pricing preview for a single night with detailed rules
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
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $date = Carbon::parse($request->date);
            $roomType = RoomType::find($request->room_type_id);
            
            // Get base price from room type if not provided
            $basePrice = $request->base_price ?: $roomType->base_price;
            
            // Use PricingService's calculateNightPrice method
            $result = $this->pricingService->calculateNightPrice(
                $request->room_type_id,
                $date,
                $basePrice
            );

            // Add room type info and date info to response
            $response = [
                'room_type' => [
                    'id' => $roomType->room_type_id,
                    'name' => $roomType->name,
                    'base_price' => $basePrice
                ],
                'date_info' => [
                    'date' => $date->format('Y-m-d'),
                    'day_of_week' => $date->format('l'),
                    'day_of_week_vi' => $this->getDayOfWeekVietnamese($date->format('l')),
                    'is_weekend' => $date->isWeekend(),
                    'is_today' => $date->isToday(),
                    'is_future' => $date->isFuture()
                ],
                'pricing' => $result
            ];

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Night pricing calculation error: ' . $e->getMessage(), [
                'room_type_id' => $request->room_type_id,
                'date' => $request->date,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính giá: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get applicable rules for a specific date range
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
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $date = Carbon::parse($request->date);
            
            // Use PricingService's getApplicableRules method
            $rules = $this->pricingService->getApplicableRules(
                $request->room_type_id,
                $date
            );

            $roomType = RoomType::find($request->room_type_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'room_type' => [
                        'id' => $roomType->room_type_id,
                        'name' => $roomType->name
                    ],
                    'date_info' => [
                        'date' => $date->format('Y-m-d'),
                        'day_of_week' => $date->format('l'),
                        'day_of_week_vi' => $this->getDayOfWeekVietnamese($date->format('l')),
                        'is_weekend' => $date->isWeekend()
                    ],
                    'applicable_rules' => $rules,
                    'rules_count' => count($rules)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get applicable rules error: ' . $e->getMessage(), [
                'room_type_id' => $request->room_type_id,
                'date' => $request->date,
                'trace' => $e->getTraceAsString()
            ]);

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
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $date = Carbon::parse($request->date);
            
            // Use PricingService's getOccupancyRate method
            $occupancyRate = $this->pricingService->getOccupancyRate(
                $request->room_type_id,
                $date
            );

            $roomType = RoomType::find($request->room_type_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'room_type' => [
                        'id' => $roomType->room_type_id,
                        'name' => $roomType->name
                    ],
                    'date' => $date->format('Y-m-d'),
                    'occupancy_rate' => $occupancyRate,
                    'occupancy_level' => $this->getOccupancyLevel($occupancyRate),
                    'occupancy_description' => $this->getOccupancyDescription($occupancyRate)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get occupancy rate error: ' . $e->getMessage(), [
                'room_type_id' => $request->room_type_id,
                'date' => $request->date,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pricing preview for multiple dates (calendar view)
     */
    public function getPricingCalendar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'required|exists:room_types,room_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'base_price' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $roomType = RoomType::find($request->room_type_id);
            $basePrice = $request->base_price ?: $roomType->base_price;

            // Limit date range to prevent excessive API calls
            $daysDiff = $startDate->diffInDays($endDate);
            if ($daysDiff > 365) {
                return response()->json([
                    'success' => false,
                    'message' => 'Khoảng thời gian không được vượt quá 365 ngày'
                ], 422);
            }

            $calendar = [];
            $currentDate = $startDate->copy();
            $totalBasePrice = 0;
            $totalFinalPrice = 0;

            while ($currentDate->lte($endDate)) {
                $nightPrice = $this->pricingService->calculateNightPrice(
                    $request->room_type_id,
                    $currentDate,
                    $basePrice
                );

                $finalPrice = $nightPrice['adjusted_price'] ?? $basePrice;
                $totalBasePrice += $basePrice;
                $totalFinalPrice += $finalPrice;

                $calendar[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'day_of_week' => $currentDate->format('l'),
                    'day_of_week_vi' => $this->getDayOfWeekVietnamese($currentDate->format('l')),
                    'is_weekend' => $currentDate->isWeekend(),
                    'is_today' => $currentDate->isToday(),
                    'base_price' => $basePrice,
                    'final_price' => $finalPrice,
                    'price_difference' => $finalPrice - $basePrice,
                    'price_adjustment_percentage' => $nightPrice['price_adjustment_total'] ?? 0,
                    'applied_rules_count' => count($nightPrice['applied_rules'] ?? []),
                    'is_capped' => ($nightPrice['capped_by_percentage'] ?? false) || ($nightPrice['capped_by_absolute'] ?? false)
                ];

                $currentDate->addDay();
            }

            $summary = [
                'total_days' => count($calendar),
                'total_base_price' => $totalBasePrice,
                'total_final_price' => $totalFinalPrice,
                'total_savings' => $totalBasePrice - $totalFinalPrice,
                'average_base_price' => count($calendar) > 0 ? $totalBasePrice / count($calendar) : 0,
                'average_final_price' => count($calendar) > 0 ? $totalFinalPrice / count($calendar) : 0
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'room_type' => [
                        'id' => $roomType->room_type_id,
                        'name' => $roomType->name,
                        'base_price' => $basePrice
                    ],
                    'date_range' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d')
                    ],
                    'summary' => $summary,
                    'calendar' => $calendar
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get pricing calendar error: ' . $e->getMessage(), [
                'room_type_id' => $request->room_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate rule conflicts (Admin only)
     */
    public function validateRuleConflicts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rule_type' => 'required|in:event,holiday,season,weekend,occupancy',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'exclude_rule_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->pricingService->validateRuleConflicts(
                $request->rule_type,
                $request->start_date,
                $request->end_date,
                $request->room_type_id,
                $request->exclude_rule_id
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Validate rule conflicts error: ' . $e->getMessage(), $request->all());
                        return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear pricing cache (Admin only)
     */
    public function clearCache(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cache_type' => 'nullable|in:pricing,occupancy,rules,all'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cacheType = $request->cache_type ?? 'all';
            $clearedCount = 0;

            if ($request->room_type_id) {
                // Clear cache for specific room type
                $clearedCount = $this->pricingService->clearPricingCache(
                    $request->room_type_id,
                    $request->start_date,
                    $request->end_date,
                    $cacheType
                );
            } else {
                // Clear cache for all room types
                $roomTypes = RoomType::pluck('room_type_id');
                foreach ($roomTypes as $roomTypeId) {
                    $clearedCount += $this->pricingService->clearPricingCache(
                        $roomTypeId,
                        $request->start_date,
                        $request->end_date,
                        $cacheType
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache đã được xóa thành công',
                'data' => [
                    'cache_type' => $cacheType,
                    'cleared_count' => $clearedCount,
                    'room_type_id' => $request->room_type_id,
                    'date_range' => $request->start_date ? [
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date
                    ] : null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Clear cache error: ' . $e->getMessage(), $request->all());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pricing statistics for admin dashboard
     */
    public function getPricingStats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type_id' => 'nullable|exists:room_types,room_type_id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Use PricingService's getPricingStatistics method
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
            Log::error('Get pricing stats error: ' . $e->getMessage(), [
                'room_type_id' => $request->room_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getOccupancyLevel($occupancyRate)
    {
        if ($occupancyRate >= 90) return 'very_high';
        if ($occupancyRate >= 75) return 'high';
        if ($occupancyRate >= 50) return 'medium';
        if ($occupancyRate >= 25) return 'low';
        return 'very_low';
    }

    private function getPriceImpactFromOccupancy($occupancyRate)
    {
        if ($occupancyRate >= 90) return 'increase_high';
        if ($occupancyRate >= 75) return 'increase_medium';
        if ($occupancyRate >= 50) return 'neutral';
        if ($occupancyRate >= 25) return 'decrease_low';
        return 'decrease_high';
    }
    public function getPricingSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'nullable|integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $days = $request->days ?? 7;
            
            // Use PricingService's getPricingSummary method
            $summary = $this->pricingService->getPricingSummary($days);

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $summary,
                    'generated_at' => now()->toISOString(),
                    'days_ahead' => $days
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get pricing summary error: ' . $e->getMessage(), [
                'days' => $request->days,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getDayOfWeekVietnamese($dayOfWeek)
    {
        $days = [
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
            'Sunday' => 'Chủ Nhật'
        ];

        return $days[$dayOfWeek] ?? $dayOfWeek;
    }

    

    private function getOccupancyDescription($occupancyRate)
    {
        if ($occupancyRate >= 90) return 'Rất cao - Giá có thể tăng mạnh';
        if ($occupancyRate >= 75) return 'Cao - Giá có thể tăng';
        if ($occupancyRate >= 50) return 'Trung bình - Giá ổn định';
        if ($occupancyRate >= 25) return 'Thấp - Có thể có ưu đãi';
        return 'Rất thấp - Nhiều ưu đãi';
    }
}
