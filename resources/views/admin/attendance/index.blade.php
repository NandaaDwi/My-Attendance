@extends('layouts.admin')

@section('title', 'Absensi Siswa')

@section('content')
<div x-data="attendanceApp()" class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-clipboard-check text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Absensi Siswa</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                    Kelola absensi siswa berdasarkan jurusan dan kelas
                </p>
            </div>
        </div>
    </div>

    @if(isset($breadcrumb) && count($breadcrumb) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                @foreach($breadcrumb as $index => $item)
                    <li class="inline-flex items-center">
                        @if($index > 0)
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        @endif
                        @if($index === count($breadcrumb) - 1)
                            <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $item }}</span>
                        @else
                            <span class="text-gray-500 dark:text-gray-400">{{ $item }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input type="text" 
                    x-model="searchQuery"
                    @input.debounce.300ms="performSearch()"
                    placeholder="@if($view === 'majors') Cari jurusan... @elseif($view === 'classes') Cari kelas... @else Cari siswa... @endif"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>

            @if($view !== 'majors')
            <div class="flex-shrink-0">
                @if($view === 'classes')
                    <a href="{{ route('admin.attendance.index') }}"
                        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Jurusan
                    </a>
                @elseif($view === 'students')
                    <a href="{{ route('admin.attendance.index', ['view' => 'classes', 'major_id' => $majorId]) }}"
                        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Kelas
                    </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <span class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <span class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div id="content-container">
        @if($view === 'majors')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($data as $major)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
                        <a href="{{ route('admin.attendance.index', ['view' => 'classes', 'major_id' => $major->id]) }}" class="block">
                            <div class="p-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                    {{ $major->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $major->studentClasses->count() }} Kelas
                                </p>
                                <div class="mt-4 inline-flex items-center text-blue-600 dark:text-blue-400 text-sm font-medium group-hover:text-blue-700 dark:group-hover:text-blue-300">
                                    Pilih Jurusan
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>    
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada jurusan ditemukan</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            @if($search) Coba ubah kata kunci pencarian @else Belum ada data yang tersedia @endif
                        </p>
                    </div>
                @endforelse
            </div>

        @elseif($view === 'classes')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($data as $class)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
                        <a href="{{ route('admin.attendance.index', ['view' => 'students', 'major_id' => $majorId, 'class_id' => $class->id]) }}" class="block">
                            <div class="p-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chalkboard text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300">
                                    {{ $class->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $class->students->where('status', 'active')->count() }} Siswa Aktif
                                </p>
                                <div class="mt-4 inline-flex items-center text-green-600 dark:text-green-400 text-sm font-medium group-hover:text-green-700 dark:group-hover:text-green-300">
                                    Pilih Kelas
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-search text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada kelas ditemukan</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            @if($search) Coba ubah kata kunci pencarian @else Belum ada data yang tersedia @endif
                        </p>
                    </div>
                @endforelse
            </div>

        @elseif($view === 'students')
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                Absensi Tanggal: {{ \Carbon\Carbon::today()->format('d F Y') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Total {{ $data->count() }} siswa
                            </p>
                        </div>
                        @if($attendanceForCurrentClassExists)
                            <div class="bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 px-4 py-2 rounded-full text-sm font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                Sudah ada data absensi hari ini untuk kelas ini
                            </div>
                        @endif
                    </div>
                </div>

                @if($data->count() > 0)
                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="p-6">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $classId }}">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($data as $index => $student)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                                            <span class="text-white font-medium text-sm">{{ substr($student->user->name, 0, 2) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->user->name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">NIS: {{ $student->nis }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::today()->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap attendance-radio-group">
                                                <div class="flex space-x-2">
                                                    @php
                                                        $statuses = [
                                                            'Present' => ['color' => 'green', 'icon' => 'check', 'label' => 'Hadir'],
                                                            'Excused' => ['color' => 'blue', 'icon' => 'user-clock', 'label' => 'Izin'],
                                                            'Sick' => ['color' => 'yellow', 'icon' => 'thermometer-half', 'label' => 'Sakit'],
                                                            'Absent' => ['color' => 'red', 'icon' => 'times', 'label' => 'Alpa']
                                                        ];
                                                        $currentStatus = isset($existingAttendance[$student->id]) ? $existingAttendance[$student->id]->status : 'Present';
                                                    @endphp
                                                    
                                                    @foreach($statuses as $status => $config)
                                                        <label class="relative cursor-pointer">
                                                            <input type="radio" 
                                                                name="attendance[{{ $student->id }}][status]" 
                                                                value="{{ $status }}"
                                                                {{ $currentStatus === $status ? 'checked' : '' }}
                                                                class="sr-only peer"
                                                                x-model="studentStatuses['{{ $student->id }}']"
                                                                @change="updateReasonVisibility('{{ $student->id }}', '{{ $status }}')"
                                                                {{ $attendanceForCurrentClassExists ? 'disabled' : '' }}>
                                                            <div class="flex flex-col items-center p-2 rounded-lg border-2 border-gray-200 dark:border-gray-600 
                                                                peer-checked:border-{{ $config['color'] }}-500 peer-checked:bg-{{ $config['color'] }}-100 dark:peer-checked:bg-{{ $config['color'] }}-900/20
                                                                peer-checked:shadow-md peer-checked:scale-105
                                                                hover:border-{{ $config['color'] }}-300 hover:bg-{{ $config['color'] }}-50 
                                                                transition-all duration-200 min-w-[60px]
                                                                {{ $attendanceForCurrentClassExists ? 'opacity-60 cursor-not-allowed' : '' }}">
                                                                <i class="fas fa-{{ $config['icon'] }} text-{{ $config['color'] }}-500 text-sm mb-1"></i>
                                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $config['label'] }}</span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div x-show="needsReason['{{ $student->id }}']" x-transition>
                                                    <input type="text" 
                                                        name="attendance[{{ $student->id }}][reason]" 
                                                        x-bind:value="studentStatuses['{{ $student->id }}'] !== 'Present' ? ( (typeof existingAttendance['{{ $student->id }}'] !== 'undefined' && existingAttendance['{{ $student->id }}'].note !== null) ? existingAttendance['{{ $student->id }}'].note : (studentStatuses['{{ $student->id }}'] === 'Excused' ? 'Izin' : (studentStatuses['{{ $student->id }}'] === 'Sick' ? 'Sakit' : (studentStatuses['{{ $student->id }}'] === 'Absent' ? 'Alpa' : ''))) ) : ''"
                                                        placeholder="Masukkan alasan..."
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm"
                                                        {{ $attendanceForCurrentClassExists ? 'disabled' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button type="submit" 
                                class="inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                {{ $attendanceForCurrentClassExists ? 'disabled' : '' }}
                                x-show="!attendanceForCurrentClassExists">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Absensi
                            </button>
                             <button type="button" 
                                class="inline-flex items-center justify-center bg-gray-400 text-white px-8 py-3 rounded-xl font-medium cursor-not-allowed"
                                x-show="attendanceForCurrentClassExists"
                                disabled>
                                <i class="fas fa-check-double mr-2"></i>
                                Absensi Sudah Disimpan
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada siswa aktif</h3>
                        <p class="text-gray-600 dark:text-gray-400">Belum ada siswa aktif di kelas ini</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@php
    $needsReason = $view === 'students'
        ? $data->pluck('id')->mapWithKeys(function($id) use ($existingAttendance) {
            $status = isset($existingAttendance[$id]) ? $existingAttendance[$id]->status : 'Present';
            return [$id => in_array($status, ['Excused', 'Sick', 'Absent'])];
        })
        : collect();
@endphp

<script>
function attendanceApp() {
    return {
        searchQuery: '{{ $search }}',
        view: '{{ $view }}',
        majorId: '{{ $majorId }}',
        classId: '{{ $classId }}',
        studentStatuses: {}, // Initialize as empty object, will be populated by updateContent
        needsReason: {}, // Initialize as empty object, will be populated by updateContent
        existingAttendance: @json($existingAttendance), // Initial data from PHP
        attendanceForCurrentClassExists: {{ $attendanceForCurrentClassExists ? 'true' : 'false' }},

        init() {
            // Initial load of content based on PHP variables
            this.updateInitialContent(@json($data), '{{ $view }}', @json($existingAttendance));
        },

        updateInitialContent(data, view, existingAttendance) {
            // Populate studentStatuses and needsReason for the initial page load
            if (view === 'students' && data) {
                data.forEach(student => {
                    const statusRecord = existingAttendance[student.id];
                    const initialStatus = statusRecord ? statusRecord.status : 'Present';
                    this.studentStatuses[student.id] = initialStatus;
                    this.needsReason[student.id] = ['Excused', 'Sick', 'Absent'].includes(initialStatus);
                });
            }
        },

        performSearch() {
            // Construct URL parameters for AJAX request
            const params = new URLSearchParams({
                search: this.searchQuery,
                view: this.view,
                major_id: this.majorId,
                class_id: this.classId
            });

            // Fetch data via AJAX
            fetch(`{{ route('admin.attendance.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.updateContent(data.data, data.view, data.existingAttendance, data.attendanceForCurrentClassExists);
                }
            })
            .catch(error => console.error('Error fetching data:', error));
        },

        updateContent(data, view, existingAttendance, attendanceForCurrentClassExists) {
            const container = document.getElementById('content-container');
            let html = '';

            // Clear previous state
            this.studentStatuses = {};
            this.needsReason = {};
            this.existingAttendance = existingAttendance; // Update existingAttendance in Alpine state
            this.attendanceForCurrentClassExists = attendanceForCurrentClassExists; // Update the flag

            if (view === 'majors') {
                html = this.renderMajorCards(data);
            } else if (view === 'classes') {
                html = this.renderClassCards(data);
            } else if (view === 'students') {
                html = this.renderStudentTable(data, existingAttendance, attendanceForCurrentClassExists);
            }

            container.innerHTML = html;
            
            // Re-initialize Alpine components within the new HTML
            this.$nextTick(() => {
                if (window.Alpine) {
                    window.Alpine.initTree(container);
                }
                // Re-populate studentStatuses and needsReason for newly loaded students
                if (view === 'students' && data) {
                    data.forEach(student => {
                        const statusRecord = existingAttendance[student.id];
                        const initialStatus = statusRecord ? statusRecord.status : 'Present';
                        this.studentStatuses[student.id] = initialStatus;
                        this.needsReason[student.id] = ['Excused', 'Sick', 'Absent'].includes(initialStatus);
                    });
                }
            });
        },

        renderMajorCards(majors) {
            if (majors.length === 0) {
                return `<div class="col-span-full text-center py-16">
                    <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada jurusan ditemukan</h3>
                    <p class="text-gray-600 dark:text-gray-400">Coba ubah kata kunci pencarian</p>
                </div>`;
            }

            return `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                ${majors.map(major => `
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
                        <a href="{{ route('admin.attendance.index') }}?view=classes&major_id=${major.id}" class="block">
                            <div class="p-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                    ${major.name}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    ${major.student_classes.length} Kelas
                                </p>
                                <div class="mt-4 inline-flex items-center text-blue-600 dark:text-blue-400 text-sm font-medium group-hover:text-blue-700 dark:group-hover:text-blue-300">
                                    Pilih Jurusan
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                `).join('')}
            </div>`;
        },

        renderClassCards(classes) {
            if (classes.length === 0) {
                return `<div class="col-span-full text-center py-16">
                    <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada kelas ditemukan</h3>
                    <p class="text-gray-600 dark:text-gray-400">Coba ubah kata kunci pencarian</p>
                </div>`;
            }

            return `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                ${classes.map(classItem => `
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
                        <a href="{{ route('admin.attendance.index') }}?view=students&major_id=${this.majorId}&class_id=${classItem.id}" class="block">
                            <div class="p-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chalkboard text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300">
                                    ${classItem.name}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    ${classItem.students_count || 0} Siswa Aktif
                                </p>
                                <div class="mt-4 inline-flex items-center text-green-600 dark:text-green-400 text-sm font-medium group-hover:text-green-700 dark:group-hover:text-green-300">
                                    Pilih Kelas
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                `).join('')}
            </div>`;
        },

        renderStudentTable(students, existingAttendance, attendanceForCurrentClassExists) {
            const todayFormatted = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
            
            let tableRowsHtml = '';
            if (students.length === 0) {
                tableRowsHtml = `
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-600 dark:text-gray-400">
                            Tidak ada siswa aktif ditemukan.
                        </td>
                    </tr>`;
            } else {
                students.forEach((student, index) => {
                    const statusRecord = existingAttendance[student.id];
                    const currentStatus = statusRecord ? statusRecord.status : 'Present';
                    const existingNote = statusRecord ? (statusRecord.note || '') : ''; // Get existing note
                    const needsReasonForStudent = ['Excused', 'Sick', 'Absent'].includes(currentStatus);

                    const statuses = {
                        'Present': { color: 'green', icon: 'check', label: 'Hadir' },
                        'Excused': { color: 'blue', icon: 'user-clock', label: 'Izin' },
                        'Sick': { color: 'yellow', icon: 'thermometer-half', label: 'Sakit' },
                        'Absent': { color: 'red', icon: 'times', label: 'Alpa' }
                    };

                    tableRowsHtml += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                ${index + 1}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">${student.user.name.substring(0, 2)}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">${student.user.name}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">NIS: ${student.nis}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${todayFormatted}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap attendance-radio-group">
                                <div class="flex space-x-2" x-init="$nextTick(() => { studentStatuses['${student.id}'] = '${currentStatus}'; needsReason['${student.id}'] = ${needsReasonForStudent}; })">
                                    ${Object.entries(statuses).map(([statusValue, config]) => `
                                        <label class="relative cursor-pointer">
                                            <input type="radio" 
                                                name="attendance[${student.id}][status]" 
                                                value="${statusValue}"
                                                ${currentStatus === statusValue ? 'checked' : ''}
                                                class="sr-only peer"
                                                x-model="studentStatuses['${student.id}']"
                                                @change="updateReasonVisibility('${student.id}', '${statusValue}')"
                                                ${attendanceForCurrentClassExists ? 'disabled' : ''}>
                                            <div class="flex flex-col items-center p-2 rounded-lg border-2 border-gray-200 dark:border-gray-600 
                                                peer-checked:border-${config.color}-500 peer-checked:bg-${config.color}-100 dark:peer-checked:bg-${config.color}-900/20
                                                peer-checked:shadow-md peer-checked:scale-105
                                                hover:border-${config.color}-300 hover:bg-${config.color}-50 
                                                transition-all duration-200 min-w-[60px]
                                                ${attendanceForCurrentClassExists ? 'opacity-60 cursor-not-allowed' : ''}">
                                                <i class="fas fa-${config.icon} text-${config.color}-500 text-sm mb-1"></i>
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">${config.label}</span>
                                            </div>
                                        </label>
                                    `).join('')}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-show="needsReason['${student.id}']" x-transition>
                                    <input type="text" 
                                        name="attendance[${student.id}][reason]" 
                                        value="${existingNote}"
                                        placeholder="Masukkan alasan..."
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm"
                                        ${attendanceForCurrentClassExists ? 'disabled' : ''}>
                                </div>
                            </td>
                        </tr>`;
                });
            }

            return `
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                    Absensi Tanggal: ${todayFormatted}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Total ${students.length} siswa
                                </p>
                            </div>
                            ${attendanceForCurrentClassExists ? `
                                <div class="bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 px-4 py-2 rounded-full text-sm font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Sudah ada data absensi hari ini untuk kelas ini
                                </div>` : ''}
                        </div>
                    </div>

                    ${students.length > 0 ? `
                    <form method="POST" action="{{ route('admin.attendance.store') }}" class="p-6">
                        @csrf
                        <input type="hidden" name="class_id" value="${this.classId}">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    ${tableRowsHtml}
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button type="submit" 
                                class="inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                ${attendanceForCurrentClassExists ? 'disabled' : ''}
                                x-show="!attendanceForCurrentClassExists">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Absensi
                            </button>
                             <button type="button" 
                                class="inline-flex items-center justify-center bg-gray-400 text-white px-8 py-3 rounded-xl font-medium cursor-not-allowed"
                                x-show="attendanceForCurrentClassExists"
                                disabled>
                                <i class="fas fa-check-double mr-2"></i>
                                Absensi Sudah Disimpan
                            </button>
                        </div>
                    </form>` : `
                    <div class="text-center py-16">
                        <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="lg:font-medium text-gray-900 dark:text-white mb-2">Tidak ada siswa aktif</h3>
                        <p class="text-gray-600 dark:text-gray-400">Belum ada siswa aktif di kelas ini</p>
                    </div>`}
                </div>`;
        },

        updateReasonVisibility(studentId, status) {
            this.needsReason[studentId] = ['Excused', 'Sick', 'Absent'].includes(status);
            // Ensure Alpine reacts to the change
            this.needsReason = { ...this.needsReason }; 
        }
    }
}
</script>


