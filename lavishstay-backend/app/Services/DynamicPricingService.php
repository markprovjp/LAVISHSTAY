<?php

namespace App\Services;

use App\Models\DynamicPricingRule;
use App\Models\RoomAvailability;
use Illuminate\Support\Facades\DB;

class DynamicPricingService
{
    /**
     * Calculate dynamic price for a room type on a specific date
     */
    public function calculatePrice($roomTypeId, $basePrice, $date)
    {
        $occupancyRate = $this->getOccupancyRate($roomTypeId, $date);
        $priceAdjustment = $this->getPriceAdjustment($roomTypeId, $occupancyRate);
        
        $adjustedPrice = $basePrice + ($basePrice * $priceAdjustment / 100);
        
        return [
            'base_price' => $basePrice,
            'occupancy_rate' => $occupancyRate,
            'price_adjustment' => $priceAdjustment,
            'adjusted_price' => $adjustedPrice,
            'price_difference' => $adjustedPrice - $basePrice
        ];
    }

    /**
     * Get occupancy rate for a room type on a specific date
     */
    public function getOccupancyRate($roomTypeId, $date)
    {
        $availability = DB::table('room_availability')
            ->join('room_option', 'room_availability.option_id', '=', 'room_option.option_id')
            ->join('room', 'room_option.room_id', '=', 'room.room_id')
            ->where('room.room_type_id', $roomTypeId)
            ->where('room_availability.date', $date)
            ->selectRaw('SUM(room_availability.total_rooms) as total_rooms, SUM(room_availability.available_rooms) as available_rooms')
            ->first();

        if (!$availability || $availability->total_rooms == 0) {
            return 0;
        }

        $occupiedRooms = $availability->total_rooms - $availability->available_rooms;
        return round(($occupiedRooms / $availability->total_rooms) * 100, 2);
    }

    /**
     * Get price adjustment based on occupancy rate
     */
    public function getPriceAdjustment($roomTypeId, $occupancyRate)
    {
        $rule = DynamicPricingRule::where('room_type_id', $roomTypeId)
            ->where('is_active', 1)
            ->where('occupancy_threshold', '<=', $occupancyRate)
            ->orderBy('occupancy_threshold', 'desc')
            ->first();

        return $rule ? $rule->price_adjustment : 0;
    }

    /**
     * Get all active rules for a room type
     */
    public function getActiveRules($roomTypeId)
    {
        return DynamicPricingRule::where('room_type_id', $roomTypeId)
            ->where('is_active', 1)
            ->orderBy('occupancy_threshold', 'asc')
            ->get();
    }

    /**
     * Check if dynamic pricing is triggered for a room type
     */
    public function isTriggered($roomTypeId, $date = null)
    {
        $date = $date ?: now()->toDateString();
        $occupancyRate = $this->getOccupancyRate($roomTypeId, $date);
        
        return DynamicPricingRule::where('room_type_id', $roomTypeId)
            ->where('is_active', 1)
            ->where('occupancy_threshold', '<=', $occupancyRate)
            ->exists();
    }

    /**
     * Get occupancy statistics for all room types
     */
    public function getOccupancyStatistics($date = null)
    {
        $date = $date ?: now()->toDateString();
        
        return DB::table('room_types')
            ->leftJoin('room', 'room_types.room_type_id', '=', 'room.room_type_id')
            ->leftJoin('room_option', 'room.room_id', '=', 'room_option.room_id')
            ->leftJoin('room_availability', function($join) use ($date) {
                $join->on('room_option.option_id', '=', 'room_availability.option_id')
                     ->where('room_availability.date', '=', $date);
            })
            ->groupBy('room_types.room_type_id', 'room_types.name')
            ->selectRaw('
                room_types.room_type_id,
                room_types.name,
                COALESCE(SUM(room_availability.total_rooms), 0) as total_rooms,
                COALESCE(SUM(room_availability.available_rooms), 0) as available_rooms,
                CASE 
                    WHEN COALESCE(SUM(room_availability.total_rooms), 0) = 0 THEN 0
                    ELSE ROUND(((COALESCE(SUM(room_availability.total_rooms), 0) - COALESCE(SUM(room_availability.available_rooms), 0)) / COALESCE(SUM(room_availability.total_rooms), 1)) * 100, 2)
                END as occupancy_rate
            ')
            ->get()
            ->map(function($item) {
                $item->status = $this->getOccupancyStatus($item->occupancy_rate);
                return $item;
            });
    }

    /**
     * Get occupancy status text
     */
    private function getOccupancyStatus($occupancy)
    {
        if ($occupancy >= 90) return 'Rất cao';
        if ($occupancy >= 70) return 'Cao';
        if ($occupancy >= 50) return 'Trung bình';
        if ($occupancy >= 30) return 'Thấp';
        return 'Rất thấp';
    }
}
