<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
        // Jika sudah login, langsung ke dashboard
        if (Auth::check()) {
            return redirect()->route('admin.home');

        }

        try {
            // Validasi input form
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[\pL\s\-]+$/u' // huruf, spasi, strip
                ],
                'email' => 'required|email:rfc,dns|max:255|unique:users,email',
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

            // Simpan ke DB
            $user = User::create([
                'name'     => strip_tags($validated['name']),
                'email'    => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'role'     => 'admin', // Pastikan field 'role' tersedia di tabel users
            ]);

            // Langsung login setelah register
            Auth::login($user);

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            Log::info('Admin berhasil registrasi', [
                'user_id' => $user->id,
                'email'   => $user->email,
            ]);

            return redirect()->route('home')->with('success', 'Registrasi admin berhasil. Selamat datang!');
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

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // ⛔️ Cek role admin
                if (Auth::user()->role !== 'admin') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Anda tidak memiliki akses sebagai admin.',
                    ]);
                }

                return redirect()->intended(route('home'));
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