<style>
/* Enhanced radio button styles */
/* Base style for the custom radio button div */
.attendance-radio-group .peer + div {
    transition: all 0.2s ease-in-out;
}

/* Styles when the peer radio input is checked */
.attendance-radio-group .peer:checked ~ div {
    transform: scale(1.05); /* Slight scale up */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    border-width: 2px; /* Ensure border is always 2px when checked */
}

/* Status-specific colors with enhanced visibility */
/* Present (Green) */
.attendance-radio-group .peer-checked\:border-green-500:checked ~ div { 
    border-color: #10B981 !important; /* Tailwind green-500 */
    background-color: #D1FAE5 !important; /* Tailwind green-100 */
    color: #065F46 !important; /* Tailwind green-800 */
}
.dark .attendance-radio-group .peer-checked\:bg-green-900\/20:checked ~ div { 
    background-color: rgba(16, 185, 129, 0.2) !important; /* green-500 with opacity */
    color: #A7F3D0 !important; /* Tailwind green-200 */
}

/* Excused (Blue) - Changed to Blue for clarity as per previous request */
.attendance-radio-group .peer-checked\:border-blue-500:checked ~ div { 
    border-color: #3B82F6 !important; /* Tailwind blue-500 */
    background-color: #DBEAFE !important; /* Tailwind blue-100 */
    color: #1E40AF !important; /* Tailwind blue-800 */
}
.dark .attendance-radio-group .peer-checked\:bg-blue-900\/20:checked ~ div { 
    background-color: rgba(59, 130, 246, 0.2) !important; /* blue-500 with opacity */
    color: #BFDBFE !important; /* Tailwind blue-200 */
}

