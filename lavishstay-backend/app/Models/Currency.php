<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currency';
    protected $primaryKey = 'currency_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['currency_code', 'name', 'exchange_rate', 'symbol', 'format'];
    protected $casts = [
        'exchange_rate' => 'float'
    ];
    public $timestamps = false;
}