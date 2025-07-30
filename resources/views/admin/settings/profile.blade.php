<div x-data="{
    open: false,
    activeTab: 'profile', // 'profile', 'general', 'help'
    modalContent: '',
    isLoading: false,

    fetchContent(tab) {
        this.isLoading = true;
        this.activeTab = tab;
        let url = '';
        if (tab === 'profile') {
            url = '{{ route('admin.settings.profile') }}';
        } else if (tab === 'general') {
            url = '{{ route('admin.settings.general') }}';
        } else if (tab === 'help') {
            url = '{{ route('admin.settings.help') }}';
        }

        fetch(url)
            .then(response => response.text())
            .then(data => {
                this.modalContent = data;
            })
            .catch(error => {
                console.error('Error fetching settings content:', error);
                this.modalContent = '<p class="text-red-500">Failed to load content.</p>';
            })
            .finally(() => {
                this.isLoading = false;
            });
    },
    init() {
        // Initial fetch for the profile tab when the modal is opened
        this.$watch('open', (value) => {
            if (value) {
                this.fetchContent(this.activeTab);
            }
        });
    }
}" x-show="open" @keydown.escape.window="open = false" style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-75 flex items-center justify-center p-4">

    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 transition-opacity" aria-hidden="true"></div>

    <div x-show="open" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg">

        <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Pengaturan</h3>
            <button @click="open = false"
                class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row mt-4">
            <div
                class="w-full sm:w-1/3 pr-4 mb-4 sm:mb-0 border-b sm:border-b-0 sm:border-r border-gray-200 dark:border-gray-700">
                <nav class="space-y-1">
                    <button @click="fetchContent('profile')"
                        :class="{ 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300': activeTab === 'profile', 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50': activeTab !== 'profile' }"
                        class="flex items-center w-full px-4 py-2 text-left rounded-md transition-colors duration-200">
                        <i class="fas fa-user mr-3 w-5"></i>
                        <span>Profile</span>
                    </button>
                    <button @click="fetchContent('general')"
                        :class="{ 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300': activeTab === 'general', 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50': activeTab !== 'general' }"
                        class="flex items-center w-full px-4 py-2 text-left rounded-md transition-colors duration-200">
                        <i class="fas fa-cogs mr-3 w-5"></i>
                        <span>Umum</span>
                    </button>
                    <button @click="fetchContent('help')"
                        :class="{ 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300': activeTab === 'help', 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50': activeTab !== 'help' }"
                        class="flex items-center w-full px-4 py-2 text-left rounded-md transition-colors duration-200">
                        <i class="fas fa-question-circle mr-3 w-5"></i>
                        <span>Bantuan</span>
                    </button>
                </nav>
            </div>

            <div class="w-full sm:w-2/3 pl-4">
                <div x-show="isLoading" class="text-center text-gray-500 dark:text-gray-400 py-8">
                    <i class="fas fa-spinner fa-spin text-2xl"></i>
                    <p class="mt-2">Loading...</p>
                </div>
                <div x-show="!isLoading" x-html="modalContent">
                    </div>
            </div>
        </div>
    </div>
</div>