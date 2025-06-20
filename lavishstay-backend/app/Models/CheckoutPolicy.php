<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes; // Xóa hoặc comment dòng này

class CheckoutPolicy extends Model
{
    protected $table = 'check_out_policies';
    protected $primaryKey = 'policy_id';
    protected $fillable = [
        'name',
        'early_check_out_fee_vnd',
        'late_check_out_fee_vnd',
        'early_check_out_max_hours',
        'late_check_out_max_hours',
        'description',
        'is_active',
    ];
    protected $casts = [
        'early_check_out_fee_vnd' => 'decimal:2',
        'late_check_out_fee_vnd' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function checkOutRequests()
    {
        return $this->hasMany(CheckOutRequest::class, 'policy_id', 'policy_id');
    }
}