@props([
    'wireTarget' => null,
])
<span wire:loading.remove wire:target='{{ $wireTarget }}'>
    {{ $buttonName }}
</span>
<span wire:loading wire:target='{{ $wireTarget }}'>
    {{ $buttonReplaceName }}
</span>
<span wire:loading wire:target='{{ $wireTarget }}'>
    <flux:icon.loading class="size-4"/>
</span>