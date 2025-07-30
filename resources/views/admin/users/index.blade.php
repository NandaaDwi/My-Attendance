@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
    <div x-data="{
        search: '{{ $search ?? '' }}',
        selectedRole: '{{ $roleFilter ?? '' }}',
        allUsers: @js($users), // All user data from the controller
        filteredUsers: [], // Users after search/role filter
        showModal: false,
        selectedUser: null,
        perPage: 10, // Number of items per page
        currentPage: 1, // Current page

        init() {
            this.filterUsers();
        },

        filterUsers() {
            this.filteredUsers = this.allUsers.filter(user => {
                const matchesSearch = !this.search ||
                    user.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.search.toLowerCase());
    
                const matchesRole = !this.selectedRole || user.role === this.selectedRole;
    
                return matchesSearch && matchesRole;
            });
            this.currentPage = 1; // Reset to the first page when filters change
        },

        paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredUsers.slice(start, end);
        },

        totalPages() {
            return Math.ceil(this.filteredUsers.length / this.perPage);
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages()) {
                this.currentPage = page;
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages()) {
                this.currentPage++;
            }
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },

        // New function to get visible page numbers for pagination
        getPagesToShow() {
            const total = this.totalPages();
            const current = this.currentPage;
            const delta = 2; // Number of pages to show around the current page
            const range = [];
            const rangeWithDots = [];
            let l;

            range.push(1); // Always include the first page

            for (let i = current - delta; i <= current + delta; i++) {
                if (i < total && i > 1) {
                    range.push(i);
                }
            }
            range.push(total); // Always include the last page

            // Remove duplicates and sort
            const uniqueRange = [...new Set(range)].sort((a, b) => a - b);

            // Add ellipses
            uniqueRange.forEach(i => {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l !== 1) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            });
            return rangeWithDots;
        },

        getRoleDisplayName(role) {
            const roleNames = {
                'admin': 'Admin',
                'staff': 'Staff',
                'homeroom_teacher': 'Wali Kelas',
                'student': 'Siswa',
                'parent_student': 'Orang Tua'
            };
            return roleNames[role] || role;
        },

        getRoleBadgeClass(role) {
            const classes = {
                'admin': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'staff': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'homeroom_teacher': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                'student': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'parent_student': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
            };
            return classes[role] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        }
    }" x-init="filterUsers()" class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">

        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users-cog text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Manajemen User</h1>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                            Kelola pengguna sistem berdasarkan peran dan aksesnya
                            <span x-show="selectedRole" class="inline-flex items-center ml-2 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Filter: <span x-text="getRoleDisplayName(selectedRole)" class="ml-1"></span>
                            </span>
                        </p>
                    </div>
                </div>
                
                <!-- Add User Button -->
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.users.create') }}"
                        class="inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="hidden sm:inline">Tambah User</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4 lg:items-center">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input x-model="search" @input="filterUsers()" type="text" placeholder="Cari nama atau email..."
                        class="block w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
                <!-- Role Filter (Optional, if you want to keep it) -->
                {{-- <div class="relative">
                    <select x-model="selectedRole" @change="filterUsers()"
                        class="block w-full py-3 pl-4 pr-10 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="homeroom_teacher">Wali Kelas</option>
                        <option value="student">Siswa</option>
                        <option value="parent_student">Orang Tua</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                        <i class="fas fa-chevron-down text-sm"></i>
                    </div>
                </div> --}}
            </div>

            <!-- Results Counter -->
            <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-info-circle mr-2"></i>
                Menampilkan <span x-text="paginatedUsers().length" class="font-semibold text-blue-600 dark:text-blue-400"></span> 
                dari <span x-text="filteredUsers.length" class="font-semibold text-blue-600 dark:text-blue-400"></span> user
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
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

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>User
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-envelope mr-2"></i>Email
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-user-tag mr-2"></i>Role
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Detail
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="user in paginatedUsers()" :key="user.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img :src="user.photo ? `/storage/${user.photo}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=3b82f6&color=ffffff&size=48`"
                                                 :alt="user.name"
                                                 class="h-12 w-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="user.name"></div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400" x-text="user.created_at ? new Date(user.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-'"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white" x-text="user.email"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getRoleBadgeClass(user.role)"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                        x-text="getRoleDisplayName(user.role)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <template x-if="user.role === 'student'">
                                        <div>NIS: <span x-text="user.nis || '-'"></span><br>Kelas: <span x-text="user.class_name || '-'"></span></div>
                                    </template>
                                    <template x-else-if="['staff', 'homeroom_teacher'].includes(user.role)">
                                        <div>NIP: <span x-text="user.nip || '-'"></span></div>
                                        <template x-if="user.role === 'homeroom_teacher'">
                                            <div>Wali Kelas: <span x-text="user.class_name || '-'"></span></div>
                                        </template>
                                    </template>
                                    <template x-else-if="user.role === 'parent_student'">
                                        <div>Hubungan: <span x-text="user.relationship || '-'"></span><br>Anak: <span x-text="user.student_name || '-'"></span></div>
                                    </template>
                                    <template x-else-if="user.role === 'admin'">
                                        <div>Administrator</div>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <button @click="selectedUser = user; showModal = true" 
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors duration-200"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </button>
                                        <a :href="`/admin/users/${user.id}/edit`"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form :action="`/admin/users/${user.id}`" method="POST" class="inline"
                                            @submit.prevent="if(confirm('Yakin ingin menghapus user ini?')) $el.submit()">
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
                        </template>
                        <template x-if="paginatedUsers().length === 0">
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-400">Tidak ada user ditemukan.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden">
                <template x-for="user in paginatedUsers()" :key="user.id">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <img :src="user.photo ? `/storage/${user.photo}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=3b82f6&color=ffffff&size=48`"
                                     :alt="user.name"
                                     class="h-12 w-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="user.name"></h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="user.email"></p>
                                        <div class="mt-2 flex items-center space-x-2">
                                            <span :class="getRoleBadgeClass(user.role)"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                x-text="getRoleDisplayName(user.role)">
                                            </span>
                                        </div>

                                        <!-- Role-specific info -->
                                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            <template x-if="user.role === 'student'">
                                                <div>NIS: <span x-text="user.nis || '-'"></span></div>
                                                <div>Kelas: <span x-text="user.class_name || '-'"></span></div>
                                            </template>
                                            <template x-else-if="['staff', 'homeroom_teacher'].includes(user.role)">
                                                <div>NIP: <span x-text="user.nip || '-'"></span></div>
                                                <template x-if="user.role === 'homeroom_teacher'">
                                                    <div>Wali Kelas: <span x-text="user.class_name || '-'"></span></div>
                                                </template>
                                            </template>
                                            <template x-else-if="user.role === 'parent_student'">
                                                <div>Hubungan: <span x-text="user.relationship || '-'"></span></div>
                                                <div>Anak: <span x-text="user.student_name || '-'"></span></div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center space-x-3">
                                    <button @click="selectedUser = user; showModal = true" 
                                        class="inline-flex items-center px-3 py-1.5 border border-indigo-300 text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </button>
                                    <a :href="`/admin/users/${user.id}/edit`"
                                        class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800 transition-colors duration-200">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form :action="`/admin/users/${user.id}`" method="POST" class="inline"
                                        @submit.prevent="if(confirm('Yakin ingin menghapus user ini?')) $el.submit()">
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
                </template>
                <template x-if="paginatedUsers().length === 0">
                    <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                        Tidak ada user ditemukan.
                    </div>
                </template>
            </div>

            <!-- Pagination Links -->
            <div x-show="totalPages() > 1" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-center items-center space-x-2">
                <button @click="prevPage()" :disabled="currentPage === 1"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200"
                    :class="{ 'bg-blue-600 text-white hover:bg-blue-700': currentPage > 1, 'bg-gray-200 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400': currentPage === 1 }">
                    Previous
                </button>
                <template x-for="page in getPagesToShow()" :key="page">
                    <button x-show="page !== '...'" @click="goToPage(page)"
                        class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200"
                        :class="{ 'bg-blue-600 text-white': currentPage === page, 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600': currentPage !== page }">
                        <span x-text="page"></span>
                    </button>
                    <span x-show="page === '...'" class="px-3 py-1.5 text-gray-500 dark:text-gray-400">...</span>
                </template>
                <button @click="nextPage()" :disabled="currentPage === totalPages()"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors duration-200"
                    :class="{ 'bg-blue-600 text-white hover:bg-blue-700': currentPage < totalPages(), 'bg-gray-200 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400': currentPage === totalPages() }">
                    Next
                </button>
            </div>

        </div>


        <!-- Modal Detail User -->
        <div x-show="showModal" x-cloak 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" 
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-8">
            
            <div @click.away="showModal = false"
                class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl max-h-[90vh] flex flex-col">
                
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-id-card text-white"></i>
                        </div>
                        Detail Pengguna
                    </h2>
                    <button @click="showModal = false" 
                        class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div x-show="selectedUser" class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Profile Section -->
                            <div class="lg:col-span-1">
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl p-6 text-center">
                                    <div class="w-32 h-32 mx-auto mb-4 relative">
                                        <img :src="selectedUser.photo ? `/storage/${selectedUser.photo}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(selectedUser.name)}&background=3b82f6&color=ffffff&size=128`"
                                            :alt="selectedUser.name"
                                            class="w-full h-full object-cover rounded-full border-4 border-white dark:border-gray-600 shadow-lg">
                                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-4 border-white dark:border-gray-600 rounded-full"></div>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2" x-text="selectedUser.name"></h3>
                                    <p class="text-gray-600 dark:text-gray-300 mb-3" x-text="selectedUser.email"></p>
                                    <span :class="getRoleBadgeClass(selectedUser.role)"
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold"
                                        x-text="getRoleDisplayName(selectedUser.role)"></span>
                                </div>
                            </div>

                            <!-- Details Section -->
                            <div class="lg:col-span-2">
                                <div class="space-y-6">
                                    <!-- Basic Information -->
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                            <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                                            Informasi Dasar
                                        </h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Nama Lengkap:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.name"></p>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Email:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.email"></p>
                                            </div>
                                            <div x-show="selectedUser.gender">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Jenis Kelamin:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.gender === 'M' ? 'Laki-laki' : (selectedUser.gender === 'F' ? 'Perempuan' : '-')"></p>
                                            </div>
                                            <div x-show="selectedUser.phone">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Telepon:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.phone || '-'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Role-specific Information -->
                                    <template x-if="selectedUser.role === 'student'">
                                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6">
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                                <i class="fas fa-user-graduate mr-2 text-green-500"></i>
                                                Informasi Siswa
                                            </h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">NIS:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.nis || '-'"></p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">NISN:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.nisn || '-'"></p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">Kelas:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.class_name || '-'"></p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">Status:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.status || '-'"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="['staff', 'homeroom_teacher'].includes(selectedUser.role)">
                                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-6">
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                                <i class="fas fa-chalkboard-teacher mr-2 text-purple-500"></i>
                                                Informasi Pegawai
                                            </h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">NIP:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.nip || '-'"></p>
                                                </div>
                                                <div x-show="selectedUser.role === 'homeroom_teacher'">
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">Wali Kelas:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.class_name || '-'"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="selectedUser.role === 'parent_student'">
                                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6">
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                                <i class="fas fa-user-friends mr-2 text-yellow-500"></i>
                                                Informasi Orang Tua
                                            </h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">Nama Lengkap:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.full_name || selectedUser.name"></p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">Pekerjaan:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.occupation || '-'"></p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">Hubungan:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.relationship || '-'"></p>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">Anak:</span>
                                                    <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.student_name || '-'"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Personal Information -->
                                    <div x-show="selectedUser.place_of_birth || selectedUser.date_of_birth || selectedUser.religion || selectedUser.address" 
                                        class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                            <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                            Informasi Personal
                                        </h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                            <div x-show="selectedUser.place_of_birth">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Tempat Lahir:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.place_of_birth || '-'"></p>
                                            </div>
                                            <div x-show="selectedUser.date_of_birth">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Tanggal Lahir:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.date_of_birth || '-'"></p>
                                            </div>
                                            <div x-show="selectedUser.religion">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Agama:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.religion || '-'"></p>
                                            </div>
                                            <div x-show="selectedUser.address" class="sm:col-span-2">
                                                <span class="font-medium text-gray-600 dark:text-gray-300">Alamat:</span>
                                                <p class="text-gray-800 dark:text-white mt-1" x-text="selectedUser.address || '-'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex justify-end space-x-3">
                        <a x-show="selectedUser" :href="`/admin/users/${selectedUser.id}/edit`"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-200 font-medium">
                            <i class="fas fa-edit mr-2"></i>
                            Edit User
                        </a>
                        <button @click="showModal = false"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-colors duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
