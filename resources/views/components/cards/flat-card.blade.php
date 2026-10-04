@props([
    'avatarInitial' => "",
    'avatarImage' => "",
    'isLink' => false,
    'labelColor' => 'primary',
    'labelClass' => '',
    'actionButtonMargin' => 'mt-6',
    'clickable' => false,
])

<x-cards.soft-glass-card {{ $attributes->merge() }} rounded="rounded-lg" padding="p-4" clickable="{{ $clickable }}">
    <!--Header-->
    <div class="flex items-center justify-between gap-3">
        <!-- Avatar and Heading -->
        <div class="flex min-w-0 items-center gap-2">
            <div>
                <flux:avatar 
                    :initials="$avatarInitial ? $avatarInitial : null" 
                    :src="$avatarImage ? $avatarImage : null"
                />
            </div>
            <div class="flex min-w-0 flex-col items-start">
                <flux:text size="lg" class="truncate max-w-[200px]">{{ $heading }}</flux:text>
                <flux:text variant="soft" size="sm">{{ $subHeading }}</flux:text>
            </div>
        </div>

        @isset($label)
            <div class="flex items-center {{ $labelClass }}">
                {{ $label }}
            </div>
        @endisset
    </div>

    <!--Content-->
    <div class="mt-2">
        {{ $slot }}
    </div>

    @isset($subContent)
        <div class="mt-3 flex items-center justify-between">
            {{ $subContent }}
        </div>
    @endisset

    @isset($highlight)
        <div class="bg-white/10 shadow-[inset_3px_3px_5px_rgba(255,255,255,0.5)] rounded-xl mt-3 p-2">
            <flux:heading variant="bold" class="font-bold" size="xl">{{ $highlight }}</flux:heading>
        </div>
    @endisset

    <!--Footer-->
    @isset($actionButton)
        <div class="py-2 text-center">
            <!-- Action buttons -->
            <div class="flex justify-center space-x-6 {{ $actionButtonMargin }}">
                {{ $actionButton }}
            </div>
        </div>
    @endisset

</x-cards.soft-glass-card>
