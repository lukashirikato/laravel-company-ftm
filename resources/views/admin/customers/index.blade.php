<!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\admin\customers\index.blade.php -->
<!DOCTYPE html>
<html lang="en" x-data="{ showDelete: false, deleteId: null, deleteName: '' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTM Admin - Customers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('customerSearch', { q: '' });
        });
    </script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen font-sans">

@if(session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2500)" x-show="show"
         x-transition class="fixed top-8 left-1/2 -translate-x-1/2 bg-green-500 text-white px-8 py-4 rounded-xl shadow-xl z-50 text-lg font-semibold flex items-center gap-3"
         style="min-width: 250px;">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
         x-transition class="fixed top-8 left-1/2 -translate-x-1/2 bg-red-500 text-white px-8 py-4 rounded-xl shadow-xl z-50 text-lg font-semibold flex items-center gap-3"
         style="min-width: 250px;">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        {{ session('error') }}
    </div>
@endif
@if(session('info'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
         x-transition class="fixed top-8 left-1/2 -translate-x-1/2 bg-blue-500 text-white px-8 py-4 rounded-xl shadow-xl z-50 text-lg font-semibold flex items-center gap-3"
         style="min-width: 250px;">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01" />
        </svg>
        {{ session('info') }}
    </div>
@endif

<nav class="bg-white shadow-md sticky top-0 z-40">
    <div class="container mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <h1 class="text-2xl font-bold text-blue-700">FTM Admin Panel</h1>
        <div class="flex flex-wrap gap-2 items-center">
            <a href="{{ route('admin.home') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Home</a>
            <a href="{{ route('feedback.index') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Feedback</a>
            <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-sm">Schedules</a>
            <div x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour12: false }) }"
                 x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour12: false }), 1000)"
                 class="text-sm text-gray-700 font-semibold px-2">
                🕒 <span x-text="time"></span>
            </div>
        </div>
    </div>
</nav>

<main class="container mx-auto px-2 md:px-4 py-6" x-data="{
    customers: @js($customers),
    showCheckinSuccess: false,
    get filtered() {
        if (!$store.customerSearch.q) return this.customers;
        return this.customers.filter(c => c.name?.toLowerCase().includes($store.customerSearch.q.toLowerCase()));
    },
    async checkin(customer) {
        let res = await fetch(`/adm/customers/${customer.id}/checkin`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        if (res.ok) {
            customer.quota--;
            this.showCheckinSuccess = true;
            setTimeout(() => this.showCheckinSuccess = false, 2000);
        } else {
            alert('Gagal cek in!');
        }
    }
}">

    <div x-show="showCheckinSuccess" x-transition
         class="fixed top-8 left-1/2 -translate-x-1/2 bg-blue-600 text-white px-8 py-4 rounded-xl shadow-xl z-50 text-lg font-semibold flex items-center gap-3"
         style="min-width: 250px;">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        Cek In Berhasil!
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-xl md:text-2xl font-bold text-blue-800">Customers Data</h2>
        <div class="flex gap-2 items-center w-full md:w-auto">
            <input x-model="$store.customerSearch.q" type="text" placeholder="Cari nama customer..."
                   class="border border-blue-300 rounded-lg px-3 py-2 text-sm w-full md:w-48 focus:ring-blue-400 focus:outline-none focus:ring-2">
            <a href="{{ route('customers.create') }}"
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm shadow font-semibold">+ Add Customer</a>
            <a href="{{ route('bookings.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Daftar Booking</a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-sm">
        <table class="min-w-full bg-white text-xs md:text-sm">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-300 text-white font-bold uppercase tracking-wider">
                <tr>
                    <th class="py-3 px-4 text-left">Full Name</th>
                    <th class="py-3 px-4 text-left">Phone Number</th>
                    <th class="py-3 px-4 text-left">Email</th>
                   <th class="py-3 px-4 text-left">Tanggal Lahir / Umur</th>
                    <th class="py-3 px-4 text-left">Program yang diminati</th>
                    <th class="py-3 px-4 text-left">Jadwal Pilihan</th>
                    <th class="py-3 px-4 text-left">Quota</th>
                    <th class="py-3 px-4 text-left">Membership</th>
                    <th class="py-3 px-4 text-left">Tujuan Anda</th>
                    <th class="py-3 px-4 text-left">Kondisi Khusus</th>
                    <th class="py-3 px-4 text-left">Mengenal FTM dari</th>
                    <th class="py-3 px-4 text-left">Pengalaman</th>
                    <th class="py-3 px-4 text-left">Muslim</th>
                    <th class="py-3 px-4 text-left">Voucher</th>
                    <th class="py-3 px-4 text-left">Preferred</th>
                    <th class="py-3 px-4 text-left">Created</th>
                    <th class="py-3 px-4 text-left">Updated</th>
                    <th class="py-3 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-blue-100">
                <tr x-show="filtered.length === 0">
                    <td colspan="18" class="py-4 px-6 text-center text-gray-500">No customers found.</td>
                </tr>
                <template x-for="customer in filtered" :key="customer.id">
                    <tr class="hover:bg-blue-50 transition">
                        <td class="py-3 px-4 font-semibold text-blue-700" x-text="customer.name || '-' "></td>
                        <td class="py-3 px-4">
                            <a :href="'https://wa.me/62' + (customer.phone_number ? customer.phone_number.replace(/^0+/, '') : '')" target="_blank" class="text-green-600 hover:underline" x-text="customer.phone_number || '-' "></a>
                        </td>
                        <td class="py-3 px-4">
                            <a :href="'mailto:' + customer.email" class="text-blue-600 hover:underline break-all" x-text="customer.email || '-' "></a>
                        </td>
                        <td class="py-3 px-4">
    <span x-text="customer.birth_date !== '-' ? new Date(customer.birth_date).toLocaleDateString('id-ID') : '-'"></span>
    <span class="text-gray-500 ml-2" x-show="customer.age !== '-'">(
        <span x-text="customer.age"></span> tahun
    )</span>
