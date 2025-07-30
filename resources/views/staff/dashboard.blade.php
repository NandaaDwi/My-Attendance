@extends('layouts.staff')

@section('title', 'Dashboard Staff')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8" x-data="dashboard()" x-init="init()">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="p-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Dashboard Staff</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Statistik dan aktivitas absensi siswa</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ now()->format('d F Y') }}
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
            <!-- Total Siswa -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Siswa Aktif</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($jumlahSiswa) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <i class="fas fa-user-graduate text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Kelas -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Kelas</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($jumlahKelas) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <i class="fas fa-chalkboard text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Absensi Hari Ini -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow sm:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Absensi Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats->hadir + $stats->sakit + $stats->izin + $stats->alpha) }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <i class="fas fa-calendar-check text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
            @php
                $statList = [
                    ['label' => 'Hadir', 'icon' => 'fa-user-check', 'color' => 'green', 'value' => $stats->hadir],
                    ['label' => 'Sakit', 'icon' => 'fa-user-injured', 'color' => 'blue', 'value' => $stats->sakit],
                    ['label' => 'Izin', 'icon' => 'fa-user-clock', 'color' => 'yellow', 'value' => $stats->izin],
                    ['label' => 'Alpha', 'icon' => 'fa-user-times', 'color' => 'red', 'value' => $stats->alpha],
                ];
            @endphp

            @foreach ($statList as $item)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-{{ $item['color'] }}-100 dark:bg-{{ $item['color'] }}-900 rounded-lg">
                            <i
                                class="fas {{ $item['icon'] }} text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400"></i>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">{{ $item['label'] }}
                            </p>
                            <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $item['value'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-2">Grafik Kehadiran</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Visualisasi data absensi siswa</p>
                </div>

                <!-- Filter Controls -->
                <div class="flex flex-wrap gap-2">
                    <template x-for="option in ['daily', 'weekly', 'monthly', 'yearly']" :key="option">
                        <button @click="changeFilter(option)"
                            :class="filter === option ?
                                'bg-green-600 text-white' :
                                'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                            <span
                                x-text="option === 'daily' ? 'Hari Ini' : option === 'weekly' ? 'Minggu Ini' : option === 'monthly' ? 'Bulan Ini' : 'Tahun Ini'"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="flex items-center justify-center h-64">
                <div class="flex items-center space-x-2">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div>
                    <span class="text-gray-600 dark:text-gray-400">Memuat data...</span>
                </div>
            </div>

            <!-- Chart Container -->
            <div x-show="!loading" class="relative w-full overflow-x-auto">
                <div class="min-w-[300px] w-full" style="min-height: 300px;">
                    <canvas id="attendanceChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function dashboard() {
            return {
                filter: 'daily',
                chart: null,
                loading: false,

                init() {
                    const initialData = @json($chartData);
                    this.renderChart(initialData);
                },

                async changeFilter(newFilter) {
                    if (this.filter === newFilter) return;

                    this.filter = newFilter;
                    this.loading = true;

                    try {
                        const response = await fetch(`{{ route('admin.dashboard.data') }}?filter=${newFilter}`);
                        const data = await response.json();
                        this.updateChart(data);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        alert('Gagal memuat data. Silakan coba lagi.');
                    } finally {
                        this.loading = false;
                    }
                },

                renderChart(data) {
                    const ctx = document.getElementById('attendanceChart').getContext('2d');

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    const config = this.getChartConfig(data);
                    this.chart = new Chart(ctx, config);
                },

                updateChart(data) {
                    if (this.chart) {
                        this.chart.destroy();
                    }
                    this.renderChart(data);
                },

                getChartConfig(data) {
                    const {
                        chartType,
                        labels,
                        statsData
                    } = data;

                    const colors = {
                        hadir: {
                            bg: 'rgba(34, 197, 94, 0.8)',
                            border: 'rgb(34, 197, 94)'
                        },
                        sakit: {
                            bg: 'rgba(59, 130, 246, 0.8)',
                            border: 'rgb(59, 130, 246)'
                        },
                        izin: {
                            bg: 'rgba(250, 204, 21, 0.8)',
                            border: 'rgb(250, 204, 21)'
                        },
                        alpha: {
                            bg: 'rgba(239, 68, 68, 0.8)',
                            border: 'rgb(239, 68, 68)'
                        }
                    };

                    if (chartType === 'doughnut') {
                        return {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: statsData,
                                    backgroundColor: [
                                        colors.hadir.bg,
                                        colors.sakit.bg,
                                        colors.izin.bg,
                                        colors.alpha.bg
                                    ],
                                    borderColor: [
                                        colors.hadir.border,
                                        colors.sakit.border,
                                        colors.izin.border,
                                        colors.alpha.border
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        };
                    }

                    const datasets = [{
                            label: 'Hadir',
                            data: statsData.hadir,
                            backgroundColor: colors.hadir.bg,
                            borderColor: colors.hadir.border,
                            borderWidth: 2,
                            tension: chartType === 'line' ? 0.4 : 0
                        },
                        {
                            label: 'Sakit',
                            data: statsData.sakit,
                            backgroundColor: colors.sakit.bg,
                            borderColor: colors.sakit.border,
                            borderWidth: 2,
                            tension: chartType === 'line' ? 0.4 : 0
                        },
                        {
                            label: 'Izin',
                            data: statsData.izin,
                            backgroundColor: colors.izin.bg,
                            borderColor: colors.izin.border,
                            borderWidth: 2,
                            tension: chartType === 'line' ? 0.4 : 0
                        },
                        {
                            label: 'Alpha',
                            data: statsData.alpha,
                            backgroundColor: colors.alpha.bg,
                            borderColor: colors.alpha.border,
                            borderWidth: 2,
                            tension: chartType === 'line' ? 0.4 : 0
                        }
                    ];

                    return {
                        type: chartType,
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: 'white',
                                    bodyColor: 'white',
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                    borderWidth: 1
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0
                                    }
                                }
                            }
                        }
                    };
                }
            }
        }
    </script>
@endsection
