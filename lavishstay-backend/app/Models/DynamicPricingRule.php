<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPricingRule extends Model
{
    use HasFactory;

    protected $table = 'dynamic_pricing_rules';
    protected $primaryKey = 'rule_id';

    protected $fillable = [
        'room_type_id',
        'occupancy_threshold',
        'price_adjustment',
        'is_active'
    ];

    protected $casts = [
        'occupancy_threshold' => 'decimal:2',
        'price_adjustment' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeForRoomType($query, $roomTypeId)
    {
        return $query->where('room_type_id', $roomTypeId);
    }

    public function scopeByThreshold($query, $threshold)
    {
        return $query->where('occupancy_threshold', '<=', $threshold);
    }

    // Accessors
    public function getFormattedThresholdAttribute()
    {
        return $this->occupancy_threshold . '%';
    }

    public function getFormattedAdjustmentAttribute()
    {
        $sign = $this->price_adjustment >= 0 ? '+' : '';
        return $sign . $this->price_adjustment . '%';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Hoạt động' : 'Không hoạt động';
    }
}
