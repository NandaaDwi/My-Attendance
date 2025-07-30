@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('content')
<div x-data="studentClass()" class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chalkboard text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Manajemen Kelas</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola data kelas dan jurusan siswa</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input type="text" x-model="search" @input.debounce.300="applySearch" placeholder="Cari kelas..."
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>

            <div class="flex-shrink-0">
                <button @click="openModal('create')"
                    class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden sm:inline">Tambah Kelas</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>
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

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-chalkboard mr-2"></i>Nama Kelas
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-graduation-cap mr-2"></i>Jurusan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($classes as $i => $class)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $classes->firstItem() + $i }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <i class="fas fa-chalkboard text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $class->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $class->major->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.student-class.show', $class) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors duration-200"
                                    title="Lihat Detail Kelas">
                                    <i class="fas fa-info-circle mr-1"></i>Detail
                                </a>
                                <button @click='openModal("edit", @json($class))'
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors duration-200"
                                    title="Edit Kelas">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <form method="POST" action="{{ route('admin.student-class.destroy', $class) }}"
                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors duration-200">
                                        <i class="fas fa-trash-alt mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-chalkboard text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada data kelas</h3>
                            <p class="text-gray-600 dark:text-gray-400">Belum ada kelas yang ditambahkan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:hidden">
            @forelse($classes as $i => $class)
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                            <i class="fas fa-chalkboard text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $class->name }}</h3>
                                <div class="mt-2 flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $class->major->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center space-x-3">
                            <a href="{{ route('admin.student-class.show', $class) }}"
                                class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800 transition-colors duration-200">
                                <i class="fas fa-info-circle mr-1"></i>Detail
                            </a>
                            <button @click='openModal("edit", @json($class))'
                                class="inline-flex items-center px-3 py-1.5 border border-indigo-300 text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800 transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <form method="POST" action="{{ route('admin.student-class.destroy', $class) }}"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800 transition-colors duration-200">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chalkboard text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada data kelas</h3>
                <p class="text-gray-600 dark:text-gray-400">Belum ada kelas yang ditambahkan</p>
            </div>
            @endforelse
        </div>
    </div>

    @if($classes->hasPages())
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        {{ $classes->links() }}
    </div>
    @endif

    <div x-show="isOpen" x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" 
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" 
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-8"
        @keydown.escape.window="closeModal()">
        
        <div @click.away="closeModal()"
            class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden w-full max-w-md">
            
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chalkboard text-white"></i>
                    </div>
                    <span x-text="modalMode === 'create' ? 'Tambah Kelas' : 'Edit Kelas'"></span>
                </h2>
                <button @click="closeModal()" 
                    class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6">
                <form x-ref="form"
                    :action="modalMode === 'create' ? '{{ route('admin.student-class.store') }}' : updateUrl"
                    method="POST"
                    class="space-y-6"
                    @submit.prevent="submitForm">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-chalkboard mr-2"></i>Nama Kelas *
                        </label>
                        <input id="name" type="text" name="name" x-model="form.name"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                            placeholder="Masukkan nama kelas"
                            required>
                    </div>

                    <div>
                        <label for="major_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-graduation-cap mr-2"></i>Jurusan *
                        </label>
                        <select id="major_id" name="major_id" x-model="form.major_id" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($majors as $major)
                                <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>{{ $major->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="closeModal()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-colors duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-colors duration-200 font-medium">
                            <i class="fas fa-save mr-2"></i>
                            <span x-text="modalMode === 'create' ? 'Tambah' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function studentClass() {
    return {
        search: '{{ $search ?? '' }}',
        form: {
            id: null,
            name: '',
            major_id: '',
        },
        modalMode: 'create',
        isOpen: false,
        updateUrl: '',

        openModal(mode, studentClass = null) {
            this.modalMode = mode;
            this.isOpen = true;

            if (mode === 'edit' && studentClass) {
                this.form.id = studentClass.id;
                this.form.name = studentClass.name;
                this.form.major_id = studentClass.major_id;
                this.updateUrl = `{{ url('admin/student-class') }}/${studentClass.id}`;
            } else {
                this.form.id = null;
                this.form.name = '';
                this.form.major_id = '';
                this.updateUrl = '';
            }
        },

        closeModal() {
            this.isOpen = false;
            this.form.id = null;
            this.form.name = '';
            this.form.major_id = '';
            this.updateUrl = '';
        },

        applySearch() {
            const params = new URLSearchParams(window.location.search);
            if (this.search) {
                params.set('search', this.search);
            } else {
                params.delete('search');
            }
            window.location.search = params.toString();
        },

        submitForm() {
            this.$refs.form.submit();
        }
    }
}
</script>
@endsection