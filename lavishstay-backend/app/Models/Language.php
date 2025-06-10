<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $primaryKey = 'language_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['language_code', 'name'];
}
