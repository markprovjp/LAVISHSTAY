<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        // Fetch all rooms from the database
        // $room_types = 
        return view('admin.rooms.index');
    }

    
}
