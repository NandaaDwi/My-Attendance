{{-- resources/views/layouts/student.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Student Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite('resources/js/app.js')
    @stack('styles')
</head>

<body
    class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-full flex flex-col">

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav x-data="{ open: false, profileOpen: false }"
            class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        {{-- Logo --}}
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('student.dashboard') }}"
                                class="flex items-center space-x-2 text-blue-600 dark:text-blue-400 font-bold text-xl transition duration-300 ease-in-out transform hover:scale-105">
                                <i class="fas fa-graduation-cap text-3xl"></i>
                                <span>Student Hub</span>
                            </a>
                        </div>
                    </div>

                    {{-- Navigasi Desktop --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                        <a href="{{ route('student.dashboard') }}"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition duration-150 ease-in-out">
                            Dashboard
                        </a>
                        {{-- Tambahkan item navigasi lain di sini jika ada, contoh: --}}
                        {{-- <a href="#" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 transition duration-150 ease-in-out">
                            Mata Pelajaran
                        </a> --}}

                        {{-- Dropdown Pengguna (Profil) --}}
                        <div class="relative" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                            <button @click="profileOpen = !profileOpen"
                                class="flex items-center space-x-2 px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition ease-in-out duration-150 shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-700 flex items-center justify-center text-blue-600 dark:text-blue-200">
                                    <i class="fas fa-user text-lg"></i>
                                </div>
                                <span class="hidden lg:inline">{{ Auth::user()->name }}</span>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute z-50 mt-2 w-48 rounded-lg shadow-xl origin-top-right right-0 bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none p-1">
                                <div class="py-1">
                                    {{-- Tautan "Profile" dihapus sesuai permintaan --}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="flex items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 rounded-md hover:bg-red-100 dark:hover:bg-red-700 hover:text-red-700 dark:hover:text-red-200 focus:outline-none focus:bg-red-100 dark:focus:bg-red-700 transition duration-150 ease-in-out">
                                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Mobile Menu --}}
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('student.dashboard') }}"
                        class="block w-full ps-3 pe-4 py-2 border-l-4 border-blue-400 dark:border-blue-600 text-start text-base font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/50 focus:outline-none focus:text-blue-800 dark:focus:text-blue-200 focus:bg-blue-100 dark:focus:bg-blue-900 focus:border-blue-700 dark:focus:border-blue-300 transition duration-150 ease-in-out">
                        {{ __('Dashboard') }}
                    </a>
                    {{-- Tambahkan item navigasi mobile lain jika ada --}}
                </div>

                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div class="px-4 flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-700 flex items-center justify-center text-blue-600 dark:text-blue-200">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-red-700 dark:hover:text-red-200 hover:bg-red-50 dark:hover:bg-red-700 hover:border-red-300 dark:hover:border-red-600 focus:outline-none focus:text-red-800 dark:focus:text-red-200 focus:bg-red-50 dark:focus:bg-red-700 focus:border-red-300 dark:focus:border-red-600 transition duration-150 ease-in-out">
                                <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow py-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative dark:bg-green-900 dark:border-green-700 dark:text-green-200"
                        role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative dark:bg-red-900 dark:border-red-700 dark:text-red-200"
                        role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <footer class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 dark:text-gray-400 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
</body>

</html>