@props([
    'width' => '20',
    'height' => '20'
])

<a {{ $attributes->merge([
    'href' => null,
    'target' => '_blank',
    'class' => 'hover:cursor-pointer'
]) }}>
    <img src="{{ asset('images/icon/wa-icon.svg') }}" width="{{ $width }}" height="{{ $height }}" alt="whatsapp" />
</a>
