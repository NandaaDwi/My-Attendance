@extends('layouts.admin')

@section('title', 'Detail Kelas: ' . $studentClass->name)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chalkboard text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                    Detail Kelas: {{ $studentClass->name }}
                </h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                    Daftar siswa di kelas {{ $studentClass->name }} (Jurusan: {{ $studentClass->major->name }})
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.student-class.index') }}"
                    class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="hidden sm:inline">Kembali</span>
                    <span class="sm:hidden">Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Nama Siswa
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-id-card mr-2"></i>NIS
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-id-card-alt mr-2"></i>NISN
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($students as $i => $student)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $students->firstItem() + $i }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $student->nis }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $student->nisn }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-user-graduate text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada siswa di kelas ini</h3>
                            <p class="text-gray-600 dark:text-gray-400">Belum ada siswa yang ditambahkan ke kelas ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden">
            @forelse($students as $i => $student)
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12 rounded-full object-cover" 
                             src="{{ $student->photo ? asset('storage/' . $student->photo) : asset('images/default-avatar.png') }}" 
                             alt="{{ $student->user->name }}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            NIS: <span class="font-medium">{{ $student->nis }}</span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            NISN: <span class="font-medium">{{ $student->nisn }}</span>
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-user-graduate text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada siswa di kelas ini</h3>
                <p class="text-gray-600 dark:text-gray-400">Belum ada siswa yang ditambahkan ke kelas ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    @if($students->hasPages())
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection