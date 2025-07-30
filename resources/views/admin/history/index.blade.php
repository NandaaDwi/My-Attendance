{{-- resources/views/admin/history/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Riwayat Absensi')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-teal-500 via-teal-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-history text-white text-xl"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Riwayat Absensi</h1>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                        Lihat riwayat perubahan data absensi siswa
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <form action="{{ route('admin.attendance-history.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 lg:items-center">
                <div class="relative flex-1 max-w-md">
                    <label for="search" class="sr-only">Cari</label>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" id="search" name="search" value="{{ $search }}"
                        placeholder="Cari berdasarkan nama siswa atau perubahan..."
                        class="block w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="relative">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none mt-6"> {{-- Adjusted mt-6 for label --}}
                            <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                        </div>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                            class="block pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div class="relative">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none mt-6"> {{-- Adjusted mt-6 for label --}}
                            <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                        </div>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                            class="block pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium self-end"> {{-- self-end to align with date inputs --}}
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    @if($search || $startDate || $endDate)
                        <a href="{{ route('admin.attendance-history.index') }}"
                           class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium self-end"> {{-- self-end to align with date inputs --}}
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Riwayat Absensi</h3>
            </div>
            @if ($histories->isEmpty())
                <div class="col-span-full text-center py-16">
                    <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="lg:text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada riwayat absensi ditemukan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Coba ubah filter pencarian Anda.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No.</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Absensi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Perubahan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diubah Oleh</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu Perubahan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($histories as $index => $history)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $histories->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($history->attendance->date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $history->attendance->student->user->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white break-words max-w-xs">{{ $history->change }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $history->user->name ?? '-' }}</td>
                                    {{-- Perbaikan: Menggunakan Carbon::parse() untuk memastikan $history->created_at adalah objek Carbon --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6">
                    {{-- Pagination links --}}
                    {{ $histories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
