@props(['disabled' => false])
<input @disabled($disabled) {{ $attributes->merge(['class' => 'tecsup-input']) }}>
