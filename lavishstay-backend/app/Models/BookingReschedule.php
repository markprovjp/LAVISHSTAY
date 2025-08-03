<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingReschedule extends Model
{
    protected $table = 'booking_reschedules';

    protected $primaryKey = 'reschedule_id';

    protected $fillable = [
        'booking_id',
        'new_check_in_date',
        'new_check_out_date',
        'new_room_id',
        'new_option_id',
        'reschedule_policy_id',
        'price_difference_vnd',
        'payment_id',
        'status',
        'reason',
        'suggested_rooms',
        'processed_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'new_check_in_date' => 'date',
        'new_check_out_date' => 'date',
        'price_difference_vnd' => 'decimal:2',
        'status' => 'string',
        'suggested_rooms' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking associated with the reschedule request.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    /**
     * Get the room associated with the reschedule request.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'new_room_id', 'room_id');
    }

    /**
     * Get the room option associated with the reschedule request.
     */
    public function roomOption(): BelongsTo
    {
        return $this->belongsTo(RoomOption::class, 'new_option_id', 'option_id');
    }

    /**
     * Get the reschedule policy associated with the reschedule request.
     */
    public function reschedulePolicy(): BelongsTo
    {
        return $this->belongsTo(ReschedulePolicy::class, 'reschedule_policy_id', 'policy_id');
    }

    /**
     * Get the payment associated with the reschedule request.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }

    /**
     * Get the user who processed the reschedule request.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by', 'id');
    }
}