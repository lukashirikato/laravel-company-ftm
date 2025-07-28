<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTM Administration - Customers</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">
    <!-- Header Menu -->
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-700">FTM Admin Panel</h1>
            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Home</a>
                <a href="{{ route('feedback.index') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Feedback</a>
                <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">Schedules</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-blue-700">Customers Data</h2>
                <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm shadow">+ Add Customer</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-200">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Name</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Email</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Phone Number</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Created At</th>
                            <th class="py-3 px-6 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Updated At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="py-3 px-6">{{ $customer->name }}</td>
                                <td class="py-3 px-6">{{ $customer->email }}</td>
                                <td class="py-3 px-6">{{ $customer->phone_number }}</td>
                                <td class="py-3 px-6">{{ $customer->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 px-6">{{ $customer->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-6 text-center text-gray-500">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="mt-10 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} Gym Admin. All rights reserved.
    </footer>
</body>
</html>