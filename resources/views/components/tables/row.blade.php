@props([
    'striped' => false,
    'hover' => false,
    'loop' => null,
])

<tr class="
    {{ $hover ? 'hover:bg-dark/50 transition-colors duration-150 cursor-pointer' : '' }}
    {{ $striped && $loop % 2 === 1 ? 'bg-dark/10' : '' }}
">
    {{ $slot }}
</tr>
