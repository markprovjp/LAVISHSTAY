<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceBedController extends Controller
{
    public function index(){
        return view('admin.services.beds.index');
    }
}
