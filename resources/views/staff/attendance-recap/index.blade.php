@extends('layouts.staff')

@section('title', 'Rekap Absensi')

@section('content')
{{-- Ini adalah skrip yang akan berjalan sangat awal untuk debugging --}}
<script>
    console.log("Script block started."); // Log sangat awal

    // Menunggu DOMContentLoaded untuk memastikan semua elemen HTML dimuat
    // dan memberikan sedikit waktu untuk Alpine.js yang dimuat via Vite
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => { // Tambahkan sedikit delay untuk memastikan Alpine.js benar-benar siap
            if (typeof Alpine === 'undefined') {
                console.error("Alpine.js is NOT loaded. Please ensure it's included and started in your resources/js/app.js file.");
            } else {
                console.log("Alpine.js detected and ready.");
            }
        }, 100); // Delay 100ms
    });
</script>

<div x-data="attendanceRecapApp({{ json_encode([
    'initialData' => $data,
    'initialView' => $view,
    'initialMajorId' => $majorId,
    'initialClassId' => $classId,
    'initialSearch' => $search,
    'initialDate' => $date,
    'initialNoRecapMessage' => $noRecapMessage,
    'breadcrumb' => $breadcrumb,
]) }})" class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-bar text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Rekap Absensi</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                    Laporan dan rekap data absensi siswa
                </p>
            </div>
        </div>
    </div>

    <div x-show="currentBreadcrumb.length > 0"
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <template x-for="(item, index) in currentBreadcrumb" :key="index">
                    <li class="inline-flex items-center">
                        <template x-if="index > 0">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        </template>
                        <template x-if="index === currentBreadcrumb.length - 1">
                            <span class="text-purple-600 dark:text-purple-400 font-medium" x-text="item"></span>
                        </template>
                        <template x-if="index < currentBreadcrumb.length - 1">
                            <span class="text-gray-500 dark:text-gray-400" x-text="item"></span>
                        </template>
                    </li>
                </template>
            </ol>
        </nav>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input type="text" x-model="searchQuery" @input.debounce.300ms="performSearch()"
                    :placeholder="searchPlaceholder"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
            </div>

            <div class="relative" x-show="currentView === 'students'">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-calendar text-gray-400 text-sm"></i>
                </div>
                <input type="date" x-model="selectedDate" @change="performSearch()"
                    class="block pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
            </div>

            <div class="flex-shrink-0" x-show="currentView !== 'majors'">
                <template x-if="currentView === 'classes'">
                    <a href="{{ route('staff.attendance-recap.index') }}"
                        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Jurusan
                    </a>
                </template>
                <template x-if="currentView === 'students'">
                    <a :href="`{{ route('staff.attendance-recap.index') }}?view=classes&major_id=${currentMajorId}`"
                        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Kelas
                    </a>
                </template>
            </div>
        </div>
    </div>

    <div id="content-container">
        <template x-if="currentView === 'majors'">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="major in data" :key="major.id">
                    <div
                        class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
                        <a :href="`{{ route('staff.attendance-recap.index') }}?view=classes&major_id=${major.id}`"
                            class="block">
                            <div class="p-6 text-center">
                                <div
                                    class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                                </div>
                                <h3
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300"
                                    x-text="major.name">
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="`${major.student_classes_count} Kelas`">
                                </p>
                                <div
                                    class="mt-4 inline-flex items-center text-purple-600 dark:text-purple-400 text-sm font-medium group-hover:text-purple-700 dark:group-hover:text-purple-300">
                                    Pilih Jurusan
                                    <i
                                        class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </template>
                <template x-if="data.length === 0">
                    <div class="col-span-full text-center py-16">
                        <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada jurusan ditemukan</h3>
                        <p class="text-gray-600 dark:text-gray-400" x-text="noRecapMessage"></p>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="currentView === 'classes'">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="cls in data" :key="cls.id">
                    <div
                        class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
                        <a :href="`{{ route('staff.attendance-recap.index') }}?view=students&major_id=${currentMajorId}&class_id=${cls.id}`"
                            class="block">
                            <div class="p-6 text-center">
                                <div
                                    class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 via-indigo-600 to-blue-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chalkboard text-white text-2xl"></i>
                                </div>
                                <h3
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300"
                                    x-text="cls.name">
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="`${cls.active_students_count ?? 0} Siswa Aktif`">
                                </p>
                                <div
                                    class="mt-4 inline-flex items-center text-indigo-600 dark:text-indigo-400 text-sm font-medium group-hover:text-indigo-700 dark:group-hover:text-indigo-300">
                                    Pilih Kelas
                                    <i
                                        class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </template>
                <template x-if="data.length === 0">
                    <div class="col-span-full text-center py-16">
                        <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada kelas ditemukan</h3>
                        <p class="text-gray-600 dark:text-gray-400" x-text="noRecapMessage"></p>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="currentView === 'students'">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                Rekap Absensi Tanggal: <span x-text="formatDate(selectedDate)"></span>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Kelas: <span x-text="classDisplayName"></span>
                            </p>
                        </div>
                        {{-- Hide save button if there's a noRecapMessage and no data --}}
                        <button @click="saveChanges()"
                            x-show="!noRecapMessage && data.length > 0"
                            class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                {{-- Display specific message if noRecapMessage is set, otherwise use generic no data message --}}
                <div x-show="noRecapMessage || data.length === 0" class="col-span-full text-center py-16">
                    <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2" x-text="noRecapMessage || 'Tidak ada data absensi ditemukan'"></h3>
                    <p class="text-gray-600 dark:text-gray-400" x-show="!noRecapMessage">Silakan pilih tanggal lain atau pastikan siswa aktif terdaftar.</p>
                </div>

                {{-- Using x-show to control table visibility --}}
                <div x-show="data.length > 0 && !noRecapMessage" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No.</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIS</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="(record, index) in data" :key="record.student_id">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="index + 1"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="record.nis"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="record.name"></td>
                                    <td class="px-6 py-4 text-center">
                                        <select x-model="record.status"
                                            class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                            <option value="Present">Hadir</option>
                                            <option value="Excused">Izin</option>
                                            <option value="Sick">Sakit</option>
                                            <option value="Absent">Alpa</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        <input type="text" x-model="record.note"
                                            placeholder="Tambahkan catatan..."
                                            class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </div>

    <div x-show="showToast" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-6 right-6 p-4 rounded-lg shadow-lg text-white"
        :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'">
        <span x-text="toastMessage"></span>
    </div>
</div>

<script>
    console.log("Script block started."); // Log sangat awal

    // Menunggu DOMContentLoaded untuk memastikan semua elemen HTML dimuat
    // dan memberikan sedikit waktu untuk Alpine.js yang dimuat via Vite
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => { // Tambahkan sedikit delay untuk memastikan Alpine.js benar-benar siap
            if (typeof Alpine === 'undefined') {
                console.error("Alpine.js is NOT loaded. Please ensure it's included and started in your resources/js/app.js file.");
            } else {
                console.log("Alpine.js detected and ready.");
            }
        }, 100); // Delay 100ms
    });

    function attendanceRecapApp(initialState) {
        console.log("attendanceRecapApp function called with initialState:", initialState); // Log saat fungsi dipanggil
        return {
            data: initialState.initialData,
            currentView: initialState.initialView,
            currentMajorId: initialState.initialMajorId,
            currentClassId: initialState.initialClassId,
            searchQuery: initialState.initialSearch,
            selectedDate: initialState.initialDate,
            noRecapMessage: initialState.initialNoRecapMessage, // Initialize with the message from the controller
            currentBreadcrumb: initialState.breadcrumb,
            classDisplayName: '', // Untuk menampilkan nama kelas di header tampilan siswa

            showToast: false,
            toastMessage: '',
            toastType: 'success',

            init() {
                console.log("init() called!"); // Log di awal init
                // Log state Alpine.js setelah inisialisasi
                console.log("Alpine component state after init:", {
                    dataLength: this.data.length,
                    currentView: this.currentView,
                    classDisplayName: this.classDisplayName,
                    noRecapMessage: this.noRecapMessage // Log the noRecapMessage
                });

                this.$watch('currentView', (view) => {
                    this.updateSearchPlaceholder(view);
                });
                this.updateSearchPlaceholder(this.currentView);

                // Inisialisasi classDisplayName saat aplikasi dimuat dalam tampilan 'students'
                if (this.currentView === 'students' && initialState.initialClassId) {
                    // If initial data exists, get class name from the first record
                    if (this.data.length > 0) {
                        this.classDisplayName = this.data[0].class_name || 'N/A';
                    } else {
                        // If no initial data (e.g., no active students), fetch class name
                        this.fetchClassNameFromController(initialState.initialClassId);
                    }
                }
            },

            updateSearchPlaceholder(view) {
                if (view === 'majors') {
                    this.searchPlaceholder = 'Cari jurusan...';
                } else if (view === 'classes') {
                    this.searchPlaceholder = 'Cari kelas...';
                } else {
                    this.searchPlaceholder = 'Cari siswa...';
                }
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },

            async fetchClassNameFromController(classId) {
                console.log("fetchClassNameFromController called for classId:", classId); // Log saat fungsi dipanggil
                const params = new URLSearchParams();
                params.append('view', 'students');
                params.append('class_id', classId);
                params.append('date', this.selectedDate); // Sertakan tanggal untuk konteks

                try {
                    const res = await fetch(`{{ route('staff.attendance-recap.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    console.log("Raw response from fetchClassNameFromController:", res); // Log respons mentah
                    const data = await res.json();
                    console.log("Data from fetchClassNameFromController:", data); // Log data yang diterima
                    if (data.success && data.classDisplayName) {
                        this.classDisplayName = data.classDisplayName;
                    } else {
                        this.classDisplayName = 'N/A';
                    }
                } catch (error) {
                    console.error('Error fetching class name:', error);
                    this.classDisplayName = 'N/A';
                }
            },

            async performSearch() {
                console.log('performSearch() called!'); // Log di awal performSearch
                const params = new URLSearchParams();
                if (this.currentView) params.append('view', this.currentView);
                if (this.currentMajorId) params.append('major_id', this.currentMajorId);
                if (this.currentClassId) params.append('class_id', this.currentClassId);
                if (this.searchQuery) params.append('search', this.searchQuery);
                if (this.currentView === 'students' && this.selectedDate) params.append('date', this.selectedDate);

                console.log('Fetching with params:', params.toString());
                try {
                    const res = await fetch(`{{ route('staff.attendance-recap.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    console.log('Raw response from fetch:', res); // Log respons mentah
                    const data = await res.json();
                    console.log('Data yang diambil:', data); // Log data yang diterima untuk debugging
                    if (data.success) {
                        this.data = data.data;
                        this.currentView = data.view;
                        this.selectedDate = data.date;
                        this.currentMajorId = data.majorId;
                        this.currentClassId = data.classId;
                        this.noRecapMessage = data.noRecapMessage; // Update with the message from the controller
                        // Perbarui classDisplayName jika dalam tampilan siswa
                        if (this.currentView === 'students') {
                            this.classDisplayName = data.classDisplayName || (this.data.length > 0 ? this.data[0].class_name : 'N/A');
                        }
                    } else {
                        this.showNotification('Terjadi kesalahan saat memuat data.', 'error');
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                    this.showNotification('Gagal mengambil data. Silakan coba lagi.', 'error');
                }
            },

            async saveChanges() {
                console.log('saveChanges() called!'); // Log di awal saveChanges
                if (this.currentView !== 'students' || !this.currentClassId || !this.selectedDate) {
                    this.showNotification('Tidak dapat menyimpan perubahan. Pastikan Anda berada di tampilan siswa dan kelas serta tanggal terpilih.', 'error');
                    return;
                }
                // Prevent saving if there's no data or a specific "no recap" message
                if (this.data.length === 0 && this.noRecapMessage) {
                    this.showNotification('Tidak ada data untuk disimpan pada tanggal ini.', 'error');
                    return;
                }


                try {
                    const response = await fetch(`{{ route('staff.attendance-recap.update') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            class_id: this.currentClassId,
                            date: this.selectedDate,
                            attendances: this.data.map(record => ({
                                student_id: record.student_id,
                                status: record.status,
                                note: record.note
                            }))
                        })
                    });

                    const result = await response.json();
                    console.log('Result from saveChanges:', result); // Log hasil simpan perubahan

                    if (result.success) {
                        this.showNotification(result.message, 'success');
                        // Ambil kembali data untuk mencerminkan perubahan terbaru dan menghapus status 'dirty'
                        this.performSearch();
                    } else {
                        this.showNotification(result.message || 'Gagal menyimpan perubahan.', 'error');
                    }
                } catch (error) {
                    console.error('Error saving changes:', error);
                    this.showNotification('Terjadi kesalahan saat menyimpan perubahan. Silakan coba lagi.', 'error');
                }
            },

            showNotification(message, type) {
                this.toastMessage = message;
                this.toastType = type;
                this.showToast = true;
                setTimeout(() => {
                    this.showToast = false;
                }, 3000); // Sembunyikan setelah 3 detik
            }
        }
    }
</script>
@endsection