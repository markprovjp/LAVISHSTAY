<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomTransfer extends Model
{
    use SoftDeletes;

    protected $table = 'room_transfers';
    protected $primaryKey = 'transfer_id';

    protected $fillable = [
        'booking_id',
        'old_room_id',
        'new_room_id',
        'new_option_id', // Lưu package_id từ room_type_package
        'transfer_policy_id',
        'status',
        'price_difference_vnd',
        'payment_id',
        'processed_by',
        'reason',
    ];

    protected $casts = [
        'price_difference_vnd' => 'decimal:2',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function oldRoom()
    {
        return $this->belongsTo(Room::class, 'old_room_id', 'room_id');
    }

    public function newRoom()
    {
        return $this->belongsTo(Room::class, 'new_room_id', 'room_id');
    }

    public function roomTypePackage()
    {
        return $this->belongsTo(RoomTypePackage::class, 'new_option_id', 'package_id');
    }

    public function transferPolicy()
    {
        return $this->belongsTo(RoomTransferPolicy::class, 'transfer_policy_id', 'policy_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id');
    }
}