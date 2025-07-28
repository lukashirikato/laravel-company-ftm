<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminRegisterController extends Controller
{
    /**
     * 🔐 Proses registrasi admin
     */
    public function register(Request $request)
    {
        // Jika sudah login admin, langsung ke dashboard admin
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        try {
            // Validasi input form
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[\pL\s\-]+$/u'
                ],
                'email' => 'required|email:rfc,dns|max:255|unique:admins,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$/'
                ],
            ], [
                'name.regex' => 'Nama hanya boleh mengandung huruf, spasi, dan tanda hubung.',
                'password.regex' => 'Password harus mengandung huruf besar, kecil, angka, dan simbol.',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validated = $validator->validated();

            // Simpan ke DB admins
            $admin = Admin::create([
                'name'     => strip_tags($validated['name']),
                'email'    => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'role'     => 'admin',
            ]);

            // Langsung login guard admin
            Auth::guard('admin')->login($admin);

            $request->session()->regenerate();

            Log::info('Admin berhasil registrasi', [
                'admin_id' => $admin->id,
                'email'    => $admin->email,
            ]);

            return redirect()->route('admin.home')->with('success', 'Registrasi admin berhasil. Selamat datang!');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            Log::error('Gagal registrasi admin', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * 🔐 Proses login admin
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required'],
            ]);

            // Gunakan guard admin
            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();

                // ⛔️ Cek role admin (jika ada kolom role)
                if (Auth::guard('admin')->user()->role !== 'admin') {
                    Auth::guard('admin')->logout();
                    return back()->withErrors([
                        'email' => 'Anda tidak memiliki akses sebagai admin.',
                    ]);
                }

                return redirect()->intended(route('admin.home'));
            }

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal login admin', [
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan saat login.')->withInput($request->except('password'));
        }
    }
}