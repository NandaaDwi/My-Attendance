@extends('layouts.admin')

@section('title', 'Tahun Ajaran')

@section('content')
<div x-data="academicYear()" class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Tahun Ajaran</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Kelola data tahun ajaran akademik</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
            <!-- Search Input -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                </div>
                <input type="text" x-model="search" @input.debounce.300="applySearch" placeholder="Cari tahun ajaran..."
                    class="block w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>

            <!-- Add Button -->
            <div class="flex-shrink-0">
                <button @click="openModal('create')"
                    class="inline-flex items-center justify-center bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden sm:inline">Tambah Tahun Ajaran</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
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

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($academicYears as $i => $ay)
                    @php
                        $item = [
                            'id' => $ay->id,
                            'name' => $ay->name,
                            'active' => $ay->active ? 1 : 0,
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $i + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ay->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($ay->active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <button @click='openModal("edit", @json($item))'
                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors duration-200"
                                    title="Edit Tahun Ajaran">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <form method="POST" action="{{ route('admin.academic_year.destroy', $ay) }}"
                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
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
                                <i class="fas fa-calendar-alt text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada data tahun ajaran</h3>
                            <p class="text-gray-600 dark:text-gray-400">Belum ada tahun ajaran yang ditambahkan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden">
            @forelse($academicYears as $i => $ay)
            @php
                $item = [
                    'id' => $ay->id,
                    'name' => $ay->name,
                    'active' => $ay->active ? 1 : 0,
                ];
            @endphp
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $ay->name }}</h3>
                                <div class="mt-2 flex items-center space-x-2">
                                    @if($ay->active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center space-x-3">
                            <button @click='openModal("edit", @json($item))'
                                class="inline-flex items-center px-3 py-1.5 border border-indigo-300 text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800 transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <form method="POST" action="{{ route('admin.academic_year.destroy', $ay) }}"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
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
                    <i class="fas fa-calendar-alt text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada data tahun ajaran</h3>
                <p class="text-gray-600 dark:text-gray-400">Belum ada tahun ajaran yang ditambahkan</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
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
            
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <span x-text="modalMode === 'create' ? 'Tambah Tahun Ajaran' : 'Edit Tahun Ajaran'"></span>
                </h2>
                <button @click="closeModal()" 
                    class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <form x-ref="form"
                    :action="modalMode === 'create' ? '{{ route('admin.academic_year.store') }}' : updateUrl"
                    method="POST"
                    class="space-y-6"
                    @submit.prevent="submitForm">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>Nama Tahun Ajaran *
                        </label>
                        <input id="name" type="text" name="name" x-model="form.name"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                            placeholder="Contoh: 2023/2024"
                            required>
                    </div>

                    <div>
                        <label for="active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-toggle-on mr-2"></i>Status Aktif *
                        </label>
                        <select id="active" name="active" x-model.number="form.active"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                            required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="closeModal()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-colors duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl transition-colors duration-200 font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function academicYear() {
    return {
        search: '{{ $search ?? '' }}',
        isOpen: false,
        modalMode: 'create',
        form: {
            id: null,
            name: '',
            active: 1
        },
        updateUrl: '',

        openModal(mode, data = null) {
            this.modalMode = mode;
            this.isOpen = true;

            if (mode === 'edit' && data) {
                this.form.id = data.id;
                this.form.name = data.name;
                this.form.active = Number(data.active);
                this.updateUrl = `/admin/academic_year/${data.id}`;
            } else {
                this.form.id = null;
                this.form.name = '';
                this.form.active = 1;
                this.updateUrl = '';
            }
        },

        closeModal() {
            this.isOpen = false;
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
            // Validasi sederhana
            if (!this.form.name.trim()) {
                alert('Nama tahun ajaran harus diisi.');
                return;
            }

            // Submit form native
            this.$refs.form.submit();
        }
    }
}
</script>
@endsection
