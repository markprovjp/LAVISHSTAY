<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RoomPriceHistory extends Model
{
    use HasFactory;

    protected $table = 'room_price_history';
    protected $primaryKey = 'price_history_id';

    protected $fillable = [
        'room_type_id',
        'date',
        'base_price',
        'adjusted_price',
        'applied_rules'
    ];

    protected $casts = [
        'date' => 'date',
        'base_price' => 'decimal:2',
        'adjusted_price' => 'decimal:2',
        'applied_rules' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship with RoomType
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }

    /**
     * Get price change amount
     */
    public function getPriceChangeAttribute()
    {
        return $this->adjusted_price - $this->base_price;
    }

    /**
     * Get price change percentage
     */
    public function getPriceChangePercentAttribute()
    {
        if ($this->base_price <= 0) {
            return 0;
        }
        
        return round((($this->adjusted_price - $this->base_price) / $this->base_price) * 100, 2);
    }

    /**
     * Get formatted applied rules
     */
    public function getFormattedAppliedRulesAttribute()
    {
        if (!$this->applied_rules || !is_array($this->applied_rules)) {
            return [];
        }

        return collect($this->applied_rules)->map(function ($rule) {
            return [
                'type' => $rule['type'] ?? 'unknown',
                'name' => $rule['name'] ?? 'N/A',
                'rule_id' => $rule['rule_id'] ?? null,
                'adjustment' => $rule['adjustment'] ?? 0,
                'priority' => $rule['priority'] ?? 5
            ];
        })->toArray();
    }

    /**
     * Get rule types from applied rules
     */
    public function getRuleTypesAttribute()
    {
        if (!$this->applied_rules || !is_array($this->applied_rules)) {
            return [];
        }

        return collect($this->applied_rules)->pluck('type')->unique()->values()->toArray();
    }

    /**
     * Check if specific rule type was applied
     */
    public function hasRuleType($type)
    {
        return in_array($type, $this->rule_types);
    }

    /**
     * Get total adjustment from all rules
     */
    public function getTotalAdjustmentAttribute()
    {
        if (!$this->applied_rules || !is_array($this->applied_rules)) {
            return 0;
        }

        return collect($this->applied_rules)->sum('adjustment');
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by room type
     */
    public function scopeByRoomType($query, $roomTypeId)
    {
        return $query->where('room_type_id', $roomTypeId);
    }

    /**
     * Scope for filtering by price range
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('adjusted_price', [$min, $max]);
    }

    /**
     * Scope for filtering by rule type
     */
    public function scopeByRuleType($query, $ruleType)
    {
        return $query->whereJsonContains('applied_rules', [['type' => $ruleType]]);
    }

    /**
     * Scope for recent records
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('date', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Scope for records with price increases
     */
    public function scopePriceIncreases($query)
    {
        return $query->whereRaw('adjusted_price > base_price');
    }

    /**
     * Scope for records with price decreases
     */
    public function scopePriceDecreases($query)
    {
        return $query->whereRaw('adjusted_price < base_price');
    }

    /**
     * Create pricing history record
     */
    public static function createRecord($data)
    {
        return self::create([
            'room_type_id' => $data['room_type_id'],
            'date' => $data['date'] ?? Carbon::now()->toDateString(),
            'base_price' => $data['base_price'],
            'adjusted_price' => $data['adjusted_price'],
            'applied_rules' => $data['applied_rules'] ?? []
        ]);
    }
}
