<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomTransferPolicy extends Model
{
    use SoftDeletes;

    protected $table = 'room_transfer_policies';
    protected $primaryKey = 'policy_id';

    protected $fillable = [
        'name',
        'description',
        'transfer_fee_vnd',
        'transfer_fee_percentage',
        'min_days_before_check_in',
        'applies_to_holiday',
        'applies_to_weekend',
        'requires_guest_confirmation',
        'room_type_id',
        'is_active',
    ];

    protected $casts = [
        'transfer_fee_vnd' => 'decimal:2',
        'transfer_fee_percentage' => 'decimal:2',
        'min_days_before_check_in' => 'integer',
        'applies_to_holiday' => 'boolean',
        'applies_to_weekend' => 'boolean',
        'requires_guest_confirmation' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }

    public function roomTransfers()
    {
        return $this->hasMany(RoomTransfer::class, 'transfer_policy_id', 'policy_id');
    }
}