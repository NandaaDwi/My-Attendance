<div x-data="{ show: true }" x-show="show"
     x-transition.opacity.duration.500ms
     @hide-loading-animation.window="show = false"
     class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-gray-900/75 dark:bg-gray-950/80 px-4">

    <div class="flex items-end justify-center mb-6 space-x-1 w-32 h-24">
        @for ($i = 0; $i < 8; $i++)
            <div class="w-4 rounded-full bg-blue-600 dark:bg-blue-400"
                 style="
                    height: 10%;
                    animation: wave-flow 1.6s infinite ease-in-out;
                    animation-delay: {{ $i * 0.1 }}s;">
            </div>
        @endfor
    </div>

    <div class="text-2xl font-bold text-gray-700 dark:text-gray-200">
        <span x-data="{ message: 'Loading...', chars: [] }"
              x-init="chars = message.split('');
                      $nextTick(() => {
                          $el.querySelectorAll('span').forEach((el, i) => {
                              setTimeout(() => {
                                  el.classList.replace('opacity-0', 'opacity-100');
                                  el.classList.replace('translate-y-2', 'translate-y-0');
                              }, i * 70);
                          });
                      });">
            <template x-for="(char, i) in chars" :key="i">
                <span class="inline-block opacity-0 transform translate-y-2 transition duration-300 ease-out"
                      x-text="char"></span>
            </template>
        </span>
    </div>

    <style>
        @keyframes wave-flow {
            0%, 100% { height: 10%; }
            25% { height: 80%; }
            50% { height: 30%; }
            75% { height: 90%; }
        }
    </style>
</div>
