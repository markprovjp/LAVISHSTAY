<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutPolicyController extends Controller
{
    public function index(){
        return view('admin.policy.checkout-policies.index');
    }
}
