<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translation'; 
    protected $primaryKey = 'translation_id';
    protected $fillable = ['table_name', 'column_name', 'record_id', 'language_code', 'value'];
    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code', 'language_code'); // Liên kết language_code với language_code
    }

    public static function boot()
    {
        parent::boot();
        // Có thể để trống hoặc thêm logic khác nếu cần, nhưng không kiểm tra trùng lặp ở đây
    }
}