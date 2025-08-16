<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Jobs\ProcessAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    /**
     * Models to exclude from auditing
     */
    protected static $excludedModels = [
        'App\Models\AuditLog',
        'App\Models\Session',
        'App\Models\Cache',
        'App\Models\FailedJob',
        'App\Models\PersonalAccessToken'
    ];

    /**
     * Fields to exclude from auditing
     */
    protected static $excludedFields = [
        'updated_at',
        'created_at',
        'deleted_at',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        if ($this->shouldAudit($model)) {
            $this->logActivity($model, 'create', null, $model->getAttributes());
        }
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        if ($this->shouldAudit($model)) {
            $original = $model->getOriginal();
            $changes = $model->getChanges();
            
            // Remove excluded fields
            $original = $this->filterFields($original);
            $changes = $this->filterFields($changes);
            
            if (!empty($changes)) {
                $this->logActivity($model, 'update', $original, $changes);
            }
        }
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        if ($this->shouldAudit($model)) {
            $this->logActivity($model, 'delete', $model->getOriginal(), null);
        }
    }

    /**
     * Handle the Model "restored" event.
     */
    public function restored(Model $model): void
    {
        if ($this->shouldAudit($model)) {
            $this->logActivity($model, 'restore', null, $model->getAttributes());
        }
    }

    /**
     * Check if model should be audited
     */
    protected function shouldAudit(Model $model): bool
    {
        $modelClass = get_class($model);
        
        // Skip excluded models
        if (in_array($modelClass, self::$excludedModels)) {
            return false;
        }
        
        // Skip if model has audit disabled
        if (method_exists($model, 'isAuditEnabled') && !$model->isAuditEnabled()) {
            return false;
        }
        
        // Skip if we're in console and not explicitly enabled
        if (app()->runningInConsole() && !config('audit.console_enabled', false)) {
            return false;
        }
        
        return true;
    }

    /**
     * Filter out excluded fields
     */
    protected function filterFields(array $data): array
    {
        return array_diff_key($data, array_flip(self::$excludedFields));
    }

    /**
     * Log the activity
     */
    protected function logActivity(Model $model, string $action, ?array $oldValues, ?array $newValues): void
    {
        $modelName = class_basename($model);
        $modelId = $model->getKey();
        
        $auditData = [
            'action' => $action,
            'model' => $modelName,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'session_id' => session()->getId(),
        ];

        // Add description based on action
        $auditData['description'] = $this->generateDescription($model, $action);
        
        // Add metadata
        $auditData['metadata'] = $this->generateMetadata($model, $action);

        // Dispatch to queue if enabled, otherwise create directly
        if (config('audit.queue_enabled', true)) {
            ProcessAuditLog::dispatch($auditData);
        } else {
            AuditLog::create($auditData);
        }
    }

    /**
     * Generate description for the audit log
     */
    protected function generateDescription(Model $model, string $action): string
    {
        $modelName = class_basename($model);
        $modelId = $model->getKey();
        $userName = Auth::user()->name ?? 'System';
        
        $descriptions = [
            'create' => "{$userName} đã tạo mới {$modelName} #{$modelId}",
            'update' => "{$userName} đã cập nhật {$modelName} #{$modelId}",
            'delete' => "{$userName} đã xóa {$modelName} #{$modelId}",
            'restore' => "{$userName} đã khôi phục {$modelName} #{$modelId}",
        ];

        return $descriptions[$action] ?? "{$userName} đã thực hiện {$action} trên {$modelName} #{$modelId}";
    }

    /**
     * Generate metadata for the audit log
     */
    protected function generateMetadata(Model $model, string $action): array
    {
        $metadata = [
            'model_class' => get_class($model),
            'action_timestamp' => now()->toISOString(),
        ];

        // Add model-specific metadata
        if (method_exists($model, 'getAuditMetadata')) {
            $metadata = array_merge($metadata, $model->getAuditMetadata());
        }

        // Add request context
        if (request()->route()) {
            $metadata['route_name'] = request()->route()->getName();
            $metadata['route_action'] = request()->route()->getActionName();
        }

        return $metadata;
    }

    /**
     * Handle bulk operations
     */
    public static function logBulkOperation(string $modelClass, string $action, array $ids, array $data = []): void
    {
        $modelName = class_basename($modelClass);
        
        $auditData = [
            'action' => "bulk_{$action}",
            'model' => $modelName,
            'model_id' => 0, // Use 0 for bulk operations
            'old_values' => null,
            'new_values' => $data,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'session_id' => session()->getId(),
            'description' => self::generateBulkDescription($modelName, $action, count($ids)),
            'metadata' => [
                'affected_ids' => $ids,
                'affected_count' => count($ids),
                'bulk_operation' => true,
                'model_class' => $modelClass,
            ]
        ];

        if (config('audit.queue_enabled', true)) {
            ProcessAuditLog::dispatch($auditData);
        } else {
            AuditLog::create($auditData);
        }
    }

    /**
     * Generate description for bulk operations
     */
    protected static function generateBulkDescription(string $modelName, string $action, int $count): string
    {
        $userName = Auth::user()->name ?? 'System';
        
        $descriptions = [
            'update' => "{$userName} đã cập nhật hàng loạt {$count} {$modelName}",
            'delete' => "{$userName} đã xóa hàng loạt {$count} {$modelName}",
        ];

        return $descriptions[$action] ?? "{$userName} đã thực hiện {$action} hàng loạt trên {$count} {$modelName}";
    }
}