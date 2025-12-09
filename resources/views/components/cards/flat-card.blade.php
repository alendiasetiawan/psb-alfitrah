@props([
    'avatarInitial' => "",
    'avatarImage' => "",
    'isLink' => false,
    'labelColor' => 'primary',
])

<x-cards.soft-glass-card {{ $attributes->merge() }} rounded="rounded-lg" padding="p-4">
    <!--Header-->
    <div class="flex items-center justify-between">
        <!-- Avatar and Heading -->
        <div class="flex items-center gap-2">
            <div>
                <flux:avatar 
                    :initials="$avatarInitial ? $avatarInitial : null" 
                    :src="$avatarImage ? $avatarImage : null"
                />
            </div>
            <div class="flex flex-col items-start">
                <flux:text size="lg">{{ $heading }}</flux:text>
                <flux:text variant="soft" size="sm">{{ $subHeading }}</flux:text>
            </div>
        </div>

        @isset($label)
            <div class="flex items-center">
                {{ $label }}
            </div>
        @endisset
    </div>

    <!--Content-->
    <div class="mt-2">
        {{ $slot }}
    </div>

    @isset($subContent)
        <div class="mt-2 flex items-center justify-between">
            {{ $subContent }}
        </div>
    @endisset

    @isset($highlight)
        <div class="bg-white/20 shadow-[inset_0px_2px_3px_rgba(255,255,255,0.5)] backdrop-blur-sm backdrop-filter rounded-xl mt-2 mb-2 p-2">
            <flux:heading variant="bold" class="font-bold" size="xl">{{ $highlight }}</flux:heading>
        </div>
    @endisset

    <!--Footer-->
    @isset($actionButton)
        <div class="py-2 text-center">
            <!-- Action buttons -->
            <div class="flex justify-center space-x-6 mt-6">
                {{ $actionButton }}
            </div>
        </div>
    @endisset

</x-cards.soft-glass-card>