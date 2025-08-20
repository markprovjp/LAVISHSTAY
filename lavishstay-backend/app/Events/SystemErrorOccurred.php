<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemErrorOccurred
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $errorMessage;
    public $errorCode;
    public $context;
    public $severity;

    public function __construct($errorMessage, $errorCode = null, $context = [], $severity = 'high')
    {
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
        $this->context = $context;
        $this->severity = $severity;
    }
}

class SystemMaintenanceScheduled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $startTime;
    public $endTime;
    public $description;
    public $affectedServices;

    public function __construct($startTime, $endTime, $description = null, $affectedServices = [])
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->description = $description;
        $this->affectedServices = $affectedServices;
    }
}

class StaffShiftReminder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $staff;
    public $shift;
    public $minutesUntilStart;

    public function __construct($staff, $shift, $minutesUntilStart)
    {
        $this->staff = $staff;
        $this->shift = $shift;
        $this->minutesUntilStart = $minutesUntilStart;
    }
}