<?php

namespace App\Listeners;

use App\Events\RoomMaintenanceRequired;
use App\Events\UrgentCleaningRequired;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class RoomNotificationListener
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle room maintenance required event
     */
    public function handleRoomMaintenanceRequired(RoomMaintenanceRequired $event)
    {
        try {
            $data = [
                'room_id' => $event->room->id ?? 'N/A',
                'room_number' => $event->room->number ?? 'N/A',
                'issue' => $event->issue,
                'priority' => $event->priority,
                'reported_by' => $event->reportedBy->name ?? 'System',
                'reported_at' => now()->format('d/m/Y H:i'),
            ];

            $this->notificationService->sendRoomNotification('maintenance', $data);

            Log::info('Room maintenance notification sent', [
                'room_id' => $event->room->id,
                'issue' => $event->issue
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send room maintenance notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle urgent cleaning required event
     */
    public function handleUrgentCleaningRequired(UrgentCleaningRequired $event)
    {
        try {
            $data = [
                'room_id' => $event->room->id ?? 'N/A',
                'room_number' => $event->room->number ?? 'N/A',
                'time' => $event->deadline->format('H:i'),
                'deadline' => $event->deadline->format('d/m/Y H:i'),
                'reason' => $event->reason ?? 'Urgent cleaning required',
                'requested_by' => $event->requestedBy->name ?? 'System',
            ];

            $this->notificationService->sendRoomNotification('cleaning_urgent', $data);

            Log::info('Urgent cleaning notification sent', [
                'room_id' => $event->room->id,
                'deadline' => $event->deadline
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send urgent cleaning notification: ' . $e->getMessage());
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events)
    {
        $events->listen(
            RoomMaintenanceRequired::class,
            [RoomNotificationListener::class, 'handleRoomMaintenanceRequired']
        );

        $events->listen(
            UrgentCleaningRequired::class,
            [RoomNotificationListener::class, 'handleUrgentCleaningRequired']
        );
    }
}