@props([
    'bgImage' => "images/background/learning_class.webp",
    'titleHeight' => 'pt-[24vh]',
    'coverHeight' => 'h-[37vh]',  
])

<div>
    <div class="fixed inset-0 top-0 left-0 right-0 transition-opacity duration-200 -z-10"
        x-data="{ scrollY: 0 }"
        x-init="window.addEventListener('scroll', () => { scrollY = window.scrollY; })"
    >
        <div class="absolute inset-0 bg-cover bg-center -my-22" :style="`background-image: url({{ asset($bgImage) }}); height:60vh; transform: translateY(${scrollY * 0.3}px)`"></div>
        <div class="absolute inset-0 bg-gradient-to-bl from-dark/10 via-dark/50 to-dark/10"></div>
        <div class="relative px-4 {{ $titleHeight }}" :style="`transform: translateY(${scrollY * 0.3}px)`">
            <x-animations.fade-down showTiming="50">
            <div class="flex justify-between">
                <div class="flex flex-col items-start">
                    @isset($label)
                        <flux:badge color="primary" class="mb-2">
                            {{ $label }}
                        </flux:badge>
                    @endisset
                    <flux:heading size="xl" class="truncate max-w-[350px]">{{ $heading }}</flux:heading>
                    {{ $subHeading }}
                    {{-- <div class="flex items-center gap-1">
                        <flux:icon.school variant="micro" class="text-white/75"/>
                        <flux:text variant="soft">Alftirah 1 Jonggol</flux:text>
                    </div>
                    <div class="flex items-center gap-1">
                        <flux:icon.book-marked variant="micro" class="text-white/75"/>
                        <flux:text variant="soft">SDIT</flux:text>
                    </div> --}}
                </div>
                @isset($action)
                    <div class="flex items-end">
                        {{ $action }}
                    </div>
                @endisset
            </div>
            </x-animations.fade-down>
        </div>
    </div>


    <div class="{{ $coverHeight }}"></div>

    {{ $slot }}
</div>
