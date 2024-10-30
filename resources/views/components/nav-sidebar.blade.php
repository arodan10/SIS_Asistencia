@props(['active'])

@php
$classes = ($active ?? false)
            ? 'text-yellow-500 bg-violet-800 bg-opacity-50 font-semibold'
            : '';
@endphp

<a {{ $attributes->merge(['class' => 'flex items-center gap-2 px-3 py-2 text-gray-400 hover:bg-violet-700 hover:bg-opacity-25 hover:text-gray-100 rounded-lg ' . $classes]) }}>
    {{ $slot }}
</a>
