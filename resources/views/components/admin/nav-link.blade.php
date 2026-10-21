@props(['active' => false, 'href' => '#'])

@php
$classes = $active ?? false 
    ? 'flex items-center px-4 py-3 text-sm font-medium text-white bg-gray-900 rounded-md group' 
    : 'flex items-center px-4 py-3 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white group';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
    {{ $slot }}
</a>
