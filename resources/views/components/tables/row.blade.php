@props([
    'striped' => true,
    'hover' => false,
    'loop' => null,
])

@php
    $classString = '';

    if ($hover) {
        $classString .= ' hover:bg-dark/50 transition-colors duration-150 cursor-pointer';
    }

    if ($striped && $loop && $loop->iteration % 2 === 0) {
        $classString .= ' bg-dark/15';
    }
@endphp

<tr {{ $attributes->merge(['class' => trim($classString)]) }}>
    {{ $slot }}
</tr>
