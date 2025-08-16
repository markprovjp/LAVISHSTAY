<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditLog;
use Carbon\Carbon;

class AuditCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:cleanup 
                            {--days= : Number of days to keep (default from config)}
                            {--batch-size=1000 : Number of records to delete per batch}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old audit log entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days') ?? config('audit.retention_days', 365);
        $batchSize = $this->option('batch-size');
        $dryRun = $this->option('dry-run');

        if ($days <= 0) {
            $this->error('Retention days must be greater than 0. Set to 0 in config to disable auto-cleanup.');
            return 1;
        }

        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Cleaning up audit logs older than {$days} days (before {$cutoffDate->format('Y-m-d H:i:s')})");

        // Count total records to be deleted
        $totalCount = AuditLog::where('created_at', '<', $cutoffDate)->count();

        if ($totalCount === 0) {
            $this->info('No old audit logs found to clean up.');
            return 0;
        }

        $this->info("Found {$totalCount} audit logs to clean up.");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No records will be actually deleted.');
            
            // Show breakdown by model
            $breakdown = AuditLog::where('created_at', '<', $cutoffDate)
                ->selectRaw('model, COUNT(*) as count')
                ->groupBy('model')
                ->orderBy('count', 'desc')
                ->get();

            $this->table(['Model', 'Count'], $breakdown->map(function ($item) {
                return [$item->model, $item->count];
            })->toArray());

            return 0;
        }

        if (!$this->confirm("Are you sure you want to delete {$totalCount} audit log records?")) {
            $this->info('Cleanup cancelled.');
            return 0;
        }

        $deletedTotal = 0;
        $progressBar = $this->output->createProgressBar($totalCount);
        $progressBar->start();

        while (true) {
            $deleted = AuditLog::where('created_at', '<', $cutoffDate)
                ->limit($batchSize)
                ->delete();

            if ($deleted === 0) {
                break;
            }

            $deletedTotal += $deleted;
            $progressBar->advance($deleted);

            // Small delay to prevent overwhelming the database
            usleep(100000); // 0.1 seconds
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("Successfully deleted {$deletedTotal} audit log records.");

        // Update statistics
        $remaining = AuditLog::count();
        $this->info("Remaining audit logs: {$remaining}");

        return 0;
    }
}