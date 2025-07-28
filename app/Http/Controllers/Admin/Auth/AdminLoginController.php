<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends Controller
{
    /**
     * Menampilkan form login admin.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login'); // Pastikan view ini ada
    }

    /**
     * Menangani proses login admin.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard('admin')->user();

            // Opsional: Cek role jika perlu
            if ($user->role !== 'admin') {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Akses ditolak. Bukan admin.']);
            }

            Log::info('Admin login berhasil', ['admin_id' => $user->id]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, Admin.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'Anda telah logout.');
    }
}
