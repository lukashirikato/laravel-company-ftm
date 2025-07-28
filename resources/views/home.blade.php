<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>FTM Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-white via-blue-50 to-blue-100 min-h-screen flex flex-col">

    <!-- Top Navbar -->
    <header class="bg-white shadow sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-extrabold text-blue-700 tracking-wide">FTM Admin</div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="flex items-center text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md shadow transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                         d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10v1m0 4a9 9 0 11-9-9 9 9 0 019 9z" /></svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="text-center mt-14 mb-10 px-4">
        <h1 class="text-4xl font-bold text-blue-800">Welcome, {{ Auth::user()->name ?? 'Admin' }}!</h1>
        <p class="text-gray-600 mt-2 text-lg">Manage your gym operations with confidence and clarity.</p>
    </section>

    <!-- Dashboard Cards -->
    <main class="flex-grow">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-16">
            <!-- Customers Card -->
            <a href="{{ route('customers.index') }}" class="group bg-white border border-gray-200 hover:border-blue-400 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 flex flex-col items-center text-center">
                <div class="bg-blue-100 text-blue-600 p-4 rounded-full mb-4 transition group-hover:bg-blue-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1
                              m1-4a4 4 0 110-8 4 4 0 010 8zm6 4a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-blue-700">Customers</h2>
                <p class="text-gray-500 mt-1 text-sm">Manage all member data</p>
            </a>

            <!-- Feedback Card -->
            <a href="{{ route('feedback.index') }}" class="group bg-white border border-gray-200 hover:border-green-400 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 flex flex-col items-center text-center">
                <div class="bg-green-100 text-green-600 p-4 rounded-full mb-4 transition group-hover:bg-green-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 10h.01M12 10h.01M16 10h.01M21 16V8a2 2 0 00-2-2H5a2 2 0
                              00-2 2v8a2 2 0 002 2h4l3 3 3-3h4a2 2 0 002-2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-700">Feedback</h2>
                <p class="text-gray-500 mt-1 text-sm">Read and respond to feedback</p>
            </a>

            <!-- Schedules Card -->
            <a href="{{ route('schedules.index') }}" class="group bg-white border border-gray-200 hover:border-purple-400 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 p-6 flex flex-col items-center text-center">
                <div class="bg-purple-100 text-purple-600 p-4 rounded-full mb-4 transition group-hover:bg-purple-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2
                              0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-purple-700">Schedules</h2>
                <p class="text-gray-500 mt-1 text-sm">Organize gym sessions</p>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-gray-500 text-sm py-6 bg-white border-t">
        &copy; {{ date('Y') }} Gym Admin. All rights reserved.
    </footer>

</body>
</html>
