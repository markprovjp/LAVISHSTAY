<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckinPolicyController extends Controller
{
    public function index(){
        return view('admin.policy.checkin-policies.index');
    }
}
