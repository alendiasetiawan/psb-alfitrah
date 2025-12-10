@props([
    'rounded' => 'rounded-2xl',
    'padding' => 'px-4 py-4',
    'subCounterColor' => 'white'
])

<div {{ $attributes->merge([
    'class' => "relative overflow-hidden $rounded",
]) }}>

    {{-- MASKED GRADIENT BORDER (Tailwind v4) --}}
    <div 
        class="pointer-events-none absolute inset-0 p-[2px] {{ $rounded }} bg-gradient-to-br from-white/20 via-white/10 to-white/5 shadow-[inset_2px_3px_5px_rgba(255,255,255,0.7)] z-[1]">
    </div>

    {{-- LIQUID GEL BORDER (soft jelly shine) --}}
    <div
        class="
        pointer-events-none absolute inset-0 z-[4]
        p-[3px] {{ $rounded }}
        [@background:linear-gradient(
            135deg,
            rgba(255,255,255,0.55),
            rgba(255,255,255,0.25),
            rgba(255,255,255,0.15),
            rgba(255,255,255,0.45)
        )]
        [mask:linear-gradient(#fff_0_0)]
        blur-[2px] opacity-95">
    </div>

    {{-- GEL CORE BLUR (soft & thick) --}}
    <div
        class="
        absolute inset-0 {{ $rounded }}
        backdrop-blur-[30px]
        bg-white/12 dark:bg-white/6
        shadow-[inset_0_0_25px_rgba(255,255,255,0.25)]
        z-[0]">
    </div>

    {{-- CONTENT --}}
    <div class="relative z-[10] {{ $padding }}">
        <div class="flex justify-between">
            <div class="flex flex-col space-y-6">
                <flux:heading variant="bold" size="xl">
                    {{ $heading }}
                </flux:heading>

                <div class="mt-2 flex items-end gap-1">
                    <flux:heading variant="bold" size="3xl" class="font-semibold leading-none">
                        {{ $mainCounter }}
                    </flux:heading>
                    @isset($subCounter)
                        <flux:text color="{{ $subCounterColor }}" class="leading-none">
                            {{ $subCounter }}
                        </flux:text>
                    @endisset
                </div>
            </div>

            @isset($mainIcon)
                <div class="w-10 h-10 rounded-lg p-3 bg-primary-400/50 flex items-center justify-center">
                    {{ $mainIcon }}
                </div>
            @endisset
        </div>

        <!-- Icon lingkaran abu-abu di pojok kanan bawah -->
        @isset($subIcon)
            <div class="absolute bottom-[-15px] right-[-5px] opacity-40">
                {{ $subIcon }}
            </div>
        @endisset
    </div>
</div>
