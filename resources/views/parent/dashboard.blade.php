{{-- resources/views/parent/dashboard.blade.php --}}
@extends('layouts.student') {{-- Pastikan ini mengarah ke layout induk Anda, atau layout bersama --}}

@section('title', 'Dashboard Orang Tua')

@push('styles')
    {{-- Memuat Chart.js untuk grafik --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Memuat Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Memuat Alpine.js: Pastikan ini disertakan! --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- CSS untuk x-cloak agar elemen tidak terlihat sebelum Alpine.js siap --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 p-4 sm:p-6 lg:p-8 font-sans antialiased">
    <div class="relative bg-gradient-to-br from-purple-600 to-pink-700 rounded-3xl shadow-xl p-8 mb-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('/images/pattern.png'); background-repeat: repeat;"></div>
        <div class="relative flex items-center space-x-6">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-white bg-opacity-30 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-child text-white text-3xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">Halo, Bapak/Ibu {{ $parentName }}!</h1>
                <p class="text-base sm:text-lg text-purple-100 mt-2">Selamat datang di Halaman Absensi Anak Anda.</p>
            </div>
        </div>
    </div>

    @forelse($childrenData as $child)
        {{-- Menginisialisasi komponen Alpine.js untuk setiap anak --}}
        {{-- Menggunakan @json untuk mengonversi data PHP ke JSON yang aman untuk JavaScript --}}
        <div x-data="childAttendance({{ json_encode($child) }})" x-init="init();"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-5 border-b pb-3 border-gray-200 dark:border-gray-700 flex items-center gap-3">
                <i class="fas fa-user-circle text-purple-500"></i>
                Absensi {{ $child['name'] }} (NISN: {{ $child['nisn'] }})
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Card Hadir --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-green-200 dark:bg-green-700 rounded-full opacity-30 transform rotate-45"></div>
                    <div class="flex items-center space-x-4 relative z-10">
                        <div class="flex-shrink-0 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center text-white shadow-md">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Hadir</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white" x-text="summary.Present"></p>
                        </div>
                    </div>
                </div>
                {{-- Card Izin --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-200 dark:bg-yellow-700 rounded-full opacity-30 transform rotate-45"></div>
                    <div class="flex items-center space-x-4 relative z-10">
                        <div class="flex-shrink-0 w-14 h-14 bg-yellow-500 rounded-full flex items-center justify-center text-white shadow-md">
                            <i class="fas fa-user-minus text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Izin</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white" x-text="summary.Excused"></p>
                        </div>
                    </div>
                </div>
                {{-- Card Sakit --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-red-200 dark:bg-red-700 rounded-full opacity-30 transform rotate-45"></div>
                    <div class="flex items-center space-x-4 relative z-10">
                        <div class="flex-shrink-0 w-14 h-14 bg-red-500 rounded-full flex items-center justify-center text-white shadow-md">
                            <i class="fas fa-procedures text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Sakit</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white" x-text="summary.Sick"></p>
                        </div>
                    </div>
                </div>
                {{-- Card Alpa --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full opacity-30 transform rotate-45"></div>
                    <div class="flex items-center space-x-4 relative z-10">
                        <div class="flex-shrink-0 w-14 h-14 bg-gray-500 rounded-full flex items-center justify-center text-white shadow-md">
                            <i class="fas fa-user-slash text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Alpa</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white" x-text="summary.Absent"></p>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">Grafik Kehadiran {{ $child['name'] }}</h3>
            <div class="flex flex-wrap gap-3 mb-6 justify-center">
                <button
                    @click="setPeriod('week')"
                    :class="{'bg-purple-600 text-white shadow-md': period === 'week', 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 hover:bg-purple-200 dark:hover:bg-purple-800': period !== 'week'}"
                    class="px-5 py-2 rounded-full font-medium transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    <i class="fas fa-calendar-week mr-2"></i>1 Minggu
                </button>
                <button
                    @click="setPeriod('month')"
                    :class="{'bg-purple-600 text-white shadow-md': period === 'month', 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 hover:bg-purple-200 dark:hover:bg-purple-800': period !== 'month'}"
                    class="px-5 py-2 rounded-full font-medium transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    <i class="fas fa-calendar-alt mr-2"></i>1 Bulan
                </button>
                <button
                    @click="setPeriod('year')"
                    :class="{'bg-purple-600 text-white shadow-md': period === 'year', 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 hover:bg-purple-200 dark:hover:bg-purple-800': period !== 'year'}"
                    class="px-5 py-2 rounded-full font-medium transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    <i class="fas fa-calendar-check mr-2"></i>1 Tahun
                </button>
            </div>
            <div class="h-80 sm:h-96 w-full max-w-2xl mx-auto">
                <canvas :id="'attendanceChart-' + childId"></canvas>
            </div>
            <div x-show="!attendanceDataForGraph.length && !loadingChart" class="text-center text-gray-500 dark:text-gray-400 mt-6">
                <i class="fas fa-exclamation-circle mr-2"></i>Belum ada data absensi untuk periode ini.
            </div>
            <div x-show="loadingChart" class="text-center text-gray-500 dark:text-gray-400 mt-6">
                <i class="fas fa-spinner fa-spin mr-2"></i>Memuat grafik...
            </div>

            <div class="text-center mt-8">
                <button
                    {{-- Dispatch an event instead of directly calling a function from another scope --}}
                    @click="$dispatch('open-detailed-modal', { childId: childId })"
                    class="inline-flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-full transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                >
                    <i class="fas fa-list-alt mr-3 text-xl"></i>
                    <span>Lihat Riwayat Absensi Lengkap {{ $child['name'] }}</span>
                </button>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 text-center text-gray-600 dark:text-gray-400">
            <i class="fas fa-exclamation-triangle text-4xl mb-4 text-yellow-500"></i>
            <p class="text-lg font-semibold mb-2">Tidak ada data anak yang terdaftar.</p>
            <p>Silakan hubungi administrator untuk memastikan anak Anda terhubung dengan akun ini.</p>
        </div>
    @endforelse

    {{-- Modal Detail Absensi (dibagi untuk semua anak) --}}
    <div x-data="parentDetailedModal({{ json_encode($childrenData) }})" x-show="isDetailedModalOpen" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-8"
        @keydown.escape.window="closeDetailedModal()"
        {{-- Listen for the custom event dispatched from childAttendance component --}}
        @open-detailed-modal.window="openDetailedModal($event.detail.childId)"
    >
        <div @click.away="closeDetailedModal()"
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl max-h-[90vh] flex flex-col">

            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-list-alt text-white"></i>
                    </div>
                    <span>Riwayat Absensi Lengkap <span x-text="currentChildName"></span></span>
                </h2>
                <button @click="closeDetailedModal()"
                    class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <div class="flex-1">
                        <label for="search_date_modal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Tanggal:</label>
                        <input
                            type="date"
                            id="search_date_modal"
                            x-model="searchDate"
                            @change="fetchDetailedAttendances()"
                            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200"
                        >
                    </div>
                    <div class="flex-1">
                        <label for="status_filter_modal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter Status:</label>
                        <select
                            id="status_filter_modal"
                            x-model="statusFilter"
                            @change="fetchDetailedAttendances()"
                            class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200"
                        >
                            <option value="">Semua Status</option>
                            <option value="Present">Hadir</option>
                            <option value="Excused">Izin</option>
                            <option value="Sick">Sakit</option>
                            <option value="Absent">Alpa</option>
                        </select>
                    </div>
                    <div class="flex-none sm:self-end">
                        <button
                            @click="resetFilters();"
                            class="w-full sm:w-auto px-6 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                        >
                            Reset Filter
                        </button>
                    </div>
                </div>

                <div x-show="loadingDetails" class="p-6 text-center">
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
                        <p class="ml-3 text-gray-600 dark:text-gray-400">Memuat detail absensi...</p>
                    </div>
                </div>

                <div x-show="!loadingDetails && detailedAttendances.length > 0" class="overflow-x-auto rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="attendance in detailedAttendances" :key="attendance.id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100" x-text="attendance.formatted_date"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="{
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': attendance.status === 'Present',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': attendance.status === 'Excused',
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': attendance.status === 'Sick',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200': attendance.status === 'Absent'
                                            }"
                                            class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                                            x-text="attendance.status"
                                        ></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate" x-text="attendance.note"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div x-show="!loadingDetails && detailedAttendances.length === 0" class="px-6 py-16 text-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-box-open text-3xl mb-3"></i>
                    <p>Tidak ada riwayat absensi yang ditemukan untuk filter ini.</p>
                </div>

                <nav x-show="!loadingDetails && paginationMeta.total > 0" class="px-4 py-3 flex items-center justify-between sm:px-6" aria-label="Pagination">
                    <div class="flex-1 flex justify-between sm:justify-end gap-2">
                        <button
                            @click="fetchDetailedAttendances(paginationMeta.current_page - 1)"
                            :disabled="!paginationMeta.prev_page_url"
                            :class="{'opacity-50 cursor-not-allowed': !paginationMeta.prev_page_url}"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
                        >
                            <i class="fas fa-chevron-left mr-2"></i> Sebelumnya
                        </button>
                        <button
                            @click="fetchDetailedAttendances(paginationMeta.current_page + 1)"
                            :disabled="!paginationMeta.next_page_url"
                            :class="{'opacity-50 cursor-not-allowed': !paginationMeta.next_page_url}"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200"
                        >
                            Berikutnya <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>
                    <div class="hidden sm:flex sm:items-center">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Menampilkan
                            <span class="font-medium" x-text="paginationMeta.from"></span>
                            sampai
                            <span class="font-medium" x-text="paginationMeta.to"></span>
                            dari
                            <span class="font-medium" x-text="paginationMeta.total"></span>
                            hasil
                        </p>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<script>
// Komponen Alpine.js untuk setiap bagian absensi anak
function childAttendance(childData) {
    return {
        childId: childData.id,
        summary: childData.summary,
        // Keep original attendance data for graph, filtering will happen in getChartData
        allAttendanceDataForGraph: childData.attendanceDataForGraph,
        attendanceDataForGraph: [], // This will hold the filtered data for the current period
        period: 'month', // Periode default

        chart: null, // Instance Chart.js
        loadingChart: false, // Status loading grafik

        init() {
            // Log seluruh data absensi yang diterima dari backend
            console.log('All attendance data for graph:', this.allAttendanceDataForGraph);

            this.updateChartDataForPeriod(); // Initial data filtering
            this.createOrUpdateChart();
            this.$watch('period', () => {
                this.updateChartDataForPeriod();
                this.createOrUpdateChart();
            });
        },

        updateChartDataForPeriod() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            let startDate;

            switch (this.period) {
                case 'week':
                    // Mulai dari hari Senin minggu ini
                    const dayOfWeek = (today.getDay() + 6) % 7; // Monday = 0, Sunday = 6
                    startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - dayOfWeek);
                    break;
                case 'month':
                    // Mulai dari tanggal 1 di bulan ini
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
                case 'year':
                    // Mulai dari tanggal 1 Januari di tahun ini
                    startDate = new Date(today.getFullYear(), 0, 1);
                    break;
                default:
                    // Default ke bulan ini jika periode tidak dikenal
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            }
            startDate.setHours(0, 0, 0, 0); // Normalisasi ke awal hari

            this.attendanceDataForGraph = this.allAttendanceDataForGraph.filter(att => {
                const attDate = new Date(att.date + 'T00:00:00'); // Pastikan parsing tanggal konsisten
                return attDate >= startDate && attDate <= today;
            });

            // Log data absensi setelah difilter untuk periode saat ini
            console.log('Filtered attendance data for period ' + this.period + ':', this.attendanceDataForGraph);
        },

        createOrUpdateChart() {
            this.loadingChart = true;
            this.$nextTick(() => {
                const ctx = document.getElementById('attendanceChart-' + this.childId);
                if (!ctx) {
                    console.error('Elemen Canvas dengan ID "attendanceChart-' + this.childId + '" tidak ditemukan.');
                    this.loadingChart = false;
                    return;
                }
                const chartContext = ctx.getContext('2d');

                if (this.chart) {
                    this.chart.destroy();
                }

                const chartData = this.getChartData();

                // Console log untuk debugging data - biarkan saja atau hapus jika sudah tidak diperlukan
                // console.log('Chart Data for child ' + this.childId + ':', chartData);

                this.chart = new Chart(chartContext, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true, // Hanya tampilkan legenda
                                labels: {
                                    color: 'rgb(156 163 175)' // Agar terlihat di dark mode
                                }
                            },
                            tooltip: {
                                enabled: true, // Hanya aktifkan tooltip
                                backgroundColor: 'rgba(0, 0, 0, 0.7)', // Warna latar belakang tooltip
                                bodyColor: '#fff', // Warna teks body tooltip
                                titleColor: '#fff', // Warna teks judul tooltip
                            }
                        },
                        scales: {
                            x: {
                                // Pengaturan dasar untuk sumbu X
                                grid: {
                                    color: 'rgba(229, 231, 235, 0.1)', // Warna grid transparan
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: 'rgb(156 163 175)' // Warna teks ticks
                                }
                            },
                            y: {
                                beginAtZero: true,
                                // Pengaturan dasar untuk sumbu Y
                                ticks: {
                                    precision: 0, // Pastikan ticks adalah bilangan bulat
                                    color: 'rgb(156 163 175)' // Warna teks ticks
                                },
                                grid: {
                                    color: 'rgba(229, 231, 235, 0.1)', // Warna grid transparan
                                    drawBorder: false,
                                }
                            }
                        },
                        // Nonaktifkan animasi sementara
                        animation: false
                    }
                });
                this.loadingChart = false;
            });
        },

        setPeriod(newPeriod) {
            this.period = newPeriod;
            // The watcher on 'period' will handle calling updateChartDataForPeriod and createOrUpdateChart
        },

        getChartData() {
            const dailyCounts = {};

            // Determine the date range based on the current period
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            let startDate;

            switch (this.period) {
                case 'week':
                    const dayOfWeek = (today.getDay() + 6) % 7; // Monday = 0, Sunday = 6
                    startDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() - dayOfWeek);
                    break;
                case 'month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
                case 'year':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    break;
                default:
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            }
            startDate.setHours(0, 0, 0, 0);

            // Populate all dates within the determined range with initial zero counts
            let currentDate = new Date(startDate);
            const endDate = new Date(today); // End date is always today
            while (currentDate <= endDate) {
                const dateKey = currentDate.toISOString().slice(0, 10);
                dailyCounts[dateKey] = { Present: 0, Excused: 0, Sick: 0, Absent: 0 };
                currentDate.setDate(currentDate.getDate() + 1);
            }

            // Fill dailyCounts with actual attendance data for the filtered period
            this.attendanceDataForGraph.forEach(att => {
                const attDate = new Date(att.date + 'T00:00:00');
                const dateKey = attDate.toISOString().slice(0, 10);

                // Only add if the date is within the generated range
                if (dailyCounts[dateKey]) {
                    dailyCounts[dateKey][att.status]++;
                }
            });

            const labels = [];
            const presentData = [];
            const excusedData = [];
            const sickData = [];
            const absentData = [];

            const sortedDateKeys = Object.keys(dailyCounts).sort(); // Sort date keys

            sortedDateKeys.forEach(dateKey => {
                const dateObj = new Date(dateKey + 'T00:00:00');
                labels.push(this.formatDateForChart(dateObj));
                const counts = dailyCounts[dateKey];
                presentData.push(counts.Present);
                excusedData.push(counts.Excused);
                sickData.push(counts.Sick);
                absentData.push(counts.Absent);
            });

            return {
                labels: labels,
                datasets: [
                    {
                        label: 'Hadir',
                        backgroundColor: 'rgba(52, 211, 153, 0.6)',
                        borderColor: 'rgba(52, 211, 153, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(52, 211, 153, 1)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        data: presentData
                    },
                    {
                        label: 'Izin',
                        backgroundColor: 'rgba(251, 191, 36, 0.6)',
                        borderColor: 'rgba(251, 191, 36, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(251, 191, 36, 1)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        data: excusedData
                    },
                    {
                        label: 'Sakit',
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        data: sickData
                    },
                    {
                        label: 'Alpa',
                        backgroundColor: 'rgba(156, 163, 175, 0.6)',
                        borderColor: 'rgba(156, 163, 175, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(156, 163, 175, 1)',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        data: absentData
                    }
                ]
            };
        },

        formatDateForChart(date) {
            const options = { day: 'numeric', month: 'short' };
            return date.toLocaleDateString('id-ID', options);
        }
    }
}

// Komponen Alpine.js untuk modal detail absensi bersama
function parentDetailedModal(allChildrenData) { // Pass childrenData here
    return {
        isDetailedModalOpen: false,
        currentChildId: null,
        currentChildName: '',
        detailedAttendances: [],
        paginationMeta: {},
        loadingDetails: false,
        searchDate: '',
        statusFilter: '',
        allChildren: allChildrenData, // Store all children data here

        openDetailedModal(childId) {
            this.isDetailedModalOpen = true;
            this.currentChildId = childId;
            const childFound = this.allChildren.find(child => child.id === childId);
            this.currentChildName = childFound ? childFound.name : 'Anak';
            this.fetchDetailedAttendances();
        },

        closeDetailedModal() {
            this.isDetailedModalOpen = false;
            this.currentChildId = null;
            this.currentChildName = '';
            this.detailedAttendances = [];
            this.paginationMeta = {};
            this.loadingDetails = false;
            this.searchDate = '';
            this.statusFilter = '';
        },

        async fetchDetailedAttendances(page = 1) {
            if (!this.currentChildId) return;
            this.loadingDetails = true;
            try {
                let url = `/parent/api/attendances?child_id=${this.currentChildId}&page=${page}`;
                if (this.searchDate) {
                    url += `&search_date=${this.searchDate}`;
                }
                if (this.statusFilter) {
                    url += `&status_filter=${this.statusFilter}`;
                }

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Respons jaringan tidak OK');
                }

                const data = await response.json();
                this.detailedAttendances = data.data; // Data absensi
                this.paginationMeta = { // Metadata pagination
                    current_page: data.current_page,
                    from: data.from,
                    to: data.to,
                    total: data.total,
                    last_page: data.last_page,
                    prev_page_url: data.prev_page_url,
                    next_page_url: data.next_page_url,
                };
            } catch (error) {
                console.error('Error saat mengambil detail absensi:', error);
                this.detailedAttendances = [];
                this.paginationMeta = {};
                // Tampilkan pesan error ke user jika diperlukan
            } finally {
                this.loadingDetails = false; // Set loading menjadi false
            }
        },

        // Reset filter
        resetFilters() {
            this.searchDate = '';
            this.statusFilter = '';
            this.fetchDetailedAttendances(1);
        },
    }
}
</script>
@endsection