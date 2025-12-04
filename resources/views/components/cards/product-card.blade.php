@props([
    'src' => null,
    'clickable' => false,
    'clickFunction' => null,
    'rounded' => 'rounded-xl',

])

<div wire:click="{{ $clickFunction }}">
    <div 
        {{ $attributes->merge([
            'class'  => "relative overflow-hidden rounded-xl" . ($clickable ? "hover:shadow-xl hover:-translate-y-1 cursor-pointer" : '')
        ]) }}>

        {{-- MASKED GRADIENT BORDER (Tailwind v4) --}}
        <div class="
            pointer-events-none absolute inset-0
            p-[2px] {{ $rounded }}
            bg-gradient-to-br from-white/20 via-white/10 to-white/5
            shadow-[inset_2px_3px_5px_rgba(255,255,255,0.7)]
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
        blur-[2px] opacity-95"></div>

        {{-- GEL CORE BLUR (soft & thick) --}}
        <div class="
        absolute inset-0 {{ $rounded }}
        backdrop-blur-md
        bg-white/12 dark:bg-white/6
        shadow-[inset_0_0_25px_rgba(255,255,255,0.25)]
        z-[0]"></div>
       
            <!-- Product Image Section -->
            <div class="relative h-35 overflow-hidden ">
                <img src="{{ $src ? $src : asset('images/background/bg-product.webp') }}" alt="Wireless Headphones" class="w-full h-full object-cover transition-transform duration-700 ease-in-out transform hover:scale-110">

                @isset($badge)
                    <flux:badge variant="solid" color="secondary" class="top-2 left-2 absolute">
                        {{ $badge }}
                    </flux:badge>
                @endisset
            </div>

            <!-- Product Details Section -->
            <div class="p-5 relative">
                <!-- Category -->
                @isset($category)
                <flux:badge color="primary" size="sm" class="mb-1" variant="solid">
                    {{ $category }}
                </flux:badge>
                @endisset

                <!-- Title -->
                <flux:heading size="xl" variant="bold">
                    {{ $productTitle }}
                </flux:heading>

                <!-- Description with truncation -->
                <div class="mb-2">
                    <flux:text variant="soft" size="sm">
                        {{ $productDescription }}
                    </flux:text>
                </div>

                {{ $slot }}

                @isset($price)
                    <!-- Price and CTA -->
                    <div class="mt-3 flex flex-wrap lg:flex-nowrap items-center justify-between gap-4">
                        <div class="price-container">
                            <div class="flex items-center">
                                <flux:heading size="xl" variant="strong" level="1">
                                    {{ $price }}
                                </flux:heading>
                            </div>
                        </div>
                        @isset($buttonAction)
                            {{ $buttonAction }}
                            {{-- <flux:button.group>
                                <flux:button variant="primary" size="sm">Edit</flux:button>
                                <flux:button variant="filled" size="sm">Hapus</flux:button>
                            </flux:button.group> --}}
                        @endisset

                    </div>
                @endisset
            </div>
    </div>
</div>
