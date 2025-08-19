<?php

namespace App\Listeners;

use App\Events\SystemErrorOccurred;
use App\Events\SystemMaintenanceScheduled;
use App\Events\StaffShiftReminder;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SystemNotificationListener
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle system error occurred event
     */
    public function handleSystemErrorOccurred(SystemErrorOccurred $event)
    {
        try {
            $data = [
                'error_message' => $event->errorMessage,
                'error_code' => $event->errorCode ?? 'N/A',
                'severity' => $event->severity,
                'occurred_at' => now()->format('d/m/Y H:i:s'),
                'context' => !empty($event->context) ? json_encode($event->context) : 'No additional context',
            ];

            $this->notificationService->sendSystemNotification('error', $data);

            Log::error('System error notification sent', [
                'error' => $event->errorMessage,
                'code' => $event->errorCode,
                'severity' => $event->severity
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send system error notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle system maintenance scheduled event
     */
    public function handleSystemMaintenanceScheduled(SystemMaintenanceScheduled $event)
    {
        try {
            $data = [
                'start_time' => $event->startTime->format('d/m/Y H:i'),
                'end_time' => $event->endTime->format('d/m/Y H:i'),
                'duration' => $event->startTime->diffInMinutes($event->endTime) . ' minutes',
                'description' => $event->description ?? 'Scheduled system maintenance',
                'affected_services' => !empty($event->affectedServices) ? implode(', ', $event->affectedServices) : 'All services',
                'scheduled_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendSystemNotification('maintenance', $data);

            Log::info('System maintenance notification sent', [
                'start' => $event->startTime,
                'end' => $event->endTime
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send system maintenance notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle staff shift reminder event
     */
    public function handleStaffShiftReminder(StaffShiftReminder $event)
    {
        try {
            $data = [
                'staff_name' => $event->staff->name ?? 'Staff',
                'shift_start' => $event->shift->start_time ?? 'N/A',
                'shift_end' => $event->shift->end_time ?? 'N/A',
                'minutes' => $event->minutesUntilStart,
                'department' => $event->staff->department ?? 'N/A',
                'position' => $event->staff->position ?? 'N/A',
            ];

            // Send to specific user only
            $this->notificationService->sendByType('staff_shift_reminder', $data, [$event->staff->id]);

            Log::info('Staff shift reminder sent', [
                'staff_id' => $event->staff->id,
                'minutes_until' => $event->minutesUntilStart
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send staff shift reminder: ' . $e->getMessage());
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events)
    {
        $events->listen(
            SystemErrorOccurred::class,
            [SystemNotificationListener::class, 'handleSystemErrorOccurred']
        );

        $events->listen(
            SystemMaintenanceScheduled::class,
            [SystemNotificationListener::class, 'handleSystemMaintenanceScheduled']
        );

        $events->listen(
            StaffShiftReminder::class,
            [SystemNotificationListener::class, 'handleStaffShiftReminder']
        );
    }
}