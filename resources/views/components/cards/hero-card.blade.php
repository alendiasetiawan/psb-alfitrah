@props([
    'rounded' => 'rounded-lg',
    'backgroundImageAsset' => 'images/background/class.webp',
    'avatarImageAsset' => '',
    'isAvatar' => true,
])

<div class="relative overflow-hidden {{ $rounded }}">
    {{-- MASKED GRADIENT BORDER (Tailwind v4) --}}
    <div class="
        pointer-events-none absolute inset-0
        p-[2px] {{ $rounded }}
        bg-gradient-to-br from-white/20 via-white/10 to-white/5
        shadow-[inset_0_2px_2px_rgba(255,255,255,0.7)]
        z-[1]
        ">
    </div>

    {{-- LIQUID GEL BORDER (soft jelly shine) --}}
    <div class="
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
        blur-[2px] opacity-95
    "></div>

    {{-- GEL CORE BLUR (soft & thick) --}}
    <div class="
        absolute inset-0 {{ $rounded }}
        backdrop-blur-[30px]
        bg-white/12 dark:bg-white/6
        shadow-[inset_0_0_25px_rgba(255,255,255,0.25)]
        z-[0]
    "></div>

    <!-- Hero Background -->
    <div class="relative h-64 w-full">
        <img src="{{ asset($backgroundImageAsset) }}"
            class="w-full h-full object-cover" />
    </div>

    <!-- Content -->
    <div class="relative p-8 flex items-center justify-between">
        <!-- Left section -->
        <div class="flex items-center gap-6">
            <!-- Avatar -->
            <div class="absolute -top-15">
                <div class="w-40 h-40 rounded-2xl overflow-hidden shadow-xl border-4 border-white">
                    @if ($isAvatar)
                        <img src="{{ asset($avatarImageAsset) }}" class="w-full h-full object-cover" />
                    @else
                        <div class="flex items-center justify-center bg-white">
                            <flux:icon.contact-round class="size-38 text-dark"/>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ml-44">
                <flux:heading size="3xl" class="font-semibold truncate max-w-[600px]">
                    {{ $title }}
                </flux:heading>

                <div class="flex items-center gap-6 text-white/75 mt-2">
                    <div class="flex items-center gap-2">
                        {{-- <flux:icon.school variant="mini"/> --}}
                        {{ $firstSubTitle }}
                    </div>

                    @isset($secondSubTitle)
                        <div class="flex items-center gap-2">
                            {{ $secondSubTitle }}
                        </div>
                    @endisset

                    @isset($thirdSubTitle)
                        <div class="flex items-center gap-2">
                            {{ $thirdSubTitle }}
                        </div>
                    @endisset

                    @isset($fourthSubTitle)
                        <div class="flex items-center gap-2">
                            {{ $fourthSubTitle }}
                        </div>
                    @endisset
                </div>
            </div>
        </div>

        <!-- Right section - Connected button -->
        @isset($actionButton)
            {{ $actionButton }}
        @endisset
    </div>
</div>