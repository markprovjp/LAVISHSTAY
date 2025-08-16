<?php

namespace App\Jobs;

use App\Models\AuditLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAuditLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The audit data to process
     */
    protected array $auditData;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(array $auditData)
    {
        $this->auditData = $auditData;
        $this->onQueue('audit'); // Use dedicated queue for audit logs
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Create the audit log entry
            $auditLog = AuditLog::create($this->auditData);
            
            // Perform additional processing if needed
            $this->processAdditionalTasks($auditLog);
            
            Log::info('Audit log processed successfully', [
                'audit_id' => $auditLog->audit_id,
                'model' => $auditLog->model,
                'action' => $auditLog->action
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to process audit log', [
                'error' => $e->getMessage(),
                'audit_data' => $this->auditData,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Audit log job failed permanently', [
            'error' => $exception->getMessage(),
            'audit_data' => $this->auditData,
            'attempts' => $this->attempts()
        ]);
        
        // Optionally, you could store failed audit logs in a separate table
        // or send notifications to administrators
    }

    /**
     * Process additional tasks after creating audit log
     */
    protected function processAdditionalTasks(AuditLog $auditLog): void
    {
        // Send notifications for critical actions
        if ($this->isCriticalAction($auditLog)) {
            $this->sendCriticalActionNotification($auditLog);
        }
        
        // Update statistics or metrics
        $this->updateAuditStatistics($auditLog);
        
        // Cleanup old audit logs if needed
        if (rand(1, 100) === 1) { // 1% chance to run cleanup
            $this->cleanupOldAuditLogs();
        }
    }

    /**
     * Check if action is critical and needs notification
     */
    protected function isCriticalAction(AuditLog $auditLog): bool
    {
        $criticalActions = ['delete', 'bulk_delete'];
        $criticalModels = ['User', 'Payment', 'Booking'];
        
        return in_array($auditLog->action, $criticalActions) || 
               in_array($auditLog->model, $criticalModels);
    }

    /**
     * Send notification for critical actions
     */
    protected function sendCriticalActionNotification(AuditLog $auditLog): void
    {
        // Implementation depends on your notification system
        // This could send email, Slack notification, etc.
        
        Log::warning('Critical action performed', [
            'audit_id' => $auditLog->audit_id,
            'user_id' => $auditLog->user_id,
            'action' => $auditLog->action,
            'model' => $auditLog->model,
            'model_id' => $auditLog->model_id,
            'ip_address' => $auditLog->ip_address
        ]);
    }

    /**
     * Update audit statistics
     */
    protected function updateAuditStatistics(AuditLog $auditLog): void
    {
        // Update cache or database with audit statistics
        // This could be used for dashboard metrics
        
        $cacheKey = "audit_stats_" . now()->format('Y-m-d');
        $stats = cache()->get($cacheKey, [
            'total' => 0,
            'by_action' => [],
            'by_model' => [],
            'by_user' => []
        ]);
        
        $stats['total']++;
        $stats['by_action'][$auditLog->action] = ($stats['by_action'][$auditLog->action] ?? 0) + 1;
        $stats['by_model'][$auditLog->model] = ($stats['by_model'][$auditLog->model] ?? 0) + 1;
        
        if ($auditLog->user_id) {
            $stats['by_user'][$auditLog->user_id] = ($stats['by_user'][$auditLog->user_id] ?? 0) + 1;
        }
        
        cache()->put($cacheKey, $stats, now()->addDays(7));
    }

    /**
     * Cleanup old audit logs based on configuration
     */
    protected function cleanupOldAuditLogs(): void
    {
        $retentionDays = config('audit.retention_days', 365);
        
        if ($retentionDays > 0) {
            $cutoffDate = now()->subDays($retentionDays);
            
            $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)
                ->limit(1000) // Delete in batches to avoid performance issues
                ->delete();
            
            if ($deletedCount > 0) {
                Log::info("Cleaned up {$deletedCount} old audit logs");
            }
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'audit',
            'model:' . ($this->auditData['model'] ?? 'unknown'),
            'action:' . ($this->auditData['action'] ?? 'unknown'),
            'user:' . ($this->auditData['user_id'] ?? 'system')
        ];
    }
}