@props([
    'width' => 'w-80',
])

<div class="{{ $width }} h-auto rounded-lg overflow-hidden shadow-3xl border-3 border-white w-">
    {{ $slot }}
</div>