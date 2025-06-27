<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingConfig extends Model
{
    protected $table = 'pricing_config';
    protected $primaryKey = 'config_id';
    
    protected $fillable = [
        'max_price_increase_percentage',
        'max_absolute_price_vnd',
        'use_exclusive_rule',
        'exclusive_rule_type'
    ];

    protected $casts = [
        'max_price_increase_percentage' => 'decimal:2',
        'max_absolute_price_vnd' => 'decimal:2',
        'use_exclusive_rule' => 'boolean',
    ];
}
