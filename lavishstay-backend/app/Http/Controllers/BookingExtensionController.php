<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingExtensionController extends Controller
{
    public function index()
    {
        return view('admin.room_modification.booking_extensions.index');
    }
}
