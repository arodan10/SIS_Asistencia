@props(['active'])

@php
$classes = ($active ?? false)
            ? 'text-yellow-500 bg-gray-700 bg-opacity-50 font-semibold border-l-4 border-indigo-600'
            : '';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
