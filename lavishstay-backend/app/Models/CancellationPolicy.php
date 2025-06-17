<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationPolicy extends Model
{
    use HasFactory;

    protected $table = 'cancellation_policies';
    protected $primaryKey = 'policy_id';

    protected $fillable = [
        'name',
        'free_cancellation_days',
        'penalty_percentage',
        'penalty_fixed_amount_vnd',
        'description',
        'is_active'
    ];

    protected $casts = [
        'penalty_percentage' => 'decimal:2',
        'penalty_fixed_amount_vnd' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for active policies
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor for formatted penalty percentage
    public function getPenaltyPercentageFormattedAttribute()
    {
        return $this->penalty_percentage ? $this->penalty_percentage . '%' : null;
    }

    // Accessor for formatted fixed amount
    public function getPenaltyFixedAmountFormattedAttribute()
    {
        return $this->penalty_fixed_amount_vnd ? number_format($this->penalty_fixed_amount_vnd, 0, ',', '.') . ' VND' : null;
    }
}
