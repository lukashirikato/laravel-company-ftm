<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ProfileController extends Controller
{
    public function show()
    {
        $customer = auth('customer')->user();
        $schedules = Schedule::all(); // Ambil semua jadwal dari database
        return view('member.profile', compact('customer', 'schedules'));
    }
}