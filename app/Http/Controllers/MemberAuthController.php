<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Helpers\WhatsAppHelper;

class MemberAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('member.login');
    }

    public function showRegisterForm()
    {
        return view('member.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:customers,email',
            'phone_number'    => 'required|string|unique:customers,phone_number',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Customer::create([
            'name'           => strip_tags($request->name),
            'email'          => strtolower(trim($request->email)),
            'phone_number'   => $request->phone_number,
            'password'       => Hash::make($request->password),
            'is_verified'    => false,
            'credit_balance' => 0,
        ]);

        return redirect()->route('member.login.form')
            ->with('success', 'Pendaftaran berhasil. Silakan tunggu verifikasi dari admin.');
    }

public function login(Request $request)
{
    $request->validate([
        'login'    => 'required|string',
        'password' => 'required|string|min:6',
    ]);

    $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

    $customer = Customer::where($loginField, $request->login)->first();

    if (!$customer) {
        return back()->withErrors(['login' => 'Akun tidak ditemukan'])->withInput();
    }

    if (!Hash::check($request->password, $customer->password)) {
        return back()->withErrors(['login' => 'Email/No HP atau password salah.'])->withInput();
    }

    if (!$customer->is_verified) {
        return back()->withErrors(['login' => 'Akun Anda belum diverifikasi oleh admin.'])->withInput();
    }

    Auth::guard('customer')->login($customer);
    $request->session()->regenerate();

    if ($customer->force_password_change) {
        return redirect()->route('member.change-password')
                         ->with('warning', 'Silakan ubah password Anda terlebih dahulu.');
    }

    return redirect()->route('member.profile')->with('success', 'Login berhasil.');
}


    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout.');
    }

public function profile()
{
    $customer = Auth::guard('customer')->user();

    // Ambil jadwal sesuai program yang dimiliki member
    $schedules = $customer->schedules; // langsung dari relasi many-to-many


    return view('member.profile', [
        'customer'  => $customer,
        'credit'    => $customer->credit_balance ?? 0,
        'packages'  => $customer->memberships ?? [],
        'schedules' => $schedules,
    ]);
}

    

    public function storeByAdmin(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:customers,email',
            'phone_number' => 'required|string|unique:customers,phone_number',
        ]);

        $defaultPassword = 'member123!';

        Customer::create([
            'name'           => strip_tags($request->name),
            'email'          => strtolower(trim($request->email)),
            'phone_number'   => $request->phone_number,
            'password'       => Hash::make($defaultPassword),
            'is_verified'    => true,
            'credit_balance' => 0,
        ]);

        return redirect()->back()->with('success', 'Akun member berhasil dibuat. Password default: ' . $defaultPassword);
    }

    public function showChangePasswordForm()
    {
        return view('member.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = Auth::guard('customer')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        $customer = Customer::find($user->id);
        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return redirect()->route('member.profile')->with('success', 'Password berhasil diubah!');
    }

    public function sendLogin($id)
    {
        Log::info("[sendLogin] Dipanggil untuk ID: $id");

        $customer = Customer::findOrFail($id);
        Log::info("[sendLogin] Customer ditemukan: {$customer->name}");

    $plainPassword = '69kfqymY';
    $customer->password = Hash::make($plainPassword);
    $customer->is_verified = true;
    $customer->save();

    Log::info("DEBUG_PASSWORD: plain={$plainPassword}, hash={$customer->password}");
    Log::info("DEBUG: Password tersimpan? " . ($customer->wasChanged('password') ? 'YA' : 'TIDAK'));
    Log::info("[sendLogin] Password disimpan dan is_verified diset ke true");

        $message = "Assalamu'alaikum, {$customer->name}.\n\n" .
            "Akun Anda telah diaktifkan di sistem kami.\n\n" .
            "\ud83d\udd10 *Login Info:*\n" .
            "\ud83d\udce7 Email: {$customer->email}\n" .
            "\ud83d\udd11 Password: {$plainPassword}\n\n" .
            "Silakan login di sini:\n" .
            url('/login');

        try {
            WhatsAppHelper::send($customer->phone_number, $message);
            Log::info("[sendLogin] WhatsAppHelper dijalankan untuk nomor {$customer->phone_number}");
        } catch (\Throwable $e) {
            Log::error("[sendLogin] Gagal mengirim WA: " . $e->getMessage());
        }

        return back()->with('success', 'Akses login dikirim via WhatsApp!');
    }
}
