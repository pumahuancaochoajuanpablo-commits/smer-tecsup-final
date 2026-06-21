@props(['value'])
<label {{ $attributes }}>
    <span class="tecsup-label">{{ $value ?? $slot }}</span>
</label>
