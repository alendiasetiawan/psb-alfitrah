@props([
    'src' => null,
    'clickable' => false,
    'clickFunction' => null
])

<div wire:click="{{ $clickFunction }}">
    <div 
    {{ $attributes->merge([
        'class'  => "w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 transform " . ($clickable ? "hover:shadow-xl hover:-translate-y-1 cursor-pointer" : '')
    ]) }}>
        <!-- Product Image Section -->
        <div class="relative h-35 overflow-hidden bg-gray-100">
            <img src="{{ $src ? $src : asset('images/background/bg-product.webp') }}" alt="Wireless Headphones" class="w-full h-full object-cover transition-transform duration-700 ease-in-out transform hover:scale-110">

            @isset($badge)
                <flux:badge variant="solid" color="secondary" class="top-2 left-2 absolute">
                    {{ $badge }}
                </flux:badge>
            @endisset
        </div>

        <!-- Product Details Section -->
        <div class="p-5">
            <!-- Category -->
            @isset($category)
            <flux:badge color="primary" size="sm" class="mb-1">
                {{ $category }}
            </flux:badge>
            @endisset


            <!-- Title -->
            <flux:heading size="xl">
                {{ $productTitle }}
            </flux:heading>

            <!-- Description with truncation -->
            <div class="mb-2">
                <flux:text variant="subtle" size="sm">
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
