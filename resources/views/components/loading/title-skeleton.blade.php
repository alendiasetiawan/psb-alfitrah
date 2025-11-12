@props([
    'width' => '1/3',
    'height' => '6'
])

<div class="flex flex-col items-center">
    <div class="h-{{ $height }} bg-gray-300 rounded w-{{ $width }}"></div>
</div>