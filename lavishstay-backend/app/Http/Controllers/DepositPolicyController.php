<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepositPolicyController extends Controller
{
    public function index(){
        return view('admin.policy.deposit-policies.index');
    }
}
