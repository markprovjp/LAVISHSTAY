<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionRequest extends Model
{
    protected $table = 'extension_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = true;

    protected $fillable = [
        'booking_id',
        'extension_policy_id',
        'new_check_out_date',
        'extension_days',
        'extension_fee_vnd',
        'status',
        'processed_by',
        'notes',
    ];

    protected $casts = [
        'booking_id' => 'integer',
        'extension_policy_id' => 'integer',
        'new_check_out_date' => 'date',
        'extension_days' => 'integer',
        'extension_fee_vnd' => 'decimal:2',
        'status' => 'string',
        'processed_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function extensionPolicy()
    {
        return $this->belongsTo(ExtensionPolicy::class, 'extension_policy_id', 'policy_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'id');
    }
}
?>