@props([
    'isDivided' => true
])

<div>
    @isset($headerTitle)
    <div class="bg-white/30 shadow-[inset_2px_3px_5px_rgba(255,255,255,0.7)] backdrop-blur-md py-2 px-4 flex justify-between rounded-t-sm">
        <flux:heading size="xl">
            {{ $headerTitle }}
        </flux:heading>
        @isset($headerBadge)
            <flux:badge color="primary" variant="solid">{{ $headerBadge }}</flux:badge>
        @endisset
    </div>
    @endisset
    <ul
    {{ $attributes->class([
        'bg-white/10 shadow-[inset_2px_0px_5px_rgba(255,255,255,0.7)] backdrop-blur-md '.($isDivided ? 'divide-y divide-white/15 divide-rounded-sm' : '').' rounded-b-md'
    ]) }}>
        <!--Isi dengan stacked-list-item.blade.php-->
        {{ $slot }}
    </ul>
</div>
