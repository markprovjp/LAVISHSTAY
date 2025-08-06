<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomOption extends Model
{
    protected $primaryKey = 'option_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'room_option';

    protected $fillable = [
        'option_id',
        'room_id',
        'name',
        'price_per_night_vnd',
        'max_guests',
        'min_guests',
        'urgency_message',
        'most_popular',
        'recommended',
        'meal_id',
        'bed_option_id',

        // Chính sách liên kết
        'cancellation_policy_id',
        'deposit_policy_id',
        'check_out_policy_id',

        // Metadata
        'policy_applied_reason',
        'policy_applied_date',
        'policy_snapshot_json',
    ];

    protected $casts = [
        'price_per_night_vnd' => 'decimal:2',
        'most_popular' => 'boolean',
        'recommended' => 'boolean',
        'policy_snapshot_json' => 'array',
        'policy_applied_date' => 'date',
    ];

    // ===== Relationships =====

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function availabilities()
    {
        return $this->hasMany(RoomAvailability::class, 'option_id', 'option_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'option_id', 'option_id');
    }

    public function promotions()
    {
        // return $this->hasMany(RoomOptionPromotion::class, 'option_id'); // Tạm thời comment để tránh lỗi khi model chưa tồn tại
    }

    public function meal()
    {
        return $this->belongsTo(RoomMealOption::class, 'meal_id');
    }

    public function bed()
    {
        return $this->belongsTo(RoomBedOption::class, 'bed_option_id');
    }

    public function cancellationPolicy()
    {
        return $this->belongsTo(CancellationPolicy::class, 'cancellation_policy_id');
    }

    public function depositPolicy()
    {
        return $this->belongsTo(DepositPolicy::class, 'deposit_policy_id');
    }

    public function checkOutPolicy()
    {
        return $this->belongsTo(CheckOutPolicy::class, 'check_out_policy_id');
    }

    public function translations()
    {
        return $this->hasMany(Translation::class, 'record_id')
            ->where('table_name', $this->getTable());
    }
}
