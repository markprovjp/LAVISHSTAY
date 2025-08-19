<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\NotificationType;
use App\Models\UserNotificationSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SyncUserNotificationSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:sync-user-settings 
                            {--user-id= : Sync settings for specific user ID}
                            {--role= : Sync settings for users with specific role}
                            {--dry-run : Show what would be created without actually creating}
                            {--force : Force recreate all settings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync notification settings for users who don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $role = $this->option('role');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('Starting user notification settings sync...');

        try {
            $users = $this->getTargetUsers($userId, $role);
            $notificationTypes = NotificationType::active()->get();

            if ($users->isEmpty()) {
                $this->info('No users found to sync.');
                return 0;
            }

            if ($notificationTypes->isEmpty()) {
                $this->error('No active notification types found.');
                return 1;
            }

            $this->info("Found {$users->count()} users and {$notificationTypes->count()} notification types");

            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;

            DB::beginTransaction();

            foreach ($users as $user) {
                $result = $this->syncUserSettings($user, $notificationTypes, $dryRun, $force);
                $createdCount += $result['created'];
                $updatedCount += $result['updated'];
                $skippedCount += $result['skipped'];

                if (!$dryRun) {
                    $this->line("Synced settings for user: {$user->name} ({$user->email})");
                }
            }

            if (!$dryRun) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            $action = $dryRun ? 'Would create/update' : 'Created/updated';
            $this->info("{$action} notification settings:");
            $this->line("  Created: {$createdCount}");
            $this->line("  Updated: {$updatedCount}");
            $this->line("  Skipped: {$skippedCount}");

            Log::info('User notification settings sync completed', [
                'users_processed' => $users->count(),
                'settings_created' => $createdCount,
                'settings_updated' => $updatedCount,
                'settings_skipped' => $skippedCount,
                'dry_run' => $dryRun
            ]);

            return 0;

        } catch (\Exception $e) {
            if (!$dryRun) {
                DB::rollBack();
            }

            $this->error("Sync failed: " . $e->getMessage());
            Log::error('User notification settings sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Get target users based on options
     */
    protected function getTargetUsers($userId, $role)
    {
        $query = User::query();

        if ($userId) {
            $query->where('id', $userId);
        } elseif ($role) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        return $query->with('roles')->get();
    }

    /**
     * Sync notification settings for a specific user
     */
    protected function syncUserSettings($user, $notificationTypes, $dryRun, $force): array
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($notificationTypes as $notificationType) {
            // Check if user should receive this notification type based on their roles
            if (!$this->userCanReceiveNotificationType($user, $notificationType)) {
                $skipped++;
                continue;
            }

            $existingSetting = UserNotificationSetting::where('user_id', $user->id)
                ->where('notification_type_id', $notificationType->id)
                ->first();

            if ($existingSetting && !$force) {
                $skipped++;
                continue;
            }

            $defaultSettings = $this->getDefaultSettingsForUserAndType($user, $notificationType);

            if ($dryRun) {
                if ($existingSetting) {
                    $this->line("  Would update setting for {$user->name} - {$notificationType->title}");
                    $updated++;
                } else {
                    $this->line("  Would create setting for {$user->name} - {$notificationType->title}");
                    $created++;
                }
                continue;
            }

            if ($existingSetting && $force) {
                $existingSetting->update($defaultSettings);
                $updated++;
            } else {
                UserNotificationSetting::create(array_merge([
                    'user_id' => $user->id,
                    'notification_type_id' => $notificationType->id,
                ], $defaultSettings));
                $created++;
            }
        }

        return [
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped
        ];
    }

    /**
     * Check if user can receive specific notification type based on their roles
     */
    protected function userCanReceiveNotificationType($user, $notificationType): bool
    {
        // If notification type has no target roles, everyone can receive it
        if (empty($notificationType->target_roles)) {
            return true;
        }

        // Check if user has any of the target roles
        $userRoles = $user->roles->pluck('name')->toArray();
        return !empty(array_intersect($userRoles, $notificationType->target_roles));
    }

    /**
     * Get default notification settings for user and notification type
     */
    protected function getDefaultSettingsForUserAndType($user, $notificationType): array
    {
        $userRoles = $user->roles->pluck('name')->toArray();

        // Default settings based on notification type and user role
        $defaults = [
            'is_enabled' => true,
            'email_enabled' => false,
            'push_enabled' => true,
        ];

        // Customize defaults based on notification type
        switch ($notificationType->name) {
            case 'system_error':
            case 'system_maintenance':
                // System notifications - only for admins and managers
                if (in_array('admin', $userRoles) || in_array('hotel_manager', $userRoles)) {
                    $defaults['email_enabled'] = true; // Important system notifications via email
                }
                break;

            case 'booking_new':
            case 'booking_cancelled':
            case 'booking_modified':
                // Booking notifications - for reception and management
                if (in_array('receptionist', $userRoles) || in_array('hotel_manager', $userRoles)) {
                    $defaults['email_enabled'] = false; // Too frequent for email
                    $defaults['push_enabled'] = true;
                }
                break;

            case 'payment_failed':
            case 'refund_requested':
                // Payment notifications - important for management
                if (in_array('admin', $userRoles) || in_array('hotel_manager', $userRoles)) {
                    $defaults['email_enabled'] = true;
                }
                break;

            case 'review_negative':
                // Negative reviews - important for management
                if (in_array('admin', $userRoles) || in_array('hotel_manager', $userRoles)) {
                    $defaults['email_enabled'] = true;
                    $defaults['push_enabled'] = true;
                }
                break;

            case 'room_maintenance':
            case 'room_cleaning_urgent':
                // Room notifications - for housekeeping and management
                if (in_array('housekeeping', $userRoles)) {
                    $defaults['push_enabled'] = true;
                    $defaults['email_enabled'] = false;
                } elseif (in_array('hotel_manager', $userRoles)) {
                    $defaults['email_enabled'] = true;
                }
                break;

            case 'staff_shift_reminder':
                // Shift reminders - only push notifications
                $defaults['email_enabled'] = false;
                $defaults['push_enabled'] = true;
                break;

            default:
                // Default settings for other notification types
                break;
        }

        // Override based on notification priority
        if ($notificationType->priority === 'urgent') {
            $defaults['push_enabled'] = true;
            if (in_array('admin', $userRoles) || in_array('hotel_manager', $userRoles)) {
                $defaults['email_enabled'] = true;
            }
        }

        return $defaults;
    }

    /**
     * Display sync statistics
     */
    protected function displaySyncStats($users, $notificationTypes): void
    {
        $this->info('Sync Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Users', $users->count()],
                ['Active Notification Types', $notificationTypes->count()],
                ['Potential Settings', $users->count() * $notificationTypes->count()],
            ]
        );

        // Show user role breakdown
        $roleBreakdown = $users->groupBy(function ($user) {
            return $user->roles->pluck('name')->implode(', ') ?: 'No Role';
        })->map->count();

        if ($roleBreakdown->isNotEmpty()) {
            $this->newLine();
            $this->info('User Role Breakdown:');
            $this->table(
                ['Role(s)', 'Count'],
                $roleBreakdown->map(function ($count, $roles) {
                    return [$roles, $count];
                })->toArray()
            );
        }

        // Show notification type breakdown
        $typeBreakdown = $notificationTypes->groupBy('priority')->map->count();
        
        if ($typeBreakdown->isNotEmpty()) {
            $this->newLine();
            $this->info('Notification Type Priority Breakdown:');
            $this->table(
                ['Priority', 'Count'],
                $typeBreakdown->map(function ($count, $priority) {
                    return [ucfirst($priority), $count];
                })->toArray()
            );
        }
    }

    /**
     * Validate existing settings and report inconsistencies
     */
    protected function validateExistingSettings(): array
    {
        $issues = [];

        // Find users without any notification settings
        $usersWithoutSettings = User::whereDoesntHave('notificationSettings')->count();
        if ($usersWithoutSettings > 0) {
            $issues[] = "{$usersWithoutSettings} users have no notification settings";
        }

        // Find notification settings for inactive notification types
        $settingsForInactiveTypes = UserNotificationSetting::whereHas('notificationType', function ($query) {
            $query->where('is_active', false);
        })->count();
        
        if ($settingsForInactiveTypes > 0) {
            $issues[] = "{$settingsForInactiveTypes} settings exist for inactive notification types";
        }

        // Find orphaned notification settings (user or notification type doesn't exist)
        $orphanedSettings = UserNotificationSetting::whereDoesntHave('user')
            ->orWhereDoesntHave('notificationType')
            ->count();
            
        if ($orphanedSettings > 0) {
            $issues[] = "{$orphanedSettings} orphaned notification settings found";
        }

        return $issues;
    }

    /**
     * Clean up orphaned or invalid settings
     */
    protected function cleanupInvalidSettings($dryRun = false): int
    {
        $this->info('Cleaning up invalid notification settings...');

        $cleanupCount = 0;

        // Remove settings for inactive notification types
        $inactiveTypeSettings = UserNotificationSetting::whereHas('notificationType', function ($query) {
            $query->where('is_active', false);
        });

        $inactiveCount = $inactiveTypeSettings->count();
        if ($inactiveCount > 0) {
            if ($dryRun) {
                $this->line("Would remove {$inactiveCount} settings for inactive notification types");
            } else {
                $inactiveTypeSettings->delete();
                $this->line("Removed {$inactiveCount} settings for inactive notification types");
            }
            $cleanupCount += $inactiveCount;
        }

        // Remove orphaned settings
        $orphanedSettings = UserNotificationSetting::whereDoesntHave('user')
            ->orWhereDoesntHave('notificationType');

        $orphanedCount = $orphanedSettings->count();
        if ($orphanedCount > 0) {
            if ($dryRun) {
                $this->line("Would remove {$orphanedCount} orphaned notification settings");
            } else {
                $orphanedSettings->delete();
                $this->line("Removed {$orphanedCount} orphaned notification settings");
            }
            $cleanupCount += $orphanedCount;
        }

        return $cleanupCount;
    }
}