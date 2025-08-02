<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionPolicy extends Model
{
    protected $table = 'extension_policies';
    protected $primaryKey = 'policy_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'max_extension_days',
        'extension_fee_vnd',
        'extension_percentage',
        'min_days_before_checkout',
        'applies_to_holiday',
        'applies_to_weekend',
        'is_active',
    ];

    protected $casts = [
        'max_extension_days' => 'integer',
        'extension_fee_vnd' => 'decimal:2',
        'extension_percentage' => 'decimal:2',
        'min_days_before_checkout' => 'integer',
        'applies_to_holiday' => 'boolean',
        'applies_to_weekend' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function extensionRequests()
    {
        return $this->hasMany(ExtensionRequest::class, 'extension_policy_id', 'policy_id');
    }
}
?>