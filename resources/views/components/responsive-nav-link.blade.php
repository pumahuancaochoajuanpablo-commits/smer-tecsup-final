@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full px-4 py-2 text-sm text-white bg-tecsup-cyan rounded-md font-semibold transition duration-150 ease-in-out'
    : 'block w-full px-4 py-2 text-sm text-white/70 hover:text-white hover:bg-white/10 rounded-md transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
