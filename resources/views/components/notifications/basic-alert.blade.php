@props([
    'variant' => 'danger',
    'icon' => 'x-circle', //triangle-alert, check-circle
    'isCloseable' => false,
])

<div 
{{ $attributes->merge([
    'x-data' => '{ visible: true }',
    'x-show' => 'visible',
    'x-collapse'
]) }}>
    <div x-show="visible" x-transition>
        <flux:callout icon="{{ $icon }}" variant="{{ $variant }}" inline x-data="{ visible: true }" x-show="visible">
            <flux:callout.heading class="flex gap-2 @max-md:flex-col items-start">
                {{ $title }}
            </flux:callout.heading>

            @isset($subTitle)
                <flux:callout.text>
                    {{ $subTitle }}
                </flux:callout.text>
            @endisset

            @isset($action)
                <x-slot name="actions">
                    {{ $action }}
                    {{-- <flux:button>Change password</flux:button>
                    <flux:button variant="ghost">Review activity</flux:button> --}}
                </x-slot>
            @endisset

            @if ($isCloseable)
                <x-slot name="controls">
                    <flux:button icon="x-mark" variant="filled" x-on:click="visible = false" />
                </x-slot>
            @endif
        </flux:callout>
    </div>
</div>