/* Sick (Yellow) */
.attendance-radio-group .peer-checked\:border-yellow-500:checked ~ div { 
    border-color: #EAB308 !important; /* Tailwind yellow-500 */
    background-color: #FEF3C7 !important; /* Tailwind yellow-100 */
    color: #92400E !important; /* Tailwind yellow-800 */
}
.dark .attendance-radio-group .peer-checked\:bg-yellow-900\/20:checked ~ div { 
    background-color: rgba(234, 179, 8, 0.2) !important; /* yellow-500 with opacity */
    color: #FDE68A !important; /* Tailwind yellow-200 */
}

/* Absent (Red) */
.attendance-radio-group .peer-checked\:border-red-500:checked ~ div { 
    border-color: #EF4444 !important; /* Tailwind red-500 */
    background-color: #FEE2E2 !important; /* Tailwind red-100 */
    color: #991B1B !important; /* Tailwind red-800 */
}
.dark .attendance-radio-group .peer-checked\:bg-red-900\/20:checked ~ div { 
    background-color: rgba(239, 68, 68, 0.2) !important; /* red-500 with opacity */
    color: #FECACA !important; /* Tailwind red-200 */
}

/* Hover effects */
.attendance-radio-group .hover\:border-green-300:hover { border-color: #86EFAC; }
.attendance-radio-group .hover\:border-blue-300:hover { border-color: #93C5FD; } /* Corrected to blue */
.attendance-radio-group .hover\:border-yellow-300:hover { border-color: #FDE047; }
.attendance-radio-group .hover\:border-red-300:hover { border-color: #FCA5A5; }

.attendance-radio-group .hover\:bg-green-50:hover { background-color: #F0FDF4; } /* Lighter hover for normal state */
.attendance-radio-group .hover\:bg-blue-50:hover { background-color: #EFF6FF; } /* Corrected to blue */
.attendance-radio-group .hover\:bg-yellow-50:hover { background-color: #FEFCE8; }
.attendance-radio-group .hover\:bg-red-50:hover { background-color: #FEF2F2; }

/* Dark mode hover backgrounds with opacity */
.dark .attendance-radio-group .hover\:bg-green-900\/10:hover { background-color: rgba(6, 78, 59, 0.1); }
.dark .attendance-radio-group .hover\:bg-blue-900\/10:hover { background-color: rgba(30, 58, 138, 0.1); } /* Corrected to blue */
.dark .attendance-radio-group .hover\:bg-yellow-900\/10:hover { background-color: rgba(113, 63, 18, 0.1); }
.dark .attendance-radio-group .hover\:bg-red-900\/10:hover { background-color: rgba(127, 29, 29, 0.1); }


/* Icon colors - ensure they are correctly applied by Tailwind or custom CSS */
.text-green-500 { color: #10B981; }
.text-blue-500 { color: #3B82F6; } /* Corrected to blue */
.text-yellow-500 { color: #EAB308; }
.text-red-500 { color: #EF4444; }
</style>
@endsection