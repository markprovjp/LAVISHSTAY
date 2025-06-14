<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class RoomOption extends Model
{
    protected $table = 'room_option';
    protected $primaryKey = 'option_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'option_id',
        'room_id',
        'name',
        'price_per_night_vnd',
        'max_guests',
        'min_guests',
        'cancellation_policy_type',
        'payment_policy_type',
        'most_popular',
        'recommended'
=======
trait HasTranslations
{
    public function getTranslatedAttribute($column, $lang)
    {
        return Translation::where('table_name', $this->getTable())
            ->where('column_name', $column)
            ->where('record_id', $this->getKey())
            ->where('language_code', $lang)
            ->value('value') ?? $this->$column;
    }
}
class RoomOption extends Model
{
    use HasTranslations;
    protected $primaryKey = 'option_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'option_id', 'room_id', 'name', 'price_per_night_vnd', 'max_guests', 'min_guests',
        'cancellation_policy_type', 'cancellation_penalty', 'cancellation_description',
        'free_until', 'payment_policy_type', 'payment_description', 'urgency_message',
        'most_popular', 'recommended', 'meal_id', 'bed_option_id', 'deposit_percentage'
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
    ];

    protected $casts = [
        'price_per_night_vnd' => 'decimal:2',
        'most_popular' => 'boolean',
        'recommended' => 'boolean',
    ];

<<<<<<< HEAD
=======


    public function translations()
    {
        return $this->hasMany(Translation::class, 'record_id')
            ->where('table_name', $this->getTable());
    }


>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function availabilities()
    {
        return $this->hasMany(RoomAvailability::class, 'option_id', 'option_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'option_id', 'option_id');
    }
<<<<<<< HEAD
=======
    public function promotions()
    {
        return $this->hasMany(RoomOptionPromotion::class, 'option_id');
    }
    public function meal()
    {
        return $this->belongsTo(RoomMealOption::class, 'meal_id');
    }

    public function bed()
    {
        return $this->belongsTo(RoomBedOption::class, 'bed_option_id');
    }
>>>>>>> d3d6154b8e36fbf29dafa15923efa07757dc20dc
}
