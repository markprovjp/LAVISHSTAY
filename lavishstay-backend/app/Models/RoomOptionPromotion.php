<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomOptionPromotion extends Model
{
    use HasTranslations;
    protected $primaryKey = 'promotion_id';
    protected $fillable = ['option_id', 'type', 'message', 'discount'];

    public function option()
    {
        return $this->belongsTo(RoomOption::class, 'option_id');
    }
}
