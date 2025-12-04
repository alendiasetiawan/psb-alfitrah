@props([
'isLink' => false,
'src' => null,
'rounded' => 'rounded-xl',
'bgImage' => null
])

<div {{ $attributes->merge([
    'class' => "relative overflow-hidden $rounded",
    ]) }}>

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
   
        <!-- Header background -->
        <div class="relative flex flex-col justify-between">
            <img src="{{ !empty($src) ? $src : asset('images/background/alfitrah-school.jpeg') }}" alt="background"
                class="w-full h-16 object-cover">
            <!-- Avatar -->
            <div class="absolute left-6 -bottom-12">
                <img class="w-22 h-22 rounded-full border-4 border-white object-cover"
                    src="{{ !empty($src) ? $src : asset('images/avatar/default-avatar.png') }}" alt="avatar">
            </div>
            @isset($socialLink)
            <!-- Social icons -->
            <div class="absolute right-6 -bottom-6 flex space-x-3">
                <a href="#" class="p-2 rounded-full bg-purple-50 text-blue-400 hover:bg-blue-100">
                    <!-- Twitter -->

                </a>
                <a href="#" class="p-2 rounded-full bg-purple-50 text-pink-500 hover:bg-pink-100">
                    <!-- Instagram -->

                </a>
                <a href="#" class="p-2 rounded-full bg-purple-50 text-blue-600 hover:bg-blue-100">
                    <!-- Facebook -->

                </a>
            </div>
            @endisset
        </div>

        <!--Content-->
        <div class="px-4 pt-12 relative">
            <div class="flex justify-between items-center">
                <!-- Avatar + Nama -->
                <div class="flex items-center space-x-4">
                    <div>
                        <flux:heading size="lg" variant="bold">{{ $fullName }}</flux:heading>
                        <flux:text variant="soft">{{ $position }}</flux:text>
                    </div>
                </div>

                @isset($counter)
                <div class="text-center">
                    <p class="text-lg font-bold text-gray-700">{{ $counter }}</p>
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                </div>
                @endisset
            </div>

            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="py-2 text-center">
            @isset($actionButton)
            <!-- Action buttons -->
            <div class="flex justify-center space-x-6 mt-6">
                {{ $actionButton }}
                {{-- <button
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200">
                    👍
                </button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200">
                    ⋯
                </button> --}}
            </div>
            @endisset
        </div>
</div>