<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomMaintenanceRequired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $issue;
    public $priority;
    public $reportedBy;

    public function __construct($room, $issue, $priority = 'normal', $reportedBy = null)
    {
        $this->room = $room;
        $this->issue = $issue;
        $this->priority = $priority;
        $this->reportedBy = $reportedBy;
    }
}

class UrgentCleaningRequired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $deadline;
    public $reason;
    public $requestedBy;

    public function __construct($room, $deadline, $reason = null, $requestedBy = null)
    {
        $this->room = $room;
        $this->deadline = $deadline;
        $this->reason = $reason;
        $this->requestedBy = $requestedBy;
    }
}