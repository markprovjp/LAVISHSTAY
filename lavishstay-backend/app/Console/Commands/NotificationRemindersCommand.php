<?php

namespace App\Console\Commands;

use App\Helpers\NotificationHelper;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificationRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:reminders 
                            {--type=all : Type of reminders to send (shift|checkin|checkout|maintenance|all)}
                            {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notification reminders';

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
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');

        $this->info("Starting notification reminders process for type: {$type}");

        $sentCount = 0;

        try {
            switch ($type) {
                case 'shift':
                    $sentCount += $this->sendShiftReminders($dryRun);
                    break;
                case 'checkin':
                    $sentCount += $this->sendCheckinReminders($dryRun);
                    break;
                case 'checkout':
                    $sentCount += $this->sendCheckoutReminders($dryRun);
                    break;
                case 'maintenance':
                    $sentCount += $this->sendMaintenanceReminders($dryRun);
                    break;
                case 'all':
                    $sentCount += $this->sendShiftReminders($dryRun);
                    $sentCount += $this->sendCheckinReminders($dryRun);
                    $sentCount += $this->sendCheckoutReminders($dryRun);
                    $sentCount += $this->sendMaintenanceReminders($dryRun);
                    break;
                default:
                    $this->error("Invalid reminder type: {$type}");
                    return 1;
            }

            $action = $dryRun ? 'Would send' : 'Sent';
            $this->info("{$action} {$sentCount} reminder notifications");

            return 0;

        } catch (\Exception $e) {
            $this->error("Error sending reminders: " . $e->getMessage());
            Log::error("Notification reminders failed", [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Send shift reminders to staff
     */
    protected function sendShiftReminders($dryRun = false): int
    {
        $this->line('Checking for upcoming shifts...');
        $sentCount = 0;

        // This is a placeholder implementation
        // You'll need to implement based on your actual shift/schedule system
        
        /*
        // Example implementation:
        $upcomingShifts = Shift::where('start_time', '>=', now())
            ->where('start_time', '<=', now()->addMinutes(30))
            ->whereDoesntHave('reminders', function ($query) {
                $query->where('type', 'shift_reminder')
                      ->where('sent_at', '>=', now()->subHours(1));
            })
            ->with('user')
            ->get();

        foreach ($upcomingShifts as $shift) {
            $minutesUntil = now()->diffInMinutes($shift->start_time);
            
            if ($dryRun) {
                $this->line("Would send shift reminder to {$shift->user->name} for shift starting in {$minutesUntil} minutes");
            } else {
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

                $this->line("Sent shift reminder to {$shift->user->name}");
            }
            
            $sentCount++;
        }
        */

        // Temporary implementation for demonstration
        $staffUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['receptionist', 'housekeeping']);
        })->get();

        foreach ($staffUsers as $user) {
            // Simulate checking if user has shift starting soon
            if (now()->hour >= 8 && now()->hour <= 18 && now()->minute % 30 == 0) {
                if ($dryRun) {
                    $this->line("Would send shift reminder to {$user->name}");
                } else {
                    $this->notificationService->sendToUsers(
                        [$user->id],
                        'Shift Reminder',
                        'Your shift is starting soon. Please prepare for your duties.',
                        ['user_id' => $user->id, 'reminder_type' => 'shift'],
                        '/admin/schedule'
                    );
                    $this->line("Sent shift reminder to {$user->name}");
                }
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Send checkin reminders
     */
    protected function sendCheckinReminders($dryRun = false): int
    {
        $this->line('Checking for upcoming checkins...');
        $sentCount = 0;

        // This is a placeholder - implement based on your booking system
        /*
        $upcomingCheckins = Booking::whereDate('checkin_date', today())
            ->where('status', 'confirmed')
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'checkin_reminder')
                      ->whereDate('created_at', today());
            })
            ->with(['customer', 'room'])
            ->get();

        foreach ($upcomingCheckins as $booking) {
            if ($dryRun) {
                $this->line("Would send checkin reminder for booking #{$booking->id} - {$booking->customer->name}");
            } else {
                NotificationHelper::checkinReminder($booking, $booking->customer);
                $this->line("Sent checkin reminder for booking #{$booking->id}");
            }
            $sentCount++;
        }
        */

        // Temporary implementation
        if (now()->hour == 10 && now()->minute < 15) { // Send at 10 AM
            $receptionists = User::whereHas('roles', function ($query) {
                $query->where('name', 'receptionist');
            })->get();

            foreach ($receptionists as $user) {
                if ($dryRun) {
                    $this->line("Would send daily checkin reminder to {$user->name}");
                } else {
                    $this->notificationService->sendToUsers(
                        [$user->id],
                        'Daily Checkin Reminder',
                        'Please review today\'s expected checkins and prepare rooms accordingly.',
                        ['reminder_type' => 'checkin', 'date' => today()->format('Y-m-d')],
                        '/admin/bookings?filter=checkin_today'
                    );
                    $this->line("Sent checkin reminder to {$user->name}");
                }
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Send checkout reminders
     */
    protected function sendCheckoutReminders($dryRun = false): int
    {
        $this->line('Checking for checkout reminders...');
        $sentCount = 0;

        // Send to housekeeping staff about rooms that need cleaning after checkout
        $housekeepingStaff = User::whereHas('roles', function ($query) {
            $query->where('name', 'housekeeping');
        })->get();

        foreach ($housekeepingStaff as $user) {
            if ($dryRun) {
                $this->line("Would send checkout reminder to {$user->name}");
            } else {
                $this->notificationService->sendToUsers(
                    [$user->id],
                    'Checkout Reminder',
                    'Please check for rooms that need cleaning after today\'s checkouts.',
                    ['reminder_type' => 'checkout', 'date' => today()->format('Y-m-d')],
                    '/admin/rooms?filter=needs_cleaning'
                );
                $this->line("Sent checkout reminder to {$user->name}");
            }
            $sentCount++;
        }

        return $sentCount;
    }

    /**
     * Send maintenance reminders
     */
    protected function sendMaintenanceReminders($dryRun = false): int
    {
        $this->line('Checking for maintenance reminders...');
        $sentCount = 0;

        // This is a placeholder - implement based on your maintenance system
        /*
        $scheduledMaintenance = MaintenanceSchedule::where('scheduled_date', today())
            ->where('status', 'scheduled')
            ->whereDoesntHave('notifications', function ($query) {
                $query->where('type', 'maintenance_reminder')
                      ->whereDate('created_at', today());
            })
            ->with('room')
            ->get();

        foreach ($scheduledMaintenance as $maintenance) {
            if ($dryRun) {
                $this->line("Would send maintenance reminder for room {$maintenance->room->number}");
            } else {
                NotificationHelper::roomMaintenanceRequired(
                    $maintenance->room,
                    $maintenance->description,
                    'normal',
                    null
                );
                $this->line("Sent maintenance reminder for room {$maintenance->room->number}");
            }
            $sentCount++;
        }
        */

        // Temporary implementation - send general maintenance reminder
        if (now()->hour % 4 == 0 && now()->minute < 15) { // Every 4 hours
            $maintenanceStaff = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['housekeeping', 'hotel_manager']);
            })->get();

            foreach ($maintenanceStaff as $user) {
                if ($dryRun) {
                    $this->line("Would send maintenance check reminder to {$user->name}");
                } else {
                    $this->notificationService->sendToUsers(
                        [$user->id],
                        'Maintenance Check Reminder',
                        'Please review any pending maintenance requests and room conditions.',
                        ['reminder_type' => 'maintenance', 'time' => now()->format('H:i')],
                        '/admin/maintenance'
                    );
                    $this->line("Sent maintenance reminder to {$user->name}");
                }
                $sentCount++;
            }
        }

        return $sentCount;
    }
}