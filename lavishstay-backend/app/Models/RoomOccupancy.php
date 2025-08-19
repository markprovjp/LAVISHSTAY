<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RoomOccupancy extends Model
{
    use HasFactory;

    protected $table = 'room_occupancy';
    protected $primaryKey = 'occupancy_id';

    protected $fillable = [
        'room_type_id',
        'date',
        'total_rooms',
        'booked_rooms'
        // occupancy_rate is auto-calculated by database
    ];

    protected $casts = [
        'date' => 'date',
        'total_rooms' => 'integer',
        'booked_rooms' => 'integer',
        'occupancy_rate' => 'decimal:2'
    ];

    /**
     * Relationship with RoomType
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }

    /**
     * Scope for today's records
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    /**
     * Scope for specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Get available rooms count
     */
    public function getAvailableRoomsAttribute()
    {
        return max(0, $this->total_rooms - $this->booked_rooms);
    }

    /**
     * Check if room type is fully booked
     */
    public function getIsFullyBookedAttribute()
    {
        return $this->available_rooms <= 0;
    }

    /**
     * Static method to get today's summary
     */
    public static function getTodaySummary()
    {
        return self::today()
            ->with('roomType')
            ->get()
            ->map(function ($occupancy) {
                return [
                    'room_type_id' => $occupancy->room_type_id,
                    'room_type_name' => $occupancy->roomType->name ?? 'Unknown',
                    'total_rooms' => $occupancy->total_rooms,
                    'booked_rooms' => $occupancy->booked_rooms,
                    'available_rooms' => $occupancy->available_rooms,
                    'occupancy_rate' => $occupancy->occupancy_rate
                ];
            });
    }
}