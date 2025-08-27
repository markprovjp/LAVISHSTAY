<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SetupNotificationUsers extends Command
{
    protected $signature = 'notifications:setup-users';
    protected $description = 'Setup users to receive booking notifications';

    public function handle()
    {
        $this->info('🔧 Setting up notification users...');

        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->error('❌ No users found in the system!');
            return;
        }

        $this->info("📋 Found {$users->count()} users:");
        
        // Display users
        $headers = ['ID', 'Name', 'Email', 'Role'];
        $userData = [];
        
        foreach ($users as $user) {
            $userData[] = [
                $user->id,
                $user->name ?? 'N/A',
                $user->email ?? 'N/A',
                $user->role ?? 'N/A'
            ];
        }
        
        $this->table($headers, $userData);

        // Check if notification_users table exists
        if (!DB::getSchemaBuilder()->hasTable('notification_users')) {
            $this->error('❌ notification_users table does not exist!');
            $this->info('💡 Please run the migration first:');
            $this->info('php artisan migrate');
            return;
        }

        // Ask which users should receive booking notifications
        $selectedUserIds = [];
        
        if ($this->confirm('Do you want to configure users to receive booking_new notifications?')) {
            $userIdInput = $this->ask('Enter user IDs (comma-separated) who should receive booking_new notifications', '1');
            $selectedUserIds = array_map('trim', explode(',', $userIdInput));
        }

        if (empty($selectedUserIds)) {
            $this->warn('⚠️  No users selected for notifications.');
            return;
        }

        // Insert notification settings
        foreach ($selectedUserIds as $userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->warn("⚠️  User ID {$userId} not found, skipping...");
                continue;
            }

            // Check if already exists
            $exists = DB::table('notification_users')
                ->where('user_id', $userId)
                ->where('notification_type', 'booking_new')
                ->exists();

            if ($exists) {
                $this->info("ℹ️  User {$user->name} ({$userId}) already configured for booking_new notifications");
                continue;
            }

            // Insert new notification setting
            DB::table('notification_users')->insert([
                'user_id' => $userId,
                'notification_type' => 'booking_new',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->info("✅ User {$user->name} ({$userId}) configured to receive booking_new notifications");
        }

        $this->info('🎉 Notification setup completed!');
        
        // Test the setup
        if ($this->confirm('Do you want to test the notification setup?')) {
            $this->call('notifications:test-simple');
        }
    }
}