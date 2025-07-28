<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('member.login.form')->withErrors(['Silakan login terlebih dahulu.']);
        }

        return view('member.profile', compact('customer'));
    }
}
