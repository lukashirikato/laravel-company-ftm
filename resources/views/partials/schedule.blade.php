<div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold mb-4 text-blue-700">Current Schedules</h2>
    <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-blue-100">
            <tr>
                <th class="border px-4 py-2 text-left">Class Name</th>
                <th class="border px-4 py-2 text-left">Day</th>
                <th class="border px-4 py-2 text-left">Time</th>
                <th class="border px-4 py-2 text-left">Instructor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules ?? [] as $schedule)
                <tr class="hover:bg-blue-50 transition">
                    <td class="border px-4 py-2">{{ $schedule->class_name }}</td>
                    <td class="border px-4 py-2">{{ $schedule->day }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($schedule->class_time)->format('H:i') }}</td>
                    <td class="border px-4 py-2">{{ $schedule->instructor }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">No schedules found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
