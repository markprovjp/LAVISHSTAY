<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuditStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:stats 
                            {--period=30 : Number of days to analyze}
                            {--detailed : Show detailed breakdown}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show audit log statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $period = $this->option('period');
        $detailed = $this->option('detailed');
        
        $startDate = Carbon::now()->subDays($period);
        
        $this->info("Audit Log Statistics (Last {$period} days)");
        $this->info("Period: {$startDate->format('Y-m-d')} to " . Carbon::now()->format('Y-m-d'));
        $this->newLine();

        // Total logs
        $totalLogs = AuditLog::where('created_at', '>=', $startDate)->count();
        $allTimeLogs = AuditLog::count();
        
        $this->info("📊 Total Logs (Period): {$totalLogs}");
        $this->info("📊 Total Logs (All Time): {$allTimeLogs}");
        $this->newLine();

        // By Action
        $this->info('📋 By Action:');
        $actionStats = AuditLog::where('created_at', '>=', $startDate)
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->get();

        $actionData = $actionStats->map(function ($stat) {
            $percentage = $actionStats->sum('count') > 0 
                ? round(($stat->count / $actionStats->sum('count')) * 100, 1) 
                : 0;
            return [
                $stat->action,
                $stat->count,
                $percentage . '%'
            ];
        })->toArray();

        $this->table(['Action', 'Count', 'Percentage'], $actionData);
        $this->newLine();

        // By Model
        $this->info('🏗️ By Model:');
        $modelStats = AuditLog::where('created_at', '>=', $startDate)
            ->selectRaw('model, COUNT(*) as count')
            ->groupBy('model')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $modelData = $modelStats->map(function ($stat) use ($totalLogs) {
            $percentage = $totalLogs > 0 ? round(($stat->count / $totalLogs) * 100, 1) : 0;
            return [
                $stat->model,
                $stat->count,
                $percentage . '%'
            ];
        })->toArray();

        $this->table(['Model', 'Count', 'Percentage'], $modelData);
        $this->newLine();

        // Top Users
        $this->info('👥 Top Users:');
        $userStats = AuditLog::where('created_at', '>=', $startDate)
            ->whereNotNull('user_id')
            ->with('user:id,name,email')
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $userData = $userStats->map(function ($stat) {
            $user = $stat->user;
            return [
                $user ? $user->name : 'Unknown',
                $user ? $user->email : 'N/A',
                $stat->count
            ];
        })->toArray();

        $this->table(['Name', 'Email', 'Actions'], $userData);
        $this->newLine();

        // Daily Activity (last 7 days)
        $this->info('📅 Daily Activity (Last 7 days):');
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = AuditLog::whereDate('created_at', $date)->count();
            $dailyStats[] = [
                $date->format('Y-m-d'),
                $date->format('l'),
                $count
            ];
        }

        $this->table(['Date', 'Day', 'Count'], $dailyStats);
        $this->newLine();

        if ($detailed) {
            $this->showDetailedStats($startDate);
        }

        // Storage info
        $this->info('💾 Storage Information:');
        $avgSize = AuditLog::selectRaw('AVG(LENGTH(old_values) + LENGTH(new_values)) as avg_size')
            ->value('avg_size');
        
        $totalSize = AuditLog::selectRaw('SUM(LENGTH(old_values) + LENGTH(new_values)) as total_size')
            ->value('total_size');

        $this->info("Average record size: " . $this->formatBytes($avgSize ?? 0));
        $this->info("Total data size: " . $this->formatBytes($totalSize ?? 0));
        $this->newLine();

        // Recommendations
        $this->showRecommendations($totalLogs, $allTimeLogs);

        return 0;
    }

    /**
     * Show detailed statistics
     */
    protected function showDetailedStats($startDate)
    {
        $this->info('🔍 Detailed Statistics:');
        
        // Sensitive data logs
        $sensitiveCount = AuditLog::where('created_at', '>=', $startDate)
            ->where('is_sensitive', true)
            ->count();
        
        $this->info("Logs with sensitive data: {$sensitiveCount}");
        
        // Bulk operations
        $bulkCount = AuditLog::where('created_at', '>=', $startDate)
            ->whereIn('action', ['bulk_update', 'bulk_delete'])
            ->count();
        
        $this->info("Bulk operations: {$bulkCount}");
        
        // Failed jobs (if any)
        $failedJobs = DB::table('failed_jobs')
            ->where('payload', 'like', '%ProcessAuditLog%')
            ->count();
        
        if ($failedJobs > 0) {
            $this->warn("Failed audit jobs: {$failedJobs}");
        }
        
        $this->newLine();
    }

    /**
     * Show recommendations based on statistics
     */
    protected function showRecommendations($periodLogs, $totalLogs)
    {
        $this->info('💡 Recommendations:');
        
        $recommendations = [];
        
        // High volume recommendation
        if ($periodLogs > 10000) {
            $recommendations[] = "High audit volume detected. Consider enabling queue processing.";
        }
        
        // Old logs recommendation
        $oldLogs = AuditLog::where('created_at', '<', Carbon::now()->subYear())->count();
        if ($oldLogs > 50000) {
            $recommendations[] = "You have {$oldLogs} logs older than 1 year. Consider running cleanup.";
        }
        
        // Queue recommendation
        if (!config('audit.queue_enabled')) {
            $recommendations[] = "Queue processing is disabled. Enable it for better performance.";
        }
        
        // Retention recommendation
        $retentionDays = config('audit.retention_days', 365);
        if ($retentionDays === 0) {
            $recommendations[] = "No retention policy set. Consider setting retention_days in config.";
        }
        
        if (empty($recommendations)) {
            $recommendations[] = "Your audit system is well configured! 🎉";
        }
        
        foreach ($recommendations as $recommendation) {
            $this->line("• {$recommendation}");
        }
        
        $this->newLine();
        $this->info('Commands you can run:');
        $this->line('• php artisan audit:cleanup --dry-run (Preview cleanup)');
        $this->line('• php artisan audit:cleanup (Clean old logs)');
        $this->line('• php artisan queue:work --queue=audit (Process audit queue)');
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}