@props([
    'name',
    'label',
    'options',
    'required' => false,
    'selected' => null
])

@php
    $selectedValue = old($name, $selected); // Prioritaskan old() untuk retensi saat validasi gagal
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-900 transition']) }}>
        
        <option value="">-- Pilih --</option>

        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $selectedValue == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
