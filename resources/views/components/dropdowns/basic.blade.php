<a x-on:click.stop>
    <flux:dropdown offset="-5" gap="1">
        <flux:button variant="filled" size="sm-circle" square>
            {{ $icon }}
            {{-- <flux:icon.ellipsis-vertical variant="micro" /> --}}
        </flux:button>
        <flux:menu>
            {{ $slot }}
        </flux:menu>
    </flux:dropdown>
</a>