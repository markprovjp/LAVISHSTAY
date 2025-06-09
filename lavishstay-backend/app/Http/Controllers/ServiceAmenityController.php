<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceAmenityController extends Controller
{
    public function index(){
        return view('admin.services.amenities.index');
    }
}
