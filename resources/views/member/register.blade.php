<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - FTM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-blue-700 mb-6 text-center">Register Admin</h2>

        {{-- Alert Section --}}
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-center">
                {{ $errors->first() }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-center">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-center">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.register') }}" autocomplete="off">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-semibold" for="name">Name</label>
                <input type="text" name="name" id="name" required
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="Admin Name">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-semibold" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="admin@example.com">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2 font-semibold" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="********">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-2 font-semibold" for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="********">
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700 transition font-semibold shadow">
                Register
            </button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>