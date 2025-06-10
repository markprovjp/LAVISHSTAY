<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $primaryKey = 'currency_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['currency_code', 'name', 'exchange_rate', 'symbol', 'format'];
}
