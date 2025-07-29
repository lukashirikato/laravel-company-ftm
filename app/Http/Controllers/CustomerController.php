<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\WhatsAppHelper;



class CustomerController extends Controller
{
    // ✅ METHOD VERIFIKASI CUSTOMER
    public function verifyCustomer($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->is_verified) {
            return back()->with('info', 'success', 'Customer berhasil diverifikasi.');
        }

        $password = Str::random(8);
        $customer->password = Hash::make($password);
        $customer->is_verified = true;
        $customer->save();

        // Kirim WhatsApp
        $message = "Assalamu'alaikum, {$customer->name}.\n\nAkun Anda telah diaktifkan.\n\n📧 Email: {$customer->email}\n🔑 Password: {$password}\n\nLogin: http://127.0.0.1:8000/login";
        WhatsAppHelper::send($customer->phone_number, $message);

        return back()->with('success', 'Customer berhasil diverifikasi dan info login dikirim via WhatsApp.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }
            /** @var \App\Models\Customer $customer */
    $customer = Auth::guard('customer')->user();

    if (!Hash::check($request->current_password, $customer->password)) {
        return back()->withErrors(['current_password' => 'Password lama salah.']);
    }
        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    // 📋 LIST CUSTOMER + JADWAL
public function index()
{
    $schedules = Schedule::all();

    $customers = Customer::with('schedules')->get()->map(function ($c) {
        return [
            'id'                   => $c->id,
            'user_id'              => $c->user_id ?? '-',
            'name'                 => $c->name,
            'email'                => $c->email,
            'phone_number'         => $c->phone_number,
            'program'              => $c->program ?? '-',
            'quota'                => $c->quota ?? 0,
            'membership'           => $c->membership ?? '-',
            'preferred_membership' => $c->preferred_membership ?? 'Not sure',
            'birth_date'           => $c->birth_date ?? '-',
            'age'                  => $c->birth_date ? \Carbon\Carbon::parse($c->birth_date)->age : '-',
            'schedule'             => $c->schedules->pluck('name')->implode(', '), // ambil dari relasi
            'goals'                => $c->goals ?? '-',
            'kondisi_khusus'       => $c->kondisi_khusus ?? '-',
            'referensi'            => $c->referensi ?? '-',
            'pengalaman'           => $c->pengalaman ?? '-',
            'is_muslim'            => $c->is_muslim ?? '-',
            'voucher_code'         => $c->voucher_code ?? '-',
            'verified'             => $c->is_verified ? '✔' : '❌',
            'is_verified'          => (bool) $c->is_verified,
            'created_at'           => optional($c->created_at)->toDateTimeString(),
            'updated_at'           => optional($c->updated_at)->toDateTimeString(),
        ];
    });

    return view('admin.customers.index', compact('schedules', 'customers'));
}


    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'                 => 'required|string|max:255',
        'email'                => 'required|email|max:255|unique:customers',
        'phone_number'         => 'required|string|max:15',
        'preferred_membership' => 'nullable|string',
        'birth_date'           => 'required|date',
        'goals'                => 'nullable|string',
        'kondisi_khusus'       => 'nullable|string',
        'referensi'            => 'nullable|string|max:255',
        'pengalaman'           => 'nullable|string|max:255',
        'is_muslim'            => 'required|in:ya,tidak',
        'voucher_code'         => 'nullable|string|max:100',
    ]);

    // Tambahkan user_id jika kolom tersedia dan user login
    

    // Simpan ke database
    Customer::create($validated);

    return $request->route()->getName() === 'public.customers.store'
        ? back()->with('success', 'Pendaftaran berhasil! Kami akan menghubungi Anda.')
        : redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan!');
}

    public function checkin($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->quota > 0) {
            $customer->decrement('quota');
            return back()->with('success', 'Check-in berhasil! Sisa kuota: ' . $customer->quota);
        }

        return back()->with('warning', 'Kuota sudah habis, tidak bisa check-in.');
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

public function edit($id)
{
    $customer = Customer::with('schedules')->findOrFail($id);
    $schedules = \App\Models\Schedule::all();
    $bookings = \App\Models\Booking::where('customer_id', $customer->id)->get(); // Tambahkan ini

    return view('admin.customers.edit', compact('customer', 'schedules', 'bookings'));
}

public function update(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    // Validasi input
    $validated = $request->validate([
        'name'                 => 'required|string|max:255',
        'email'                => 'required|email|max:255|unique:customers,email,' . $customer->id,
        'phone_number'         => 'required|string|max:15',
        'program'              => 'required|string|max:255',
        'quota'                => 'required|integer|min:0',
        'membership'           => 'required|string',
        'preferred_membership' => 'nullable|string',
        'schedule_ids'   => 'nullable|array',
        'schedule_ids.*' => 'exists:schedules,id',
        // Validasi booking
        'schedule_date' => 'nullable|array',
        'schedule_time' => 'nullable|array',
    ]);

    if (Schema::hasColumn('customers', 'user_id') && auth()->check()) {
        $validated['user_id'] = auth()->id();
    }

    $customer->update($validated);
    $customer->schedules()->sync($request->input('schedule_ids', []));

    // Update jadwal booking
    if ($request->has('schedule_date') && $request->has('schedule_time')) {
        foreach ($request->schedule_date as $bookingId => $date) {
            $booking = \App\Models\Booking::find($bookingId);
            if ($booking && $booking->customer_id == $customer->id) {
                $booking->schedule_date = $date;
                $booking->schedule_time = $request->schedule_time[$bookingId];
                $booking->program = $request->schedule_program[$bookingId];
                $booking->save();
            }
        }
    }

    return redirect()->route('customers.index')->with('success', 'Customer & jadwal booking berhasil diperbarui!');
}




    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus!');
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('member.login.form')->withErrors([
                'login' => 'Silakan login terlebih dahulu.'
            ]);
        }

        return view('member.profile', compact('customer'));
    }

    public function activateLoginView($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.activate-login', compact('customer'));
    }

   public function sendLogin($id)
{
    Log::info("[sendLogin] Dipanggil untuk ID: $id");

    $customer = Customer::findOrFail($id);
    Log::info("[sendLogin] Customer ditemukan: {$customer->name}");

    // Hardcoded password untuk debug
    $plainPassword = '69kfqymY'; // sementara hardcode untuk test
    $customer->password = Hash::make($plainPassword);
    $customer->is_verified = true;
    $customer->save();

    Log::info("DEBUG_PASSWORD: plain={$plainPassword}, hash={$customer->password}");
    Log::info("[sendLogin] Password disimpan dan is_verified diset ke true");

    $message = "Assalamu'alaikum, {$customer->name}.\n\n" .
        "Akun Anda telah diaktifkan di sistem kami.\n\n" .
        "🔐 *Login Info:*\n" .
        "📧 Email: {$customer->email}\n" .
        "🔑 Password: {$plainPassword}\n\n" .
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


    public function kirimPesanWelcome($id)
    {
        $customer = Customer::findOrFail($id);
        $message = "Selamat datang di FTM Society Gym Muslimah ✨💪\n\n" .
            "Kami senang menyambut Anda sebagai bagian dari komunitas kami 💖\n" .
            "Yuk mulai perjalanan sehatmu sekarang!";

        try {
            WhatsAppHelper::send($customer->phone_number, $message);
            return back()->with('success', 'Pesan WhatsApp berhasil dikirim!');
        } catch (\Exception $e) {
            Log::error("❌ Gagal kirim WA ke {$customer->phone_number}: " . $e->getMessage());
            return back()->with('error', 'Gagal mengirim pesan WhatsApp.');
        }
    }
}
