<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingRescheduleController extends Controller
{
    public function index()
    {
        return view('admin.room_modification.booking_reschedules.index');
    }
}
