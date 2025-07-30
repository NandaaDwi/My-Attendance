<div
    x-data="{
        open: false, // Initialize locally, x-model will override/sync
        activeTab: 'profile', // 'profile', 'general', 'help'
        modalContent: '',
        isLoading: false,

        init() {
            // Watch the local 'open' state (which is synced by x-model)
            this.$watch('open', (value) => {
                if (value) {
                    this.fetchContent(this.activeTab);
                } else {
                    // Optional: Reset content and active tab when modal closes
                    this.modalContent = '';
                    this.activeTab = 'profile'; // Reset to default tab
                }
            });

            // If the modal is initially rendered as open (e.g., on page load if settingsModalOpen is true)
            // This ensures content is fetched even on initial load if the modal is meant to be open.
            if (this.open) {
                this.fetchContent(this.activeTab);
            }
        },

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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    this.modalContent = data;
                })
                .catch(error => {
                    console.error('Error fetching settings content:', error);
                    this.modalContent = '<p class="text-red-500 p-4">Error loading content. Please try again or check your network connection.</p>';
                })
                .finally(() => {
                    this.isLoading = false;
                });
        }
    }"
    x-show="open"
    x-model="{{ $parentOpenVariable ?? 'false' }}" {{-- Bind to the parent's variable passed from sidebar.blade.php --}}
    @click.outside="{{ $parentOpenVariable ?? 'false' }} = false" {{-- Update parent's variable to close --}}
    @keydown.escape.window="{{ $parentOpenVariable ?? 'false' }} = false" {{-- Update parent's variable to close --}}
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;" {{-- Hidden by default --}}
    aria-labelledby="modal-title" role="dialog" aria-modal="true"
>
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg">

            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                    Pengaturan
                </h3>
                <button @click="{{ $parentOpenVariable ?? 'false' }} = false"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors duration-200">
                    <span class="sr-only">Close panel</span>
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row">
                <div class="flex-shrink-0 w-full sm:w-48 border-b sm:border-b-0 sm:border-r border-gray-200 dark:border-gray-700 pb-4 sm:pr-4 sm:pb-0">
                    <nav class="space-y-1" aria-label="Settings">
                        <button @click="fetchContent('profile')"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300': activeTab === 'profile', 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50': activeTab !== 'profile' }"
                                class="flex items-center w-full px-4 py-2 text-left rounded-md transition-colors duration-200">
                            <i class="fas fa-user-circle mr-3 w-5"></i>
                            <span class="font-medium">Profil</span>
                        </button>
                        <button @click="fetchContent('general')"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300': activeTab === 'general', 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50': activeTab !== 'general' }"
                                class="flex items-center w-full px-4 py-2 text-left rounded-md transition-colors duration-200">
                            <i class="fas fa-cog mr-3 w-5"></i>
                            <span class="font-medium">Umum</span>
                        </button>
                        <button @click="fetchContent('help')"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300': activeTab === 'help', 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50': activeTab !== 'help' }"
                                class="flex items-center w-full px-4 py-2 text-left rounded-md transition-colors duration-200">
                            <i class="fas fa-question-circle mr-3 w-5"></i>
                            <span class="font-medium">Bantuan</span>
                        </button>
                    </nav>
                </div>

                <div class="flex-1 mt-4 sm:mt-0 sm:ml-6">
                    <div x-show="isLoading" class="text-center text-gray-500 dark:text-gray-400 py-8">
                        <i class="fas fa-spinner fa-spin text-4xl"></i>
                        <p class="mt-2">Memuat...</p>
                    </div>
                    <div x-show="!isLoading" x-html="modalContent" class="overflow-y-auto max-h-96">
                        {{-- Content will be loaded here via AJAX --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>