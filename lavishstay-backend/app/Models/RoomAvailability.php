<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAvailability extends Model
{
    protected $table = 'room_availability';
    protected $primaryKey = 'availability_id';
    
    protected $fillable = [
        'option_id',
        'date',
        'total_rooms',
        'available_rooms'
    ];
    
    protected $casts = [
        'date' => 'date'
    ];
    
    public function roomOption()
    {
        return $this->belongsTo(RoomOption::class, 'option_id', 'option_id');
    }
}
