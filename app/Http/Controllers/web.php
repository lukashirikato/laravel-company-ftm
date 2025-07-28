<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ScheduleController;
// Ganti jika file controller admin Anda ada di Auth
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerAuthController;

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $schedules = Schedule::all();
    return view('welcome', compact('schedules'));
})->name('welcome');

Route::post('/customers', [CustomerController::class, 'store'])->name('public.customers.store');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::get('/send-whatsapp', [WhatsappController::class, 'sendWhatsapp']);

/*
|--------------------------------------------------------------------------
| 👤 CUSTOMER / MEMBER LOGIN (Guard: customer)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:customer')->group(function () {
    Route::get('/login-member', [CustomerAuthController::class, 'showLoginForm'])->name('member.login.form');
    Route::post('/login-member', [CustomerAuthController::class, 'login'])->name('member.login');
});

/*
|--------------------------------------------------------------------------
| 👤 CUSTOMER AUTH ROUTES (Guard: customer)
|--------------------------------------------------------------------------
*/
Route::prefix('member')->middleware('auth:customer')->group(function () {
    Route::get('/profile', [CustomerController::class, 'profile'])->name('member.profile');
    Route::get('/program-saya', [CustomerController::class, 'program'])->name('member.program');
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('member.logout');

    // Optional (change password untuk member)
    Route::get('/change-password', [MemberAuthController::class, 'showChangePasswordForm'])->name('member.password.form');
    Route::post('/change-password', [MemberAuthController::class, 'changePassword'])->name('member.password.update');
});

/*
|--------------------------------------------------------------------------
| 🔐 LEGACY MEMBER ROUTES (Deprecated)
|--------------------------------------------------------------------------
*/
Route::prefix('member')->middleware('guest')->group(function () {
    Route::get('/login-old', [MemberAuthController::class, 'showLoginForm'])->name('member.login.old');
    Route::post('/login-old', [MemberAuthController::class, 'login'])->name('member.login.submit.old');
    Route::get('/register', [MemberAuthController::class, 'showRegisterForm'])->name('member.register');
    Route::post('/register', [MemberAuthController::class, 'register'])->name('member.register.submit');
});

/*
|--------------------------------------------------------------------------
| 🔐 ADMIN AUTH ROUTES (Guard: admin)
|--------------------------------------------------------------------------
*/
Route::prefix('adm')->middleware('guest:admin')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.login.form'));

    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login.form');
    Route::get('/register', fn() => view('register'))->name('admin.register.form');

    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login');
    Route::post('/register', [AdminRegisterController::class, 'register'])->name('admin.register');
});

/*
|--------------------------------------------------------------------------
| 🛡️ ADMIN PANEL ROUTES (Guard: admin)
|--------------------------------------------------------------------------
*/
Route::prefix('adm')->middleware('auth:admin')->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::get('/', fn() => view('home'))->name('admin.home');

    Route::resource('customers', CustomerController::class);
    Route::post('/customers/{id}/checkin', [CustomerController::class, 'checkin'])->name('customers.checkin');
    Route::get('/customers/{id}/activate-login', [CustomerController::class, 'activateLoginView'])->name('customers.activate-login');
    Route::post('/customers/{id}/activate-login', [CustomerController::class, 'activateLogin'])->name('customers.activateLogin');
    Route::post('/customers/{id}/welcome', [CustomerController::class, 'kirimPesanWelcome'])->name('customers.welcome');
    Route::post('/customers/{id}/send-login', [CustomerController::class, 'sendLogin'])->name('customers.send-login');

    Route::resource('schedules', ScheduleController::class);
    Route::resource('programs', ProgramController::class);

    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/users/{id}/approve', [UserController::class, 'approve'])->name('admin.users.approve');

    Route::get('/members/create', [MemberAuthController::class, 'createByAdmin'])->name('admin.members.create');
    Route::post('/members/store', [MemberAuthController::class, 'storeByAdmin'])->name('admin.members.store');

    Route::get('/profile', [MemberAuthController::class, 'profile'])->name('admin.profile');
});

/*
|--------------------------------------------------------------------------
| 🧩 PROFILE EDIT (Guard: default auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});