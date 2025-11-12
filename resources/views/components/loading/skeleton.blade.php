@props([
    'width' => '1/4', //1/3, 20, 30
    'heigth' => '4',
    'variant' => 'soft',
])

@php
    $color = match ($variant) {
        'soft' => 'gray-200',
        'dark' => 'gray-300'
    }
@endphp

<div class="h-{{ $heigth }} bg-{{ $color }} rounded w-{{ $width }}"></div>