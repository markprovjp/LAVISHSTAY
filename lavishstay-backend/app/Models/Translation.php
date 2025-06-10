<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $primaryKey = 'translation_id';
    protected $fillable = ['table_name', 'column_name', 'record_id', 'language_code', 'value'];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code');
    }
}
