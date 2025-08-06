<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReschedulePolicy extends Model
{
    protected $table = 'reschedule_policies';

    protected $primaryKey = 'policy_id';

    protected $fillable = [
        'name',
        'description',
        'room_type_id',
        'min_days_before_checkin',
        'reschedule_fee_vnd',
        'reschedule_fee_percentage',
        'applies_to_holiday',
        'applies_to_weekend',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'applies_to_holiday' => 'boolean',
        'applies_to_weekend' => 'boolean',
        'is_active' => 'boolean',
        'reschedule_fee_vnd' => 'decimal:2',
        'reschedule_fee_percentage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the room type associated with the policy.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }
}
