<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponRedemption extends Model
{
    use HasFactory;

    public $timestamps = false; // Chỉ có created_at

    protected $fillable = [
        'coupon_id',
        'user_id',
        'booking_id',
        'amount_saved_vnd',
        'applied_amount_vnd',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'amount_saved_vnd' => 'decimal:2',
        'applied_amount_vnd' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
