<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SchedulerDaemon extends Command
{
    /**
     * The name and signature of the console command.
     * interval in seconds, default 60
     */
    protected $signature = 'scheduler:daemon {--interval=60} {--once : Run just one iteration and exit}';

    /**
     * The console command description.
     */
    protected $description = 'Long-running scheduler daemon that runs `php artisan schedule:run` periodically';

    public function handle()
    {
        $interval = (int) $this->option('interval');

        if ($this->option('once')) {
            $this->info('Scheduler daemon: running one iteration');
            Artisan::call('schedule:run');
            return 0;
        }

        $this->info("Scheduler daemon started (interval={$interval}s). Logs: storage/logs/scheduler-daemon.log");

        while (true) {
            try {
                $start = microtime(true);
                // Run scheduled tasks
                Artisan::call('schedule:run');
                $duration = round((microtime(true) - $start) * 1000, 2);
                Log::info('scheduler:daemon ran schedule:run', ['duration_ms' => $duration]);
            } catch (\Exception $e) {
                Log::error('scheduler:daemon error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            }

            // Free memory and sleep
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }

            sleep($interval);
        }

        return 0;
    }
}
