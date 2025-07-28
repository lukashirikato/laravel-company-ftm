<!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\admin\customers\edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <h2 class="text-3xl font-bold mb-8 text-blue-700 text-center">Edit Data Member & Jadwal Booking</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4 bg-white rounded-xl shadow p-6">
                    <div>
                        <label class="block mb-1 font-semibold text-blue-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full border px-3 py-2 rounded focus:ring focus:ring-blue-200" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-blue-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full border px-3 py-2 rounded focus:ring focus:ring-blue-200" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-blue-700">No. HP</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" class="w-full border px-3 py-2 rounded focus:ring focus:ring-blue-200" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-blue-700">Program / Kelas</label>
                        <input type="text" name="program" value="{{ old('program', $customer->program) }}" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-blue-700">Kuota</label>
                        <input type="number" name="quota" min="0" value="{{ old('quota', $customer->quota) }}" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
                    </div>
                    @php
                        $memberships = [
                            "Exclusive Class Program",
                            "Reformer PilatesSingle Visit Group Class",
                            "Single Visit Class",
                            "Private Program",
                            "Reformer Pilates Packages",
                            "Private Group Program"
                        ];
                        $selectedMembership = old('membership', $customer->membership);
                        $selectedPreferred = old('preferred_membership', $customer->preferred_membership);
                    @endphp
                    <div>
                        <label class="block font-semibold mb-1 text-blue-700">Membership</label>
                        <select name="membership" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
                            <option value="">-- Pilih Membership --</option>
                            @foreach ($memberships as $item)
                                <option value="{{ $item }}" {{ $selectedMembership === $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-blue-700">Preferred Membership</label>
                        <select name="preferred_membership" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                            <option value="">-- Pilih Preferred --</option>
                            @foreach ($memberships as $item)
                                <option value="{{ $item }}" {{ $selectedPreferred === $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                            <option value="Not sure" {{ $selectedPreferred === 'Not sure' ? 'selected' : '' }}>
                                Not sure
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="schedule_ids" class="block font-semibold mb-1 text-blue-700">Pilih Jadwal Program</label>
                        <select name="schedule_ids[]" id="schedule_ids" multiple class="form-select w-full focus:ring focus:ring-blue-200">
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->id }}"
                                    {{ isset($customer) && $customer->schedules->contains($schedule->id) ? 'selected' : '' }}>
                                    {{ $schedule->class_name }} - {{ $schedule->day }} ({{ \Carbon\Carbon::parse($schedule->class_time)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-gray-500">Gunakan Ctrl (Windows) / Command (Mac) untuk memilih lebih dari satu jadwal.</small>
                    </div>
                </div>

               <!-- Jadwal Booking Member Full Section -->
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-bold mb-6 text-blue-700 text-xl text-center">Edit Jadwal Booking Member</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-blue-200 rounded-lg text-sm bg-blue-50">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-2 text-center font-semibold text-blue-700">Visit</th>
                    <th class="p-2 text-center font-semibold text-blue-700">Tanggal</th>
                    <th class="p-2 text-center font-semibold text-blue-700">Program/Kelas</th> <!-- Tambahan -->
                    <th class="p-2 text-center font-semibold text-blue-700">Jam</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td class="p-2 text-center font-semibold text-blue-700">Visit {{ $loop->iteration }}</td>
                    <td class="p-2">
                        <input type="date" name="schedule_date[{{ $booking->id }}]" value="{{ $booking->schedule_date }}" class="border rounded px-3 py-2 w-full focus:ring focus:ring-blue-200">
                    </td>
                    <td class="p-2"> <!-- Tambahan kolom program -->
                        <select name="schedule_program[{{ $booking->id }}]" class="border rounded px-3 py-2 w-full focus:ring focus:ring-blue-200">
                            <option value="">Pilih Program</option>
                            <option value="Muaythai" {{ $booking->program === 'Muaythai' ? 'selected' : '' }}>Muaythai</option>
                            <option value="Body Shaping" {{ $booking->program === 'Body Shaping' ? 'selected' : '' }}>Body Shaping</option>
                            <option value="Mat Pilates" {{ $booking->program === 'Mat Pilates' ? 'selected' : '' }}>Mat Pilates</option>
                            <option value="Reformer Pilates" {{ $booking->program === 'Reformer Pilates' ? 'selected' : '' }}>Reformer Pilates</option>
                        </select>
                    </td>
                    <td class="p-2">
                        <input type="time" name="schedule_time[{{ $booking->id }}]" value="{{ $booking->schedule_time }}" class="border rounded px-3 py-2 w-full focus:ring focus:ring-blue-200">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
            <div class="flex justify-between mt-8">
                <a href="{{ route('customers.index') }}" class="px-6 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold">Update</button>
            </div>
        </form>
    </div>
</body>
</html>