<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CustomCors::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'role' => \App\Http\Middleware\CheckRole::class,
        'permission' => \App\Http\Middleware\CheckPermission::class,
        
        // Notification middleware
        'notification' => \App\Http\Middleware\NotificationMiddleware::class,
        'notification.owner' => \App\Http\Middleware\NotificationMiddleware::class.':view-notifications',
        'notification.manage' => \App\Http\Middleware\NotificationMiddleware::class.':manage-notifications',
        'notification.send' => \App\Http\Middleware\NotificationMiddleware::class.':send-notifications',
    ];

    protected function schedule(Schedule $schedule): void
    {
        // ========== EXISTING OCCUPANCY SCHEDULES ==========
        
        // Calculate room occupancy daily at 00:01 AM
        // This creates 7 records (one for each room type) every day
        $schedule->command('occupancy:calculate')
            ->dailyAt('00:01')
            ->name('daily-occupancy-calculation')
            ->withoutOverlapping(10) // Prevent overlapping executions, timeout after 10 minutes
            ->runInBackground()
            ->onSuccess(function () {
                Log::info('Daily room occupancy calculation completed successfully via scheduler');
            })
            ->onFailure(function () {
                Log::error('Daily room occupancy calculation failed via scheduler');
            });

        // Recalculate occupancy at noon to account for same-day bookings
        $schedule->command('occupancy:calculate')
            ->dailyAt('12:00')
            ->name('midday-occupancy-recalculation')
            ->withoutOverlapping(5)
            ->runInBackground()
            ->onSuccess(function () {
                Log::info('Midday occupancy recalculation completed successfully');
            })
            ->onFailure(function () {
                Log::error('Midday occupancy recalculation failed');
            });

        // Weekly cleanup of old occupancy records (every Sunday at 02:00 AM)
        $schedule->command('occupancy:calculate --cleanup --days-to-keep=365')
            ->weeklyOn(0, '02:00') // Sunday at 2 AM
            ->name('weekly-occupancy-cleanup')
            ->withoutOverlapping(30)
            ->runInBackground()
            ->onSuccess(function () {
                Log::info('Weekly occupancy records cleanup completed successfully');
            })
            ->onFailure(function () {
                Log::error('Weekly occupancy records cleanup failed');
            });

        // ========== NOTIFICATION SCHEDULES ==========

        // Clean up old notifications daily at 2:30 AM (after occupancy cleanup)
        $schedule->command('notifications:cleanup --days=30')
                 ->dailyAt('02:30')
                 ->name('daily-notification-cleanup')
                 ->withoutOverlapping(15)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::info('Daily notification cleanup completed successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Daily notification cleanup failed');
                 })
                 ->appendOutputTo(storage_path('logs/notification-cleanup.log'));

        // Send shift reminders every 15 minutes during business hours
        $schedule->command('notifications:reminders --type=shift')
                 ->everyFifteenMinutes()
                 ->between('06:00', '23:00')
                 ->name('shift-reminders')
                 ->withoutOverlapping(5)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::debug('Shift reminders check completed');
                 })
                 ->onFailure(function () {
                     Log::warning('Shift reminders check failed');
                 });

        // Send checkin reminders every hour during the day
        $schedule->command('notifications:reminders --type=checkin')
                 ->hourly()
                 ->between('08:00', '20:00')
                 ->name('checkin-reminders')
                 ->withoutOverlapping(10)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::debug('Checkin reminders check completed');
                 })
                 ->onFailure(function () {
                     Log::warning('Checkin reminders check failed');
                 });

        // Send checkout reminders at 10 AM daily
        $schedule->command('notifications:reminders --type=checkout')
                 ->dailyAt('10:00')
                 ->name('checkout-reminders')
                 ->withoutOverlapping(10)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::info('Checkout reminders sent successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Checkout reminders failed');
                 });

        // Send maintenance reminders every 2 hours during business hours
        $schedule->command('notifications:reminders --type=maintenance')
                 ->cron('0 */2 8-18 * * *') // Every 2 hours from 8 AM to 6 PM
                 ->name('maintenance-reminders')
                 ->withoutOverlapping(5)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::debug('Maintenance reminders check completed');
                 })
                 ->onFailure(function () {
                     Log::warning('Maintenance reminders check failed');
                 });

        // Weekly notification statistics report (for admins) - every Monday at 9 AM
        $schedule->command('notifications:stats --email-report')
                 ->weeklyOn(1, '09:00')
                 ->name('weekly-notification-stats')
                 ->withoutOverlapping(20)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::info('Weekly notification statistics report sent successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Weekly notification statistics report failed');
                 });

        // Clean up very old notifications (older than 90 days) monthly - first day at 3:30 AM
        $schedule->command('notifications:cleanup --days=90')
                 ->monthlyOn(1, '03:30')
                 ->name('monthly-notification-deep-cleanup')
                 ->withoutOverlapping(60)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::info('Monthly deep notification cleanup completed successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Monthly deep notification cleanup failed');
                 });

        // Backup notification data before cleanup (if needed) - first day at 3:00 AM
        $schedule->command('notifications:backup')
                 ->monthlyOn(1, '03:00')
                 ->name('monthly-notification-backup')
                 ->withoutOverlapping(30)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::info('Monthly notification backup completed successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Monthly notification backup failed');
                 });

        // Send daily summary notifications to managers at 6 PM
        $schedule->command('notifications:daily-summary')
                 ->dailyAt('18:00')
                 ->name('daily-summary-notifications')
                 ->withoutOverlapping(15)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::info('Daily summary notifications sent successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Daily summary notifications failed');
                 });

        // Check for overdue payments and send reminders - every 4 hours during business days
        $schedule->command('notifications:payment-reminders')
                 ->cron('0 */4 * * 1-5') // Every 4 hours, Monday to Friday
                 ->name('payment-reminders')
                 ->withoutOverlapping(10)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::debug('Payment reminders check completed');
                 })
                 ->onFailure(function () {
                     Log::warning('Payment reminders check failed');
                 });

        // Send review follow-up notifications - daily at 11 AM
        $schedule->command('notifications:review-followup')
                 ->dailyAt('11:00')
                 ->name('review-followup-notifications')
                 ->withoutOverlapping(10)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::info('Review follow-up notifications sent successfully');
                 })
                 ->onFailure(function () {
                     Log::error('Review follow-up notifications failed');
                 });

        // Health check for notification system - every 30 minutes
        $schedule->command('notifications:health-check')
                 ->everyThirtyMinutes()
                 ->name('notification-system-health-check')
                 ->withoutOverlapping(5)
                 ->runInBackground()
                 ->onFailure(function () {
                     Log::critical('Notification system health check failed - system may be down');
                 });

        // Sync notification settings for new users - every hour
        $schedule->command('notifications:sync-user-settings')
                 ->hourly()
                 ->name('sync-notification-user-settings')
                 ->withoutOverlapping(10)
                 ->runInBackground()
                 ->onSuccess(function () {
                     Log::debug('User notification settings sync completed');
                 })
                 ->onFailure(function () {
                     Log::warning('User notification settings sync failed');
                 });

        // ========== CONDITIONAL SCHEDULES (ENVIRONMENT SPECIFIC) ==========

        // Development/Staging only: Send test notifications
        if (app()->environment(['local', 'staging'])) {
            $schedule->command('notifications:send-test --type=system_test')
                     ->dailyAt('09:00')
                     ->name('daily-test-notifications')
                     ->withoutOverlapping(5)
                     ->runInBackground()
                     ->onSuccess(function () {
                         Log::info('Daily test notifications sent (dev/staging)');
                     });
        }

        // Production only: Send critical system monitoring notifications
        if (app()->environment('production')) {
            $schedule->command('notifications:system-monitor')
                     ->everyFiveMinutes()
                     ->name('critical-system-monitoring')
                     ->withoutOverlapping(2)
                     ->runInBackground()
                     ->onFailure(function () {
                         Log::critical('Critical system monitoring failed');
                     });
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}