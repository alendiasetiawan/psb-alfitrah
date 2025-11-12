<div>
    @isset($headerTitle)
    <div class="bg-gray-300 py-2 px-4 flex justify-between rounded-t-md">
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
        'bg-white shadow divide-y divide-gray-200 rounded-b-md'
    ]) }}>
        <!--Isi dengan stacked-list-item.blade.php-->
        {{ $slot }}
    </ul>
</div>
