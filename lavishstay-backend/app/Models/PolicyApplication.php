<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyApplication extends Model
{
    protected $table = 'policy_applications';

    protected $fillable = [
        'room_type_id',
        'policy_type',
        'policy_id',
        'applies_to_holiday',
        'min_occupancy_percent',
        'max_occupancy_percent',
        'min_days_before_checkin',
        'date_from',
        'date_to',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'applies_to_holiday' => 'boolean',
        'is_active' => 'boolean',
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function policy()
    {
        return match ($this->policy_type) {
            'cancellation' => $this->belongsTo(\App\Models\CancellationPolicy::class, 'policy_id'),
            'deposit'      => $this->belongsTo(\App\Models\DepositPolicy::class, 'policy_id'),
            'check_out'    => $this->belongsTo(\App\Models\CheckOutPolicy::class, 'policy_id'),
            default        => null,
        };
    }

    public function roomType()
    {
        return $this->belongsTo(\App\Models\RoomType::class, 'room_type_id');
    }
}
