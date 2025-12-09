@props([
    'width' => 'w-80',
    'href' => null,
    'fancyBoxName' => 'gallery',
    'fancyBoxCaption' => 'Photo'
])

<div 
{{ $attributes->merge([
    'class' => ''.$width.' h-auto rounded-lg overflow-hidden shadow-3xl border-3 border-white'
]) }}>
    <a href="{{ $href }}" data-fancybox="{{ $fancyBoxName }}" data-caption="{{ $fancyBoxCaption }}">
        {{ $slot }}
    </a>
</div>