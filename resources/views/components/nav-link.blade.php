@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 py-2 rounded-md text-sm font-semibold text-white bg-tecsup-cyan/30 border-b-2 border-tecsup-cyan focus:outline-none transition duration-150 ease-in-out'
    : 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white/70 hover:text-white hover:bg-white/10 border-b-2 border-transparent focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
