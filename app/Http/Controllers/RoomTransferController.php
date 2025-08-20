<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomTransferController extends Controller
{
    public function index(){
        return view('admin.room_modification.room_transfers.index');
    }
}
