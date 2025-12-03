@props([
    'borderColor' => '',
    'subTextVariant' => 'subtle',
    'subTextColor' => ''
])

<div>
    <div class="relative bg-gradient-to-br from-white/20 via-white/10 to-white/5
   shadow-[inset_4px_0px_5px_var(--color-primary)] rounded-2xl px-4 py-3">
       <div class="
        pointer-events-none absolute inset-0 z-[4]
        p-[3px] rounded-2xl
        [@background:linear-gradient(
            135deg,
            rgba(255,255,255,0.55),
            rgba(255,255,255,0.25),
            rgba(255,255,255,0.15),
            rgba(255,255,255,0.45)
        )]
        [mask:linear-gradient(#fff_0_0)]
        blur-[2px] opacity-95
    "></div>

    <div class="
        absolute inset-0 rounded-2xl
        backdrop-blur-[30px]
        bg-white/12 dark:bg-white/6
        shadow-[inset_4px_0px_5px_var(--color-primary)]
        z-[0]
    "></div>

        <div class="relative z-[10]">

            <div class="flex justify-between">
                <div>
                    <flux:heading size="xl" variant="bold">{{ $title }}</flux:heading>
                    @isset($subTitle)
                        {{ $subTitle }}
                    @endisset
                </div>

                @isset($buttonAction)
                <div class="flex justify-center">
                    {{ $buttonAction }}
                </div>
                @endisset
            </div>

            <!-- Progress bar -->
            {{ $slot }}
        
            @isset($actionInformation)
                <div class="flex items-center justify-between">
                    <span class="text-{{ $borderColor }}-600 font-semibold text-lg">
                        {{ $textInfo }}
                    </span>
                    {{ $action }}
                </div>
            @endisset
        </div>

    </div>
</div>
