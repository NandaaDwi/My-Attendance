@extends('layouts.staff')

@section('title', 'Tahun Ajaran')

@section('content')
<div
    x-data="academicYearSearch()"
    x-init="fetchAcademicYears()"
    class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8"
>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Tahun Ajaran</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Lihat data tahun ajaran akademik</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="max-w-md mx-auto">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input
                    type="text"
                    x-model="search"
                    @input.debounce.500ms="fetchAcademicYears()"
                    placeholder="Cari tahun ajaran..."
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                />
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden max-w-4xl mx-auto">

        <div x-show="loading" class="p-6 text-center">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
                <p class="ml-3 text-gray-600 dark:text-gray-400">Memuat data...</p>
            </div>
        </div>

        <div class="hidden lg:block overflow-x-auto" x-show="!loading && academicYears.length > 0">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-2"></i>Nama Tahun Ajaran
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-toggle-on mr-2"></i>Status Aktif
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="(ay, i) in academicYears" :key="ay.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100" x-text="i + 1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-white text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="ay.name"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <template x-if="ay.active">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                                    >
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                </template>
                                <template x-if="!ay.active">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
                                    >
                                        <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                    </span>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div
            x-show="!loading && academicYears.length === 0"
            class="px-6 py-16 text-center"
        >
            <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-calendar-alt text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada data tahun ajaran</h3>
            <p class="text-gray-600 dark:text-gray-400">Belum ada tahun ajaran yang ditambahkan atau hasil pencarian tidak ditemukan.</p>
        </div>

        <div class="lg:hidden space-y-4 px-4 py-6" x-show="!loading && academicYears.length > 0">
            <template x-for="(ay, i) in academicYears" :key="ay.id">
                <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="ay.name"></h3>
                            <div class="mt-1">
                                <template x-if="ay.active">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                                    >
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                </template>
                                <template x-if="!ay.active">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
                                    >
                                        <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function academicYearSearch() {
    return {
        search: '',
        academicYears: [],
        loading: false, // New state for loading
        fetchAcademicYears() {
            this.loading = true; // Set loading to true before fetch
            fetch(`{{ route('staff.academic_year.index') }}?search=` + encodeURIComponent(this.search), {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                this.academicYears = data.academicYears;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                this.academicYears = []; // Clear data on error
            })
            .finally(() => {
                this.loading = false; // Set loading to false after fetch completes (success or error)
            });
        }
    }
}
</script>
@endsection