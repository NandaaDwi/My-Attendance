@props(['label' => '', 'name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false])

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}"
            class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' =>
                'w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600',
        ]) }}>
    @error($name)
        <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
    @enderror
</div>
