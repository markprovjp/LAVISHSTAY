<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\UserNotificationSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NotificationBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:backup 
                            {--days=90 : Backup notifications older than specified days}
                            {--format=json : Backup format (json|csv|sql)}
                            {--compress : Compress backup files}
                            {--storage=local : Storage disk to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup notification data before cleanup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $format = $this->option('format');
        $compress = $this->option('compress');
        $storageDisk = $this->option('storage');

        $this->info("Starting notification backup process...");
        $this->info("Backing up notifications older than {$days} days in {$format} format");

        try {
            $cutoffDate = Carbon::now()->subDays($days);
            $backupPath = $this->createBackup($cutoffDate, $format, $compress, $storageDisk);
            
            $this->info("Backup completed successfully!");
            $this->info("Backup saved to: {$backupPath}");

            Log::info("Notification backup completed", [
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'format' => $format,
                'compressed' => $compress,
                'path' => $backupPath
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            Log::error("Notification backup failed", [
                'error' => $e->getMessage(),
                'days' => $days,
                'format' => $format
            ]);
            return 1;
        }
    }

    /**
     * Create backup of notification data
     */
    protected function createBackup($cutoffDate, $format, $compress, $storageDisk): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "notifications_backup_{$timestamp}";
        
        // Get data to backup
        $notifications = $this->getNotificationsToBackup($cutoffDate);
        $notificationTypes = NotificationType::all();
        $userSettings = UserNotificationSetting::all();

        $this->info("Found {$notifications->count()} notifications to backup");

        // Create backup data structure
        $backupData = [
            'metadata' => [
                'created_at' => now()->toDateTimeString(),
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'format' => $format,
                'total_notifications' => $notifications->count(),
                'total_types' => $notificationTypes->count(),
                'total_user_settings' => $userSettings->count(),
            ],
            'notifications' => $notifications->toArray(),
            'notification_types' => $notificationTypes->toArray(),
            'user_notification_settings' => $userSettings->toArray(),
        ];

        // Generate backup content based on format
        $content = $this->generateBackupContent($backupData, $format);
        
        // Determine file extension
        $extension = $this->getFileExtension($format, $compress);
        $fullFilename = $filename . $extension;

        // Compress if requested
        if ($compress) {
            $content = gzcompress($content, 9);
        }

        // Save to storage
        $disk = Storage::disk($storageDisk);
        $backupDir = 'backups/notifications';
        $fullPath = $backupDir . '/' . $fullFilename;

        $disk->put($fullPath, $content);

        return $disk->path($fullPath);
    }

    /**
     * Get notifications to backup
     */
    protected function getNotificationsToBackup($cutoffDate)
    {
        return Notification::with(['notificationType'])
            ->where('created_at', '<', $cutoffDate)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Generate backup content based on format
     */
    protected function generateBackupContent($data, $format): string
    {
        switch ($format) {
            case 'json':
                return json_encode($data, JSON_PRETTY_PRINT);
                
            case 'csv':
                return $this->generateCsvContent($data);
                
            case 'sql':
                return $this->generateSqlContent($data);
                
            default:
                throw new \InvalidArgumentException("Unsupported backup format: {$format}");
        }
    }

    /**
     * Generate CSV content
     */
    protected function generateCsvContent($data): string
    {
        $csv = "# Notification Backup - " . $data['metadata']['created_at'] . "\n";
        $csv .= "# Total Notifications: " . $data['metadata']['total_notifications'] . "\n\n";

        // Notifications CSV
        $csv .= "# NOTIFICATIONS\n";
        $csv .= "id,notification_type_id,notifiable_type,notifiable_id,title,message,data,priority,icon,color,url,read_at,status,created_at,updated_at\n";
        
        foreach ($data['notifications'] as $notification) {
            $csv .= implode(',', [
                $notification['id'],
                $notification['notification_type_id'] ?? '',
                '"' . ($notification['notifiable_type'] ?? '') . '"',
                $notification['notifiable_id'] ?? '',
                '"' . str_replace('"', '""', $notification['title'] ?? '') . '"',
                '"' . str_replace('"', '""', $notification['message'] ?? '') . '"',
                '"' . str_replace('"', '""', json_encode($notification['data'] ?? [])) . '"',
                $notification['priority'] ?? '',
                $notification['icon'] ?? '',
                $notification['color'] ?? '',
                $notification['url'] ?? '',
                $notification['read_at'] ?? '',
                $notification['status'] ?? '',
                $notification['created_at'] ?? '',
                $notification['updated_at'] ?? '',
            ]) . "\n";
        }

        return $csv;
    }

    /**
     * Generate SQL content
     */
    protected function generateSqlContent($data): string
    {
        $sql = "-- Notification Backup - " . $data['metadata']['created_at'] . "\n";
        $sql .= "-- Total Notifications: " . $data['metadata']['total_notifications'] . "\n\n";

        // Notification Types
        $sql .= "-- NOTIFICATION TYPES\n";
        foreach ($data['notification_types'] as $type) {
            $sql .= "INSERT INTO notification_types (id, name, title, message_template, priority, icon, color, target_roles, is_active, created_at, updated_at) VALUES (";
            $sql .= implode(', ', [
                $type['id'],
                "'" . addslashes($type['name']) . "'",
                "'" . addslashes($type['title']) . "'",
                "'" . addslashes($type['message_template']) . "'",
                "'" . $type['priority'] . "'",
                "'" . $type['icon'] . "'",
                "'" . $type['color'] . "'",
                "'" . json_encode($type['target_roles']) . "'",
                $type['is_active'] ? '1' : '0',
                "'" . $type['created_at'] . "'",
                "'" . $type['updated_at'] . "'"
            ]);
            $sql .= ");\n";
        }

        $sql .= "\n-- NOTIFICATIONS\n";
        foreach ($data['notifications'] as $notification) {
            $sql .= "INSERT INTO notifications (id, notification_type_id, notifiable_type, notifiable_id, title, message, data, priority, icon, color, url, read_at, status, created_at, updated_at) VALUES (";
            $sql .= implode(', ', [
                "'" . $notification['id'] . "'",
                $notification['notification_type_id'] ?? 'NULL',
                "'" . addslashes($notification['notifiable_type'] ?? '') . "'",
                $notification['notifiable_id'] ?? 'NULL',
                "'" . addslashes($notification['title'] ?? '') . "'",
                "'" . addslashes($notification['message'] ?? '') . "'",
                "'" . addslashes(json_encode($notification['data'] ?? [])) . "'",
                "'" . $notification['priority'] . "'",
                "'" . $notification['icon'] . "'",
                "'" . $notification['color'] . "'",
                "'" . $notification['url'] . "'",
                $notification['read_at'] ? "'" . $notification['read_at'] . "'" : 'NULL',
                "'" . $notification['status'] . "'",
                "'" . $notification['created_at'] . "'",
                "'" . $notification['updated_at'] . "'"
            ]);
            $sql .= ");\n";
        }

        return $sql;
    }

    /**
     * Get file extension based on format and compression
     */
    protected function getFileExtension($format, $compress): string
    {
        $extension = '.' . $format;
        
        if ($compress) {
            $extension .= '.gz';
        }
        
        return $extension;
    }
}