<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositPolicy extends Model
{
    use HasFactory;

    protected $table = 'deposit_policies';
    protected $primaryKey = 'policy_id';

    protected $fillable = [
        'name',
        'deposit_percentage',
        'deposit_fixed_amount_vnd',
        'description',
        'is_active',
    ];

    protected $casts = [
        'deposit_percentage' => 'decimal:2',
        'deposit_fixed_amount_vnd' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Accessors
    public function getFormattedDepositPercentageAttribute()
    {
        return $this->deposit_percentage ? $this->deposit_percentage . '%' : null;
    }

    public function getFormattedDepositFixedAmountAttribute()
    {
        return $this->deposit_fixed_amount_vnd ? number_format($this->deposit_fixed_amount_vnd, 0, ',', '.') . ' VND' : null;
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Hoạt động' : 'Không hoạt động';
    }

    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'green' : 'red';
    }
}
