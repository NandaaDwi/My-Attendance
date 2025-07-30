@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Edit User</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Perbarui informasi user: <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $user->name }}</span></p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Terdapat kesalahan pada form:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Section -->
    <div x-data="{ 
        photoPreview: null,
        photoName: '',
        
        previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Hanya file gambar (JPEG, JPG, PNG, GIF) yang diperbolehkan!');
                    event.target.value = '';
                    this.photoPreview = null;
                    this.photoName = '';
                    return;
                }
                
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB!');
                    event.target.value = '';
                    this.photoPreview = null;
                    this.photoName = '';
                    return;
                }
                
                this.photoName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.photoPreview = null;
                this.photoName = '';
            }
        },
        
        removePhoto() {
            this.photoPreview = null;
            this.photoName = '';
            document.querySelector('input[name=photo]').value = '';
        }
    }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-user-circle mr-3 text-blue-600 dark:text-blue-400"></i>
                    Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-2"></i>Nama Lengkap *
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $user->name) }}" 
                            placeholder="Masukkan nama lengkap" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email *
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}" 
                            placeholder="contoh@email.com" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                    </div>

                    <!-- Role (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-tag mr-2"></i>Role
                        </label>
                        <input 
                            type="text" 
                            value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white cursor-not-allowed"
                            readonly
                        >
                    </div>

                    <!-- Jenis Kelamin -->
                    @if($user->role !== 'admin')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-venus-mars mr-2"></i>Jenis Kelamin *
                        </label>
                        <select 
                            name="gender" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="M" {{ old('gender', optional($user->studentDetail)->gender ?? optional($user->employeeDetail)->gender ?? '') === 'M' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="F" {{ old('gender', optional($user->studentDetail)->gender ?? optional($user->employeeDetail)->gender ?? '') === 'F' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Role-specific fields -->
            @if($user->role === 'student')
            <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-user-graduate mr-3 text-indigo-600 dark:text-indigo-400"></i>
                    Detail Siswa
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-id-card mr-2"></i>NIS *
                        </label>
                        <input 
                            type="text" 
                            name="nis" 
                            value="{{ old('nis', optional($user->studentDetail)->nis) }}" 
                            placeholder="Masukkan NIS" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-id-card-alt mr-2"></i>NISN *
                        </label>
                        <input 
                            type="text" 
                            name="nisn" 
                            value="{{ old('nisn', optional($user->studentDetail)->nisn) }}" 
                            placeholder="Masukkan NISN" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-chalkboard mr-2"></i>Kelas *
                        </label>
                        <select 
                            name="class_id" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', optional($user->studentDetail)->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-toggle-on mr-2"></i>Status *
                        </label>
                        <select 
                            name="status" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                            <option value="">Pilih Status</option>
                            <option value="active" {{ old('status', optional($user->studentDetail)->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', optional($user->studentDetail)->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            @elseif(in_array($user->role, ['staff', 'homeroom_teacher']))
            <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-chalkboard-teacher mr-3 text-purple-600 dark:text-purple-400"></i>
                    Detail {{ $user->role === 'homeroom_teacher' ? 'Wali Kelas' : 'Staff' }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-id-badge mr-2"></i>NIP *
                        </label>
                        <input 
                            type="text" 
                            name="nip" 
                            value="{{ old('nip', optional($user->employeeDetail)->nip) }}" 
                            placeholder="Masukkan NIP" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                    </div>
                    @if($user->role === 'homeroom_teacher')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-chalkboard mr-2"></i>Kelas
                        </label>
                        <select 
                            name="class_id" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', optional($user->employeeDetail)->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>
            @elseif($user->role === 'parent_student')
            <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-users mr-3 text-teal-600 dark:text-teal-400"></i>
                    Detail Orang Tua
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-2"></i>Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="{{ old('full_name', optional($user->parentDetail)->full_name) }}" 
                            placeholder="Nama lengkap orang tua" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-briefcase mr-2"></i>Pekerjaan *
                        </label>
                        <input 
                            type="text" 
                            name="occupation" 
                            value="{{ old('occupation', optional($user->parentDetail)->occupation) }}" 
                            placeholder="Masukkan pekerjaan" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-heart mr-2"></i>Hubungan *
                        </label>
                        <select 
                            name="relationship" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                            <option value="">Pilih Hubungan</option>
                            @foreach(['father' => 'Ayah', 'mother' => 'Ibu', 'guardian' => 'Wali'] as $key => $label)
                                <option value="{{ $key }}" {{ old('relationship', optional($user->parentDetail)->relationship) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email Orang Tua
                        </label>
                        <input 
                            type="email" 
                            name="parent_email" 
                            value="{{ old('parent_email', optional($user->parentDetail)->email) }}" 
                            placeholder="email@orangtua.com" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-graduate mr-2"></i>Siswa *
                        </label>
                        <select 
                            name="student_id" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            required
                        >
                            <option value="">Pilih Siswa</option>
                            @foreach(\App\Models\StudentDetail::with('user')->get() as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', optional($user->parentDetail)->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} ({{ $student->nis }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @endif

            <!-- Personal Information Section -->
            @if($user->role !== 'admin')
            <div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-id-card mr-3 text-green-600 dark:text-green-400"></i>
                    Informasi Personal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Tempat Lahir
                        </label>
                        <input 
                            type="text" 
                            name="place_of_birth" 
                            value="{{ old('place_of_birth', optional($user->studentDetail)->place_of_birth ?? optional($user->employeeDetail)->place_of_birth) }}" 
                            placeholder="Masukkan tempat lahir" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>Tanggal Lahir
                        </label>
                        <input 
                            type="date" 
                            name="date_of_birth" 
                            value="{{ old('date_of_birth', optional($user->studentDetail)->date_of_birth ?? optional($user->employeeDetail)->date_of_birth) }}" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <!-- Agama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-pray mr-2"></i>Agama
                        </label>
                        <input 
                            type="text" 
                            name="religion" 
                            value="{{ old('religion', optional($user->studentDetail)->religion ?? optional($user->employeeDetail)->religion) }}" 
                            placeholder="Masukkan agama" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2"></i>Nomor HP
                        </label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone', optional($user->studentDetail)->phone ?? optional($user->employeeDetail)->phone ?? optional($user->parentDetail)->phone) }}" 
                            placeholder="08xxxxxxxxxx" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-home mr-2"></i>Alamat
                    </label>
                    <textarea 
                        name="address" 
                        rows="3"
                        placeholder="Masukkan alamat lengkap"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                    >{{ old('address', optional($user->studentDetail)->address ?? optional($user->employeeDetail)->address ?? optional($user->parentDetail)->address) }}</textarea>
                </div>

                <!-- Current Photo Display -->
                @php
                    $currentPhoto = optional($user->studentDetail)->photo ?? optional($user->employeeDetail)->photo;
                @endphp
                @if($currentPhoto)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-image mr-2"></i>Foto Saat Ini
                    </label>
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . $currentPhoto) }}" alt="Foto User" class="w-24 h-24 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <p class="font-medium">Foto profil saat ini</p>
                            <p>Upload foto baru untuk menggantinya</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Photo Preview Area -->
                <div x-show="photoPreview" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600"
                     style="display: none;">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <img :src="photoPreview" 
                                 alt="Preview" 
                                 class="w-24 h-24 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-600 shadow-sm">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="photoName"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Preview foto yang akan diupload</p>
                                </div>
                                <button type="button" 
                                        @click="removePhoto()"
                                        class="inline-flex items-center px-3 py-1.5 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/30 transition-colors duration-200">
                                    <i class="fas fa-trash-alt mr-1"></i>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload New Photo -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-camera mr-2"></i>{{ $currentPhoto ? 'Upload Foto Baru (Opsional)' : 'Upload Foto Profil' }}
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600 transition-colors duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG atau JPEG (MAX. 2MB)</p>
                            </div>
                            <input type="file" 
                                   name="photo" 
                                   @change="previewPhoto($event)"
                                   accept="image/*" 
                                   class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            @endif

            <!-- Security Section -->
            <div class="pb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-shield-alt mr-3 text-red-600 dark:text-red-400"></i>
                    Keamanan (Opsional)
                </h3>
                
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5 mr-2"></i>
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            Kosongkan field password jika tidak ingin mengubah password user.
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2"></i>Password Baru
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Masukkan password baru" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2"></i>Konfirmasi Password Baru
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="Ulangi password baru" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button 
                    type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-8 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
                
                <a 
                    href="{{ route('admin.users.index') }}"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-8 py-3 rounded-xl transition-all duration-200 font-medium border border-gray-300 dark:border-gray-600"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
