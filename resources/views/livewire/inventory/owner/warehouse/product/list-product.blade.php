<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Produk') }}</x-slot:title>
        <x-slot:activePage>{{ __('Manajemen Database Produk') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--Action and Filter--->
    <div class="grid grid-cols-2 mt-4">
        <div class="col-span-1">
            <div class="flex gap-2">
                <flux:input placeholder="Cari Produk" wire:model.live.debounce.500ms="searchProduct"/>
                <div>
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down">Toko</flux:button>

                        <flux:menu>
                            <flux:menu.item icon="plus">New post</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
                <div>
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down">Kategori</flux:button>

                        <flux:menu>
                            <flux:menu.item icon="plus">New post</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>
        <div class="col-span-1">
            <div class="flex justify-end">
                <flux:button variant="primary" icon="plus" href="{{ route('owner.warehouse.product.add_product') }}" wire:navigate>{{ __('Tambah') }}</flux:button>
            </div>
        </div>
    </div>
    <!--#Action and Filter--->

    <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4 gap-4">
        @forelse ($this->productLists() as $product)
            <div class="col-span-1">
                <x-cards.product-card>
                    <x-slot:badge>Restroke</x-slot:badge>
                    <x-slot:category>Audio</x-slot:category>
                    <x-slot:productTitle>SoundMax Pro X7 Wireless</x-slot:productTitle>
                    <x-slot:productDescription>
                        Experience premium sound quality with advanced active noise cancellation. Enjoy up to 36 hours of battery life and ultra-comfortable memory foam ear cushions
                    </x-slot:productDescription>
                    <x-slot:price>Rp 1.500.000</x-slot:price>
                </x-cards.product-card>
            </div>
        @empty
            <div class="lg:col-span-3 md:col-span-2">
                <x-notifications.not-found message="Tidak ada produk yang bisa ditampilkan"/>
            </div>
        @endforelse
    </div>
</div>
