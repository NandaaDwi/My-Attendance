@extends('layouts.staff')

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
                    <a href="{{ route('staff.attendance.index') }}"
                        class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Jurusan
                    </a>
                @elseif($view === 'students')
                    <a href="{{ route('staff.attendance.index', ['view' => 'classes', 'major_id' => $majorId]) }}"
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
                        <a href="{{ route('staff.attendance.index', ['view' => 'classes', 'major_id' => $major->id]) }}" class="block">
                            <div class="p-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                    {{ $major->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $major->student_classes_count }} Kelas
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
                        <a href="{{ route('staff.attendance.index', ['view' => 'students', 'major_id' => $majorId, 'class_id' => $class->id]) }}" class="block">
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
                        <p class="text-gray-600 dark:text-gray-400">Coba ubah kata kunci pencarian</p>
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
                        {{-- Added based on admin's logic --}}
                        @if($attendanceForCurrentClassExists)
                            <div class="bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 px-4 py-2 rounded-full text-sm font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                Absensi untuk kelas ini sudah disimpan hari ini
                            </div>
                        @endif
                    </div>
                </div>

                @if($data->count() > 0)
                    <form method="POST" action="{{ route('staff.attendance.store') }}" class="p-6">
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
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex space-x-2">
                                                    @php
                                                        $statuses = [
                                                            'Present' => ['color' => 'green', 'icon' => 'check', 'label' => 'Hadir'],
                                                            'Excused' => ['color' => 'blue', 'icon' => 'user-clock', 'label' => 'Izin'],
                                                            'Sick' => ['color' => 'yellow', 'icon' => 'thermometer-half', 'label' => 'Sakit'],
                                                            'Absent' => ['color' => 'red', 'icon' => 'times', 'label' => 'Alpa']
                                                        ];
                                                        // Use existing_attendance_status from mapped data, or 'Present' as default
                                                        $currentStatus = $student->existing_attendance_status ?? 'Present'; 
                                                    @endphp
                                                    
                                                    @foreach($statuses as $status => $config)
                                                        <label class="relative cursor-pointer">
                                                            <input type="radio" 
                                                                name="attendance[{{ $student->id }}][status]" 
                                                                value="{{ $status }}"
                                                                {{ $currentStatus === $status ? 'checked' : '' }}
                                                                class="sr-only peer"
                                                                @change="updateReason({{ $student->id }}, '{{ $status }}')"
                                                                x-bind:disabled="attendanceForCurrentClassExists"> {{-- Disable if attendance exists --}}
                                                            <div class="flex flex-col items-center p-2 rounded-lg border-2 border-gray-200 dark:border-gray-600 
                                                                peer-checked:border-{{ $config['color'] }}-500 peer-checked:bg-{{ $config['color'] }}-50 dark:peer-checked:bg-{{ $config['color'] }}-900/20
                                                                peer-checked:shadow-lg peer-checked:scale-105
                                                                hover:border-{{ $config['color'] }}-300 hover:bg-{{ $config['color'] }}-25 
                                                                transition-all duration-200 min-w-[60px]">
                                                                <i class="fas fa-{{ $config['icon'] }} text-{{ $config['color'] }}-500 text-sm mb-1"></i>
                                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $config['label'] }}</span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div x-transition>
                                                    <input type="text" 
                                                        name="attendance[{{ $student->id }}][reason]" 
                                                        value="{{ $student->existing_attendance_note ?? '' }}" {{-- Use existing_attendance_note --}}
                                                        placeholder="Masukkan alasan..."
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm"
                                                        x-bind:disabled="attendanceForCurrentClassExists"> {{-- Disable if attendance exists --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button type="submit" 
                                x-bind:disabled="attendanceForCurrentClassExists" {{-- Disable if attendance exists --}}
                                :class="attendanceForCurrentClassExists ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transform hover:-translate-y-0.5'"
                                class="inline-flex items-center justify-center text-white px-8 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Absensi
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
    $initialNeedsReason = $view === 'students' 
        ? $data->mapWithKeys(function($student) { 
            $status = $student->existing_attendance_status ?? 'Present'; 
            return [$student->id => in_array($status, ['Excused', 'Sick', 'Absent'])]; 
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
        needsReason: @json($initialNeedsReason), 
        existingAttendance: @json($existingAttendance->toArray()), 
        attendanceForCurrentClassExists: @json($attendanceForCurrentClassExists), 

        // Initialize state based on initial PHP data
        init() {
            // No need to explicitly set studentStatuses here, as the Blade loop handles initial values.
            // The `needsReason` is correctly initialized via PHP @json($initialNeedsReason)
        },

        performSearch() { 
            const params = new URLSearchParams({ 
                search: this.searchQuery, 
                view: this.view, 
                major_id: this.majorId, 
                class_id: this.classId 
            });

            fetch(`{{ route('staff.attendance.index') }}?${params}`, { 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest', 
                    'Accept': 'application/json' 
                }
            })
            .then(response => response.json()) 
            .then(data => { 
                if (data.success) { 
                    this.updateContent(data.data, data.view, data.existingAttendance, data.attendanceForCurrentClassExists); 
                    // Update current majorId and classId for subsequent searches
                    this.majorId = data.majorId; 
                    this.classId = data.classId;
                }
            })
            .catch(error => console.error('Error:', error)); 
        },

        updateContent(data, view, existingAttendance, attendanceForCurrentClassExists) { 
            const container = document.getElementById('content-container'); 
            let html = ''; 

            // Update Alpine.js properties
            this.existingAttendance = existingAttendance; 
            this.attendanceForCurrentClassExists = attendanceForCurrentClassExists; 
            this.view = view; // Update view to reflect current state for back buttons and search placeholder

            // Reset needsReason for new set of students
            this.needsReason = {}; 
            if (view === 'students') {
                data.forEach(student => {
                    const status = this.existingAttendance[student.id] ? this.existingAttendance[student.id].status : 'Present';
                    this.needsReason[student.id] = ['Excused', 'Sick', 'Absent'].includes(status);
                });
            }

            if (view === 'majors') { 
                html = this.renderMajorCards(data); 
            } else if (view === 'classes') { 
                html = this.renderClassCards(data); 
            } else if (view === 'students') { 
                html = this.renderStudentTable(data); 
            }

            container.innerHTML = html; 
            // Re-initialize Alpine tree after innerHTML update
            this.$nextTick(() => { 
                window.Alpine.initTree(container); 
            });
        },

        renderMajorCards(majors) { 
            // Similar to existing code, ensure routes and Alpine data are correctly passed
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
                        <a href="{{ route('staff.attendance.index') }}?view=classes&major_id=${major.id}" class="block">
                            <div class="p-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                    ${major.name}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    ${major.student_classes_count || 0} Kelas
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
            // Similar to existing code, ensure routes and Alpine data are correctly passed
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
                        <a href="{{ route('staff.attendance.index') }}?view=students&major_id=${this.majorId}&class_id=${classItem.id}" class="block">
                            <div class="p-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 rounded-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chalkboard text-white text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300">
                                    ${classItem.name}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    ${classItem.students ? classItem.students.filter(s => s.status === 'active').length : 0} Siswa Aktif
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

        renderStudentTable(students) {
            const todayFormatted = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
            const todayShort = new Date().toLocaleDateString('id-ID'); // For d/m/Y format

            if (students.length === 0) {
                return `<div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="lg:text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada siswa aktif</h3>
                    <p class="text-gray-600 dark:text-gray-400">Belum ada siswa aktif di kelas ini</p>
                </div>`;
            }

            const existingAttendanceMessage = this.attendanceForCurrentClassExists ? `
                <div class="bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-info-circle mr-2"></i>
                    Absensi untuk kelas ini sudah disimpan hari ini
                </div>` : '';

            const statusOptions = {
                'Present': { color: 'green', icon: 'check', label: 'Hadir' },
                'Excused': { color: 'blue', icon: 'user-clock', label: 'Izin' },
                'Sick': { color: 'yellow', icon: 'thermometer-half', label: 'Sakit' },
                'Absent': { color: 'red', icon: 'times', label: 'Alpa' }
            };

            const rowsHtml = students.map((student, index) => {
                const currentStatus = this.existingAttendance[student.id] ? this.existingAttendance[student.id].status : 'Present';
                const currentNote = this.existingAttendance[student.id] ? this.existingAttendance[student.id].note : '';

                return `
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
                            ${todayShort}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                ${Object.entries(statusOptions).map(([status, config]) => `
                                    <label class="relative cursor-pointer">
                                        <input type="radio" 
                                            name="attendance[${student.id}][status]" 
                                            value="${status}"
                                            ${currentStatus === status ? 'checked' : ''}
                                            class="sr-only peer"
                                            @change="updateReason(${student.id}, '${status}')"
                                            x-bind:disabled="attendanceForCurrentClassExists">
                                        <div class="flex flex-col items-center p-2 rounded-lg border-2 border-gray-200 dark:border-gray-600 
                                            peer-checked:border-${config.color}-500 peer-checked:bg-${config.color}-50 dark:peer-checked:bg-${config.color}-900/20
                                            peer-checked:shadow-lg peer-checked:scale-105
                                            hover:border-${config.color}-300 hover:bg-${config.color}-25 
                                            transition-all duration-200 min-w-[60px]">
                                            <i class="fas fa-${config.icon} text-${config.color}-500 text-sm mb-1"></i>
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">${config.label}</span>
                                        </div>
                                    </label>
                                `).join('')}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div x-transition>
                                <input type="text" 
                                    name="attendance[${student.id}][reason]" 
                                    value="${currentNote}"
                                    placeholder="Masukkan alasan..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm"
                                    x-bind:disabled="attendanceForCurrentClassExists">
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

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
                            ${existingAttendanceMessage}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('staff.attendance.store') }}" class="p-6">
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
                                    ${rowsHtml}
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit" 
                                x-bind:disabled="attendanceForCurrentClassExists"
                                :class="attendanceForCurrentClassExists ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transform hover:-translate-y-0.5'"
                                class="inline-flex items-center justify-center text-white px-8 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Absensi
                            </button>
                        </div>
                    </form>
                </div>
            `;
        },

        updateReason(studentId, status) {
            this.needsReason[studentId] = ['Excused', 'Sick', 'Absent'].includes(status);
        }
    }
}
</script>

<style>
/* Enhanced radio button styles */
.peer:checked ~ div {
    /* Removed redundant background-color and transform as they are defined by peer-checked: classes */
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Status-specific colors with enhanced visibility */
.peer-checked\:border-green-500:checked ~ div { 
    border-color: #10b981 !important; 
    background-color: #f0fdf4 !important;
}
.dark .peer-checked\:bg-green-900\/20:checked ~ div { 
    background-color: rgba(6, 78, 59, 0.3) !important; 
}

.peer-checked\:border-blue-500:checked ~ div { 
    border-color: #3b82f6 !important; 
    background-color: #eff6ff !important;
}
.dark .peer-checked\:bg-blue-900\/20:checked ~ div { 
    background-color: rgba(30, 58, 138, 0.3) !important; 
}

.peer-checked\:border-yellow-500:checked ~ div { 
    border-color: #eab308 !important; 
    background-color: #fefce8 !important;
}
.dark .peer-checked\:bg-yellow-900\/20:checked ~ div { 
    background-color: rgba(113, 63, 18, 0.3) !important; 
}

.peer-checked\:border-red-500:checked ~ div { 
    border-color: #ef4444 !important; 
    background-color: #fef2f2 !important;\r\n}
.dark .peer-checked\:bg-red-900\/20:checked ~ div { 
    background-color: rgba(127, 29, 29, 0.3) !important; 
}

/* Hover effects */
.hover\:border-green-300:hover { border-color: #86efac; }
.hover\:border-blue-300:hover { border-color: #93c5fd; }
.hover\:border-yellow-300:hover { border-color: #fde047; }
.hover\:border-red-300:hover { border-color: #fca5a5; }

.hover\:bg-green-25:hover { background-color: rgba(240, 253, 244, 0.5); }
.hover\:bg-blue-25:hover { background-color: rgba(239, 246, 255, 0.5); }
.hover\:bg-yellow-25:hover { background-color: rgba(254, 252, 232, 0.5); }
.hover\:bg-red-25:hover { background-color: rgba(254, 242, 242, 0.5); }

/* Dark mode hover backgrounds with opacity */
.dark .hover\:bg-green-900\/10:hover { background-color: rgba(6, 78, 59, 0.1); }
.dark .hover\:bg-blue-900\/10:hover { background-color: rgba(30, 58, 138, 0.1); }
.dark .hover\:bg-yellow-900\/10:hover { background-color: rgba(113, 63, 18, 0.1); }
.dark .hover\:bg-red-900\/10:hover { background-color: rgba(127, 29, 29, 0.1); }


/* Icon colors */
.text-green-500 { color: #10b981; }
.text-blue-500 { color: #3b82f6; }
.text-yellow-500 { color: #eab308; }
.text-red-500 { color: #ef4444; }
</style>
@endsection