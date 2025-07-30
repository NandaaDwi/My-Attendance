@props(['title', 'value', 'icon', 'color'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 flex items-center justify-between">
    <div>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $title }}</p>
        <p class="text-3xl font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400 mt-2">{{ $value }}</p>
    </div>
    <div class="p-3 bg-{{ $color }}-100 dark:bg-{{ $color }}-900 rounded-lg">
        <i class="fas {{ $icon }} text-{{ $color }}-600 dark:text-{{ $color }}-400 text-2xl"></i>
    </div>  
</div>
