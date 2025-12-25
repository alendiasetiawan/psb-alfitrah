@props([
    'width' => 'w-auto',
])

<td 
{{ $attributes->merge(['class' => 'px-5 py-3 whitespace-nowrap']) }}>
    {{ $slot }}
</td>
