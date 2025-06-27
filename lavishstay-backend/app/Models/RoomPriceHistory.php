<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPriceHistory extends Model
{
    protected $table = 'room_price_history';
    protected $primaryKey = 'price_history_id';
    
    protected $fillable = [
        'room_type_id',
        'date',
        'base_price',
        'adjusted_price',
        'applied_rules'
    ];

    protected $casts = [
        'date' => 'date',
        'base_price' => 'decimal:2',
        'adjusted_price' => 'decimal:2',
        'applied_rules' => 'array'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'room_type_id');
    }
}
