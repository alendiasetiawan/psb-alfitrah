@props([
    'iconName' => 'file-pen-line'
])
<flux:menu.item icon="{{ $iconName }}" {{ $attributes->merge() }}>
    {{ $slot }}
</flux:menu.item>