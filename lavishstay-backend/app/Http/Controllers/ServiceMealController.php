<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceMealController extends Controller
{
    public function index(){
        return view('admin.services.meals.index');
    }
}
