<div class="space-y-4 text-gray-700 dark:text-gray-300">
    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Umum</h4>

    <div x-data="{
        currentTheme: localStorage.getItem('theme') || 'system',
        toggleTheme() {
            if (this.currentTheme === 'light') {
                this.currentTheme = 'dark';
            } else if (this.currentTheme === 'dark') {
                this.currentTheme = 'system';
            } else { // 'system'
                this.currentTheme = 'light';
            }
            this.applyTheme();
        },
        applyTheme() {
            localStorage.setItem('theme', this.currentTheme);
            if (this.currentTheme === 'dark' || (this.currentTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            this.sendThemeToServer(this.currentTheme);
        },
        sendThemeToServer(theme) {
            fetch('{{ route('admin.settings.changeTheme') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme: theme })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
            })
            .catch(error => {
                console.error('Error updating theme on server:', error);
            });
        },
        init() {
            this.applyTheme(); // Apply theme on component initialization
        }
    }" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
        <label for="theme-toggle" class="text-base font-medium text-gray-800 dark:text-gray-200">Mode Tampilan:</label>
        <div class="flex items-center space-x-2">
            <span x-show="currentTheme === 'light'" class="text-yellow-500"><i class="fas fa-sun"></i></span>
            <span x-show="currentTheme === 'dark'" class="text-indigo-500"><i class="fas fa-moon"></i></span>
            <span x-show="currentTheme === 'system'" class="text-blue-500"><i class="fas fa-desktop"></i></span>
            
            <button @click="toggleTheme()"
                class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :class="{ 'bg-blue-600': currentTheme !== 'light', 'bg-gray-200': currentTheme === 'light' }"
                role="switch" aria-checked="false" aria-labelledby="theme-toggle-label">
                <span aria-hidden="true"
                    :class="{ 'translate-x-5': currentTheme !== 'light', 'translate-x-0': currentTheme === 'light' }"
                    class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
            </button>
            <span class="text-sm font-medium capitalize text-gray-600 dark:text-gray-300" x-text="currentTheme === 'light' ? 'Terang' : (currentTheme === 'dark' ? 'Gelap' : 'Sistem')"></span>
        </div>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Pilih 'Terang' untuk tampilan terang, 'Gelap' untuk tampilan gelap, atau 'Sistem' untuk mengikuti pengaturan tema sistem operasi Anda.
    </p>
</div>