</td>
                        <td class="py-3 px-4 text-gray-700" x-text="customer.program || '-' "></td>
                        <td class="py-3 px-4" x-text="customer.schedule ?? '-'"></td>
                        <td class="py-3 px-4 font-semibold" :class="customer.quota > 0 ? 'text-green-600' : 'text-red-500'" x-text="customer.quota ?? '-' "></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-semibold" x-text="customer.membership || '-' "></span>
                        </td>
                        <td class="py-3 px-4" x-text="customer.goals ?? '-'"></td>
                        <td class="py-3 px-4" x-text="customer.kondisi_khusus ?? '-'"></td>
                        <td class="py-3 px-4" x-text="customer.referensi ?? '-'"></td>
                        <td class="py-3 px-4" x-text="customer.pengalaman ?? '-'"></td>
                        <td class="py-3 px-4" x-text="customer.is_muslim ?? '-'"></td>
                        <td class="py-3 px-4" x-text="customer.voucher_code ?? '-'"></td>
                        <td class="py-3 px-4">
                            <template x-if="customer.preferred_membership === 'Basic'">
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-semibold">Basic</span>
                            </template>
                            <template x-if="customer.preferred_membership === 'Premium'">
                                <span class="px-2 py-1 rounded bg-purple-100 text-purple-700 text-xs font-semibold">Premium</span>
                            </template>
                            <template x-if="!['Basic','Premium'].includes(customer.preferred_membership)">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold" x-text="customer.preferred_membership || 'Not sure'"> </span>
                            </template>
                        </td>
                        <td class="py-3 px-4 text-gray-500" x-text="new Date(customer.created_at).toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' })"></td>
                        <td class="py-3 px-4 text-gray-500" x-text="new Date(customer.updated_at).toLocaleString('id-ID', { timeZone: 'Asia/Jakarta' })"></td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center gap-2 flex-wrap md:flex-nowrap">
                                <template x-if="customer.quota > 0">
                                    <button @click.prevent="checkin(customer)" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-semibold whitespace-nowrap transition">
                                        Cek In
                                    </button>
                                </template>
                                <template x-if="customer.quota <= 0">
                                    <div class="px-3 py-1 bg-gray-300 text-gray-600 rounded text-xs font-semibold whitespace-nowrap">
                                        Habis
                                    </div>
                                </template>
                                <a :href="'/adm/customers/' + customer.id + '/edit'" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs font-semibold whitespace-nowrap">
                                    Edit
                                </a>
                                <!-- Delete Button: open modal -->
                                <button type="button"
                                    @click="showDelete = true; deleteId = customer.id; deleteName = customer.name"
                                    class="px-3 py-1 bg-red-500 text-white rounded text-xs font-semibold whitespace-nowrap">
                                    Delete
                                </button>
                                <a :href="'/adm/bookings/create/' + customer.id" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Booking</a>
                                <template x-if="!customer.is_verified">
                                    <form method="POST" :action="`/adm/customers/${customer.id}/verify`"
                                        @submit.prevent="if (confirm(`Verifikasi dan kirim login untuk ${customer.name}?`)) $el.submit()">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded">
                                            Verifikasi & Kirim Login
                                        </button>
                                    </form>
                                </template>
                                <template x-if="customer.is_verified">
                                    <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded font-semibold">
                                        ✅ Sudah Bisa Login
                                    </span>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\admin\customers\index.blade.php -->
<div x-show="showDelete"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
     style="display: none;"
     @keydown.escape.window="showDelete = false">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-7 relative flex flex-col items-center">
        <div class="bg-red-100 rounded-full p-3 mb-4 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-red-700 mb-2 text-center">Konfirmasi Hapus Customer</h3>
        <p class="mb-6 text-gray-700 text-center">
            Anda yakin ingin menghapus data <span class="font-semibold text-blue-700" x-text="deleteName"></span>?<br>
            <span class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan.</span>
        </p>
        <div class="flex flex-col gap-3 w-full mt-2">
            <button @click="showDelete = false"
                class="w-full px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-base hover:bg-gray-300 transition">
                Batal
            </button>
            <form :action="`/adm/customers/${deleteId}`" method="POST" class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full px-4 py-2 rounded-lg bg-red-600 text-white font-semibold text-base hover:bg-red-700 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
    </div>
</main>

<footer class="mt-10 text-center text-gray-500 text-xs md:text-sm">
    &copy; {{ date('Y') }} FTM Admin. All rights reserved.
</footer>

</body>
</html>