<!-- filepath: resources/views/member/change-password.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6 text-primary">Ganti Password</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-600 p-3 mb-4 rounded text-sm">
            {{ $errors->first() }}
        </div>
    @endif
    <form method="POST" action="{{ route('member.change-password') }}" class="space-y-5">
        @csrf
        <div>
            <label for="current_password" class="block text-sm mb-1">Password Lama</label>
            <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-2 border rounded">
        </div>
        <div>
            <label for="new_password" class="block text-sm mb-1">Password Baru</label>
            <input type="password" name="new_password" id="new_password" required class="w-full px-4 py-2 border rounded">
        </div>
        <div>
            <label for="new_password_confirmation" class="block text-sm mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="w-full px-4 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-primary text-white px-6 py-2 rounded hover:bg-secondary transition font-semibold">Ubah Password</button>
    </form>
</div>
@endsection