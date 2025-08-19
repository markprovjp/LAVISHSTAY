<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    protected $primaryKey = 'audit_id';
    
    public $timestamps = false; // We only use created_at
    
    protected $fillable = [
        'user_id',
        'session_id',
        'action',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'changes_summary',
        'description',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'metadata',
        'is_sensitive'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'is_sensitive' => 'boolean',
        'created_at' => 'datetime'
    ];

    /**
     * Sensitive fields that should be encrypted
     */
    protected static $sensitiveFields = [
        'password',
        'password_confirmation',
        'token',
        'api_key',
        'secret',
        'private_key',
        'access_token',
        'refresh_token',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    /**
     * Action colors for UI
     */
    protected static $actionColors = [
        'create' => 'green',
        'update' => 'blue',
        'delete' => 'red',
        'restore' => 'purple',
        'login' => 'indigo',
        'logout' => 'gray',
        'bulk_update' => 'orange',
        'bulk_delete' => 'red',
        'other' => 'gray'
    ];

    /**
     * Action icons for UI
     */
    protected static $actionIcons = [
        'create' => 'plus',
        'update' => 'pencil',
        'delete' => 'trash',
        'restore' => 'arrow-path',
        'login' => 'arrow-right-on-rectangle',
        'logout' => 'arrow-left-on-rectangle',
        'bulk_update' => 'squares-plus',
        'bulk_delete' => 'trash',
        'other' => 'ellipsis-horizontal'
    ];

    /**
     * Relationship with User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the model instance that was audited
     */
    public function getAuditedModel()
    {
        if (!$this->model || !$this->model_id) {
            return null;
        }

        try {
            $modelClass = "App\\Models\\{$this->model}";
            if (class_exists($modelClass)) {
                return $modelClass::find($this->model_id);
            }
        } catch (\Exception $e) {
            // Model might not exist anymore
        }

        return null;
    }

    /**
     * Encrypt sensitive data before saving
     */
    public function setOldValuesAttribute($value)
    {
        $this->attributes['old_values'] = json_encode($this->encryptSensitiveData($value));
    }

    /**
     * Encrypt sensitive data before saving
     */
    public function setNewValuesAttribute($value)
    {
        $this->attributes['new_values'] = json_encode($this->encryptSensitiveData($value));
    }

    /**
     * Decrypt sensitive data when retrieving
     */
    public function getOldValuesAttribute($value)
    {
        $data = json_decode($value, true) ?? [];
        return $this->decryptSensitiveData($data);
    }

    /**
     * Decrypt sensitive data when retrieving
     */
    public function getNewValuesAttribute($value)
    {
        $data = json_decode($value, true) ?? [];
        return $this->decryptSensitiveData($data);
    }

    /**
     * Encrypt sensitive fields in data array
     */
    protected function encryptSensitiveData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (in_array($key, self::$sensitiveFields)) {
                try {
                    $data[$key] = Crypt::encryptString($value);
                    $this->is_sensitive = true;
                } catch (\Exception $e) {
                    // If encryption fails, mask the value
                    $data[$key] = '[ENCRYPTED_ERROR]';
                }
            }
        }

        return $data;
    }

    /**
     * Decrypt sensitive fields in data array
     */
    protected function decryptSensitiveData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (in_array($key, self::$sensitiveFields) && is_string($value)) {
                try {
                    $data[$key] = Crypt::decryptString($value);
                } catch (\Exception $e) {
                    // If decryption fails, show masked value
                    $data[$key] = '[ENCRYPTED]';
                }
            }
        }

        return $data;
    }

    /**
     * Get changes summary in human readable format
     */
    public function getChangesSummaryAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Auto-generate summary if not provided
        return $this->generateChangesSummary();
    }

    /**
     * Generate human readable changes summary
     */
    protected function generateChangesSummary()
    {
        if (!$this->old_values || !$this->new_values) {
            return null;
        }

        $changes = [];
        $oldValues = $this->old_values;
        $newValues = $this->new_values;

        foreach ($newValues as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? null;
            
            if ($oldValue != $newValue) {
                if (in_array($field, self::$sensitiveFields)) {
                    $changes[] = "{$field}: [HIDDEN]";
                } else {
                    $changes[] = "{$field}: '{$oldValue}' → '{$newValue}'";
                }
            }
        }

        return implode(', ', $changes);
    }

    /**
     * Get action color for UI
     */
    public function getActionColor()
    {
        return self::$actionColors[$this->action] ?? 'gray';
    }

    /**
     * Get action icon for UI
     */
    public function getActionIcon()
    {
        return self::$actionIcons[$this->action] ?? 'ellipsis-horizontal';
    }

    /**
     * Get formatted action name
     */
    public function getFormattedAction()
    {
        $actions = [
            'create' => 'Tạo mới',
            'update' => 'Cập nhật',
            'delete' => 'Xóa',
            'restore' => 'Khôi phục',
            'login' => 'Đăng nhập',
            'logout' => 'Đăng xuất',
            'bulk_update' => 'Cập nhật hàng loạt',
            'bulk_delete' => 'Xóa hàng loạt',
            'other' => 'Khác'
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Get formatted model name
     */
    public function getFormattedModel()
    {
        $models = [
            'User' => 'Người dùng',
            'Hotel' => 'Khách sạn',
            'Room' => 'Phòng',
            'Booking' => 'Đặt phòng',
            'Payment' => 'Thanh toán',
            'Review' => 'Đánh giá',
            'Setting' => 'Cài đặt'
        ];

        return $models[$this->model] ?? $this->model;
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by model
     */
    public function scopeByModel($query, $model)
    {
        return $query->where('model', $model);
    }

    /**
     * Scope for filtering by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', Carbon::now()->subHours($hours));
    }

    /**
     * Create audit log entry
     */
    public static function createLog($data)
    {
        return self::create(array_merge($data, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'session_id' => session()->getId(),
            'user_id' => Auth::id()
        ]));
    }
}