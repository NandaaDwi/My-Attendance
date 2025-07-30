<div class="fixed h-screen z-20" x-data="{
    dropdowns: {
        siswa: false,
        sekolah: false,
        absensi: false
    },
    sidebarOpen: false,

    toggleDropdown(key) {
        this.dropdowns[key] = !this.dropdowns[key];
        // Close other dropdowns
        Object.keys(this.dropdowns).forEach(k => {
            if (k !== key) this.dropdowns[k] = false;
        });
    }
}">

    <!-- Mobile Overlay -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" 
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
        @click="sidebarOpen = false" 
        style="display: none;">
    </div>

    <!-- Sidebar -->
    <div 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-50 w-64 h-full bg-white dark:bg-gray-800 shadow-2xl border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3b82f6&color=ffffff&size=40"
                        alt="Avatar"
                        class="w-10 h-10 rounded-full shadow-sm border-2 border-white dark:border-gray-600">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <button @click="sidebarOpen = false"
                class="lg:hidden p-1 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

            <!-- Dashboard -->
            <a href="{{ route('staff.dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 shadow-sm' : '' }}">
                <i class="fas fa-tachometer-alt text-lg {{ request()->routeIs('staff.dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }} group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Attendance Management Dropdown -->
            <div class="space-y-1">
                <button @click="toggleDropdown('sekolah')"
                    class="flex w-full items-center justify-between px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-700 dark:hover:text-green-300 transition-all duration-200 group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-school text-lg text-gray-500 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200"></i>
                        <span class="font-medium">Manajemen Sekolah</span>
                    </div>
                    <i :class="dropdowns.sekolah ? 'fa-chevron-up' : 'fa-chevron-down'"
                        class="fas text-xs text-gray-400 transition-transform duration-200"></i>
                </button>

                <div x-show="dropdowns.sekolah" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700 space-y-1" style="display: none;">
                    
                    <a href="{{ route('staff.academic_year.index') }}" class="flex items-center py-2.5 px-4 text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-all duration-200 group">
                        <i class="fas fa-calendar-alt mr-3 w-4 text-gray-400 group-hover:text-green-500 transition-colors duration-200"></i>
                        <span>Tahun Ajaran</span>
                    </a>

                    <a href="{{ route('staff.major.index') }}" class="flex items-center py-2.5 px-4 text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-all duration-200 group">
                        <i class="fas fa-chalkboard mr-3 w-4 text-gray-400 group-hover:text-green-500 transition-colors duration-200"></i>
                        <span>Jurusan</span>
                    </a>

                    <a href="{{ route('staff.student_class.index') }}" class="flex items-center py-2.5 px-4 text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-all duration-200 group">
                        <i class="fas fa-chalkboard mr-3 w-4 text-gray-400 group-hover:text-green-500 transition-colors duration-200"></i>
                        <span>Kelas</span>
                    </a>

                </div>
            </div>
            
            <div class="space-y-1">
                <button @click="toggleDropdown('absensi')"
                    class="flex w-full items-center justify-between px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 hover:text-yellow-700 dark:hover:text-yellow-300 transition-all duration-200 group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clipboard-list text-lg text-gray-500 dark:text-gray-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors duration-200"></i>
                        <span class="font-medium">Manajemen Absensi</span>
                    </div>
                    <i :class="dropdowns.absensi ? 'fa-chevron-up' : 'fa-chevron-down'"
                        class="fas text-xs text-gray-400 transition-transform duration-200"></i>
                </button>

                <div x-show="dropdowns.absensi" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="ml-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700 space-y-1" style="display: none;">
                    
                    <a href="{{ route('staff.attendance.index') }}" class="flex items-center py-2.5 px-4 text-sm text-gray-600 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all duration-200 group">
                        <i class="fas fa-check-square mr-3 w-4 text-gray-400 group-hover:text-yellow-500 transition-colors duration-200"></i>
                        <span>Absensi</span>
                    </a>

                    <a href="{{ route('staff.attendance-recap.index') }}" class="flex items-center py-2.5 px-4 text-sm text-gray-600 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all duration-200 group">
                        <i class="fas fa-file-alt mr-3 w-4 text-gray-400 group-hover:text-yellow-500 transition-colors duration-200"></i>
                        <span>Rekap Absensi</span>
                    </a>
                </div>
            </div>
            
            <!-- Divider -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

            <!-- Settings -->
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-100 transition-all duration-200 group">
                <i class="fas fa-cog text-lg text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors duration-200"></i>
                <span class="font-medium">Pengaturan</span>
            </a>

        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex w-full items-center space-x-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 transition-all duration-200 group">
                    <i class="fas fa-sign-out-alt text-lg text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-200"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>

    </div>

    <!-- Mobile Menu Button -->
    <button @click="sidebarOpen = true"
        class="fixed top-3 right-4 z-30 lg:hidden bg-white dark:bg-gray-800 p-3 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
        <i class="fas fa-bars text-gray-700 dark:text-gray-200"></i>
    </button>
</div>
