<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password | FTM Society Gym Muslimah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px);}
            to { opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-200 via-pink-100 to-purple-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md fade-in">

        <!-- Header -->
        <div class="text-center mb-6">
            <img src="{{ asset('icons/logo-ftm.jpg') }}" alt="Logo Gym" class="w-20 mx-auto mb-3 rounded-full shadow-lg">
            <h1 class="text-2xl font-bold text-purple-700 flex items-center justify-center gap-2">
                <i class="ri-key-2-line"></i> Ganti Password
            </h1>
            <p class="text-sm text-gray-500">Lindungi akunmu dengan password baru yang lebih aman</p>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded text-sm shadow-sm flex items-center gap-2">
                <i class="ri-checkbox-circle-line"></i> {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "{{ route('member.profile') }}";
                }, 1800);
            </script>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 mb-4 rounded text-sm shadow-sm flex items-center gap-2">
                <i class="ri-error-warning-line"></i> {{ $errors->first() }}
            </div>
        @endif

        <!-- Password Tips -->
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded mb-6 text-xs flex items-center gap-2">
            <i class="ri-information-line"></i>
            Gunakan minimal 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol agar password lebih kuat.
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('member.change-password') }}" class="space-y-5">
            @csrf
            <!-- Password Lama -->
            <div>
                <label for="current_password" class="block text-sm text-gray-700 mb-1">Password Lama</label>
                <input type="password" id="current_password" name="current_password" required
                       placeholder="Masukkan password lama"
                       class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 transition">
            </div>

            <!-- Password Baru -->
            <div>
                <label for="new_password" class="block text-sm text-gray-700 mb-1">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required
                       placeholder="Minimal 8 karakter"
                       class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 transition">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="new_password_confirmation" class="block text-sm text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                       placeholder="Ulangi password baru"
                       class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 transition">
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                    class="w-full bg-purple-600 text-white px-6 py-2 rounded-xl hover:bg-pink-500 hover:scale-105 hover:shadow-lg transition-all font-semibold text-sm flex items-center justify-center gap-2">
                <i class="ri-save-line"></i> Simpan Perubahan
            </button>
        </form>

        <!-- Info -->
        <div class="mt-6 text-xs text-gray-400 text-center">
            Jika mengalami kendala, hubungi admin melalui WhatsApp.
        </div>
    </div>
</body>
</html>