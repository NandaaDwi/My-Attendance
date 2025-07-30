@props(['type' => 'submit', 'color' => 'blue'])

@php
    $colors = [
        'blue' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
        'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white',
        'gray' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 text-white',
    ];
    $colorClass = $colors[$color] ?? $colors['blue'];
@endphp

<button type="{{ $type }}"
    {{ $attributes->merge([
        'class' => "px-4 py-2 rounded focus:outline-none focus:ring-2 {$colorClass} disabled:opacity-50 disabled:cursor-not-allowed",
    ]) }}>
    {{ $slot }}
</button>
