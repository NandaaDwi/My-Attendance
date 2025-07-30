{{-- resources/views/home.blade.php --}}
@extends('layouts.student') {{-- Assuming you have a default app layout --}}

@section('title', 'Beranda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-6">Selamat Datang!</h1>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="text-center text-gray-700 dark:text-gray-300">
            <p class="text-lg mb-4">Anda berhasil login.</p>
            <p class="mb-6">
                Ini adalah halaman beranda default. Jika Anda melihat ini, mungkin akun Anda belum memiliki peran khusus atau Anda telah dialihkan ke halaman ini.
            </p>

            <div class="mt-8">
                <p class="text-gray-600 dark:text-gray-400">Anda dapat kembali ke <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600 font-semibold">Halaman Utama</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection