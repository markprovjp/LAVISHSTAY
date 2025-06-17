<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomModificationController extends Controller
{
    public function index()
    {
        return view('admin.room_modification.index');
    }
}
