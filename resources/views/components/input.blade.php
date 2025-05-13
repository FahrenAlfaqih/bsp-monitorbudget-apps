@props([
    'name',
    'type' => 'text',
    'label',
    'required' => false,
    'value' => null,
])

@php
$error = $errors->has($name);
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        value="{{ old($name, $value) }}"
        {{ $attributes->merge([
            'class' =>
                'w-full border rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:outline-none text-gray-900 transition ' .
                ($error ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500')
        ]) }}
    >

    @if ($error)
        <p class="text-sm text-red-600 mt-1">{{ $errors->first($name) }}</p>
    @endif
</div>
