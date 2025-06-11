<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin payment management page
     */
    public function paymentManagement()
    {
        return view('admin.payment');
    }
}
