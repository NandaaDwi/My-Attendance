@props(['type' => 'info', 'message' => null])

@php
    $colors = [
        'success' => 'green',
        'error' => 'red',
        'warning' => 'yellow',
        'info' => 'blue',
    ];
    $color = $colors[$type] ?? 'blue';
    $content = $message ?? $slot;
@endphp

@if ($content->isNotEmpty())
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 4000)" 
        x-transition
        {{ $attributes->merge(['class' => "p-4 mb-4 text-sm text-{$color}-800 bg-{$color}-100 border border-{$color}-300 rounded-lg", 'role' => 'alert']) }}
    >
        {{ $content }}
    </div>
@endif
