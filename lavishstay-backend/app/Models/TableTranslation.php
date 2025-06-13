<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableTranslation extends Model
{
    protected $table = 'table_translation'; 
    protected $fillable = ['table_name', 'display_name', 'is_active'];
    protected $primaryKey = 'table_name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}