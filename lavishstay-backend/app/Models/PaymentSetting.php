<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group_name',
        'description',
        'is_encrypted',
        'is_active'
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Cache key prefix
    const CACHE_PREFIX = 'payment_setting_';
    const CACHE_TTL = 3600; // 1 hour

    /**
     * Get setting value with proper type casting and decryption
     */
    public function getValueAttribute($value)
    {
        // Decrypt if needed
        if ($this->is_encrypted && !empty($value)) {
            try {
                $value = Crypt::decryptString($value);
            } catch (\Exception $e) {
                Log::error('Failed to decrypt payment setting: ' . $this->key, ['error' => $e->getMessage()]);
                return null;
            }
        }

        // Type casting
        switch ($this->type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'json':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }

    /**
     * Set setting value with encryption if needed
     */
    public function setValueAttribute($value)
    {
        // Convert to string for storage
        if ($this->type === 'json') {
            $value = json_encode($value);
        } elseif ($this->type === 'boolean') {
            $value = $value ? '1' : '0';
        } else {
            $value = (string) $value;
        }

        // Encrypt if needed
        if ($this->is_encrypted && !empty($value)) {
            $value = Crypt::encryptString($value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get setting by key with caching
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = self::CACHE_PREFIX . $key;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($key, $default) {
            $setting = self::where('key', $key)->where('is_active', true)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value, string $type = 'string'): bool
    {
        try {
            $setting = self::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'is_active' => true
                ]
            );

            // Clear cache
            Cache::forget(self::CACHE_PREFIX . $key);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to set payment setting: ' . $key, ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = self::CACHE_PREFIX . 'group_' . $group;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($group) {
            return self::where('group_name', $group)
                ->where('is_active', true)
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Clear all payment settings cache
     */
    public static function clearCache(): void
    {
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget(self::CACHE_PREFIX . $key);
        }
        
        // Clear group caches
        $groups = self::distinct('group_name')->pluck('group_name');
        foreach ($groups as $group) {
            Cache::forget(self::CACHE_PREFIX . 'group_' . $group);
        }
    }

    /**
     * Boot method to clear cache on model events
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            Cache::forget(self::CACHE_PREFIX . $model->key);
            Cache::forget(self::CACHE_PREFIX . 'group_' . $model->group_name);
        });

        static::deleted(function ($model) {
            Cache::forget(self::CACHE_PREFIX . $model->key);
            Cache::forget(self::CACHE_PREFIX . 'group_' . $model->group_name);
        });
    }
}