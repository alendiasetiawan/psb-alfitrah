@props([
    'modalName' => '',
    'isMobile' => false,
])

<flux:modal name="{{ $modalName }}" class="min-w-[22rem]" variant="{{ $isMobile ? 'flyout' : '' }}" position="{{ $isMobile ? 'bottom' : '' }}">
    <div class="space-y-6">
        <div>
            <flux:heading size="xl">
                {{ $heading }}
            </flux:heading>

            {{ $slot }}

            <flux:text class="mt-2">
                {{ $content }}
            </flux:text>
        </div>

        <!--Modal Footer-->
        @isset($deleteButton)
            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="filled">
                        {{ $closeButton }}
                    </flux:button>
                </flux:modal.close>

                <flux:button
                {{ $attributes->merge([
                    'type' => 'button',
                    'variant' => 'danger',
                ]) }}>
                    {{ $deleteButton }}
                </flux:button>
            </div>
        @endisset
        <!--#Modal Footer-->
    </div>
</flux:modal>