<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use App\Helpers\NotificationHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificationCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup 
                            {--days=30 : Number of days to keep notifications}
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--send-reminders : Send scheduled reminders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old notifications and send scheduled reminders';

    protected $notificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting notification cleanup and reminder process...');

        // Clean up old notifications
        $this->cleanupOldNotifications();

        // Send scheduled reminders if requested
        if ($this->option('send-reminders')) {
            $this->sendScheduledReminders();
        }

        $this->info('Notification cleanup and reminder process completed.');
        return 0;
    }

    /**
     * Clean up old notifications
     */
    protected function cleanupOldNotifications()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $this->info("Cleaning up notifications older than {$days} days...");

        $cutoffDate = Carbon::now()->subDays($days);
        
        $query = Notification::where('created_at', '<', $cutoffDate);
        
        // Get count before deletion
        $count = $query->count();
        
        if ($count === 0) {
            $this->info('No old notifications found to clean up.');
            return;
        }

        if ($dryRun) {
            $this->warn("DRY RUN: Would delete {$count} notifications older than {$cutoffDate->format('Y-m-d H:i:s')}");
            
            // Show some examples
            $examples = $query->take(5)->get(['id', 'title', 'created_at']);
            $this->table(['ID', 'Title', 'Created At'], $examples->map(function ($notification) {
                return [
                    $notification->id,
                    substr($notification->title, 0, 50) . (strlen($notification->title) > 50 ? '...' : ''),
                    $notification->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray());
            
            return;
        }

        // Perform actual deletion
        try {
            $deletedCount = $query->delete();
            $this->info("Successfully deleted {$deletedCount} old notifications.");
            
            Log::info("Notification cleanup completed", [
                'deleted_count' => $deletedCount,
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'days' => $days
            ]);
            
        } catch (\Exception $e) {
            $this->error("Error during cleanup: " . $e->getMessage());
            Log::error("Notification cleanup failed", [
                'error' => $e->getMessage(),
                'cutoff_date' => $cutoffDate->toDateTimeString()
            ]);
        }
    }

    /**
     * Send scheduled reminders
     */
    protected function sendScheduledReminders()
    {
        $this->info('Sending scheduled reminders...');

        // Send shift reminders
        $this->sendShiftReminders();

        // Send checkin reminders
        $this->sendCheckinReminders();

        // Send maintenance reminders
        $this->sendMaintenanceReminders();
    }

    /**
     * Send shift reminders to staff
     */
    protected function sendShiftReminders()
    {
        $this->info('Checking for upcoming shifts...');

        // This is a placeholder - you'll need to implement based on your shift/schedule system
        // Example: Find shifts starting in 30 minutes
        
        /*
        $upcomingShifts = Shift::where('start_time', '>=', now())
            ->where('start_time', '<=', now()->addMinutes(30))
            ->whereDoesntHave('reminders', function ($query) {
                $query->where('sent_at', '>=', now()->subHours(1));
            })
            ->with('user')
            ->get();

        foreach ($upcomingShifts as $shift) {
            $minutesUntil = now()->diffInMinutes($shift->start_time);
            
            NotificationHelper::staffShiftReminder(
                $shift->user,
                $shift,
                $minutesUntil
            );

            // Mark reminder as sent
            $shift->reminders()->create([
                'type' => 'shift_reminder',
                'sent_at' => now()
            ]);

            $this->info("Sent shift reminder to {$shift->user->name} for shift starting in {$minutesUntil} minutes");
        }
        */

        $this->info('Shift reminders check completed.');
    }

    /**
     * Send checkin reminders
     */
    protected function sendCheckinReminders()
    {
        $this->info('Checking for upcoming checkins...');

        // This is a placeholder - implement based on your booking system
        /*
        $upcomingCheckins = Booking::whereDate('checkin_date', today())
            ->where('status', 'confirmed')
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'checkin_reminder')
                      ->where('created_at', '>=', today());
            })
            ->with(['customer', 'room'])
            ->get();

        foreach ($upcomingCheckins as $booking) {
            NotificationHelper::checkinReminder($booking, $booking->customer);
            $this->info("Sent checkin reminder for booking #{$booking->id}");
        }
        */

        $this->info('Checkin reminders check completed.');
    }

    /**
     * Send maintenance reminders
     */
    protected function sendMaintenanceReminders()
    {
        $this->info('Checking for scheduled maintenance...');

        // This is a placeholder - implement based on your maintenance system
        /*
        $scheduledMaintenance = MaintenanceSchedule::where('scheduled_date', today())
            ->where('status', 'scheduled')
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'maintenance_reminder')
                      ->where('created_at', '>=', today());
            })
            ->with('room')
            ->get();

        foreach ($scheduledMaintenance as $maintenance) {
            NotificationHelper::roomMaintenanceRequired(
                $maintenance->room,
                $maintenance->description,
                'normal',
                null
            );
            $this->info("Sent maintenance reminder for room {$maintenance->room->number}");
        }
        */

        $this->info('Maintenance reminders check completed.');
    }

    /**
     * Get notification statistics
     */
    protected function showStatistics()
    {
        $this->info('Notification Statistics:');

        $stats = [
            'Total notifications' => Notification::count(),
            'Unread notifications' => Notification::whereNull('read_at')->count(),
            'Today\'s notifications' => Notification::whereDate('created_at', today())->count(),
            'This week\'s notifications' => Notification::where('created_at', '>=', now()->startOfWeek())->count(),
            'Urgent notifications' => Notification::where('priority', 'urgent')->whereNull('read_at')->count(),
        ];

        foreach ($stats as $label => $count) {
            $this->line("  {$label}: {$count}");
        }

        // Show breakdown by priority
        $this->info('By Priority:');
        $priorities = Notification::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        foreach (['low', 'normal', 'high', 'urgent'] as $priority) {
            $count = $priorities[$priority] ?? 0;
            $this->line("  {$priority}: {$count}");
        }

        // Show breakdown by type
        $this->info('By Type (Top 10):');
        $types = Notification::join('notification_types', 'notifications.notification_type_id', '=', 'notification_types.id')
            ->selectRaw('notification_types.title, COUNT(*) as count')
            ->groupBy('notification_types.id', 'notification_types.title')
            ->orderByDesc('count')
            ->take(10)
            ->pluck('count', 'title')
            ->toArray();

        foreach ($types as $type => $count) {
            $this->line("  {$type}: {$count}");
        }
    }
}