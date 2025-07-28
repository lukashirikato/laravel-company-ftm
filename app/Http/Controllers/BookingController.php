<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create($customer_id)
{
    $customer = Customer::findOrFail($customer_id);
    return view('bookings.create', compact('customer'));
}
public function createGeneral()
{
    // Untuk booking umum tanpa customer
    return view('bookings.create');
}

    public function store(Request $request)
{
    $request->validate([
        'customer_id'      => 'required|exists:customers,id',
        'schedule_date'    => 'required|array|min:1',
        'schedule_time'    => 'required|array|min:1',
        'schedule_program' => 'required|array|min:1', // tambahkan validasi ini
    ]);

    $saved = 0;
    foreach ($request->schedule_date as $i => $date) {
        if (
            !empty($date) &&
            !empty($request->schedule_time[$i]) &&
            !empty($request->schedule_program[$i]) // pastikan program diisi
        ) {
            Booking::create([
                'customer_id'   => $request->customer_id,
                'schedule_date' => $date,
                'schedule_time' => $request->schedule_time[$i],
                'program'       => $request->schedule_program[$i],
            ]);
            $saved++;
        }
    }

    if ($saved > 0) {
        return redirect()->route('customers.index')->with('success', 'Booking jadwal berhasil!');
    } else {
        return back()->with('error', 'Minimal isi satu jadwal!');
    }
}

    public function index(Request $request)
{
    $date = $request->input('date');
    $bookings = Booking::with('customer')
        ->when($date, function($query) use ($date) {
            $query->where('schedule_date', $date);
        })
        ->orderBy('created_at', 'desc') // tambahkan baris ini
        ->get();
    return view('bookings.index', compact('bookings', 'date'));
}

    public function bulkDelete(Request $request)
{
    $ids = $request->ids;
    if ($ids && is_array($ids)) {
        \App\Models\Booking::whereIn('id', $ids)->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking terpilih berhasil dihapus!');
    }
    return back()->with('error', 'Tidak ada booking yang dipilih.');
}

public function destroy($id)
{
    $booking = \App\Models\Booking::findOrFail($id);
    $booking->delete();

    return redirect()->route('bookings.index')->with('success', 'Jadwal berhasil dihapus!');
}
}

