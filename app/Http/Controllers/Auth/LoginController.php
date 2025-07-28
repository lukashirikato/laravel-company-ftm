<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ...existing code...

    protected function credentials(Request $request)
{
    $login = $request->input('login');

    return [
        filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number' => $login,
        'password' => $request->input('password'),
        'is_verified' => true, // hanya yang terverifikasi bisa login
    ];
}


    // ...existing code...
}