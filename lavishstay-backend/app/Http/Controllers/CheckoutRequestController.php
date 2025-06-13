<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutRequestController extends Controller
{
    public function index()
    {
        return view('admin.room_modification.checkout_requests.index');
    }
}
