<flux:sidebar class="bg-zinc-100 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <!--Brand & Collapse button-->
    <flux:sidebar.header>
        <flux:sidebar.brand
            href="#"
            logo="https://fluxui.dev/img/demo/logo.png"
            logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
            name="{{ config('app.name') }}"
        />
    </flux:sidebar.header>
    <!--#Brand & Collapse button-->

    <!--Menu List-->
    <flux:sidebar.nav>
        <flux:sidebar.item
            icon="home"
            href="{{ route('owner.dashboard') }}"
            :current="Route::is('owner.dashboard')"
            wire:navigate>
            {{ __('Dashboard') }}
        </flux:sidebar.item>

        <!--Menu Manajemen-->
        <flux:navlist.group :heading="__('Manajemen')" class="grid">
            <flux:sidebar.group
            expandable
            :expanded="Route::is('owner.management.owner_account') || Route::is('owner.management.reseller_account')"
            icon="users"
            heading="{{ __('Akun') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('owner.management.owner_account') }}"
                :current="Route::is('owner.management.owner_account')"
                wire:navigate>
                    {{ __('Owner') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.management.reseller_account') }}"
                :current="Route::is('owner.management.reseller_account')"
                wire:navigate>
                    {{ __('Reseller') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            <flux:sidebar.item
                :current="Route::is('owner.management.store_profile')"
                icon="store"
                href="{{ route('owner.management.store_profile') }}"
                wire:navigate>
            {{ __('Toko') }}
            </flux:sidebar.item>
        </flux:navlist.group>
        <!--#Menu Manajemen-->

        <!--Menu Gudang-->
        <flux:navlist.group :heading="__('Gudang')" class="grid">
            <flux:sidebar.group
            expandable
            :expanded="Route::is('owner.warehouse.product.*')"
            icon="package-search"
            heading="{{ __('Produk') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.category') }}"
                :current="Route::is('owner.warehouse.product.category')"
                wire:navigate>
                    {{ __('Kategori') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Daftar Produk') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            <flux:sidebar.item icon="blocks" href="#">{{ __('Stok Barang') }}</flux:sidebar.item>
        </flux:navlist.group>
        <!--#Menu Gudang-->

        <!--Menu Transaksi-->
        <flux:navlist.group :heading="__('Transaksi')" class="grid">
            <flux:sidebar.item icon="shopping-bag" href="#">{{ __('Penjualan') }}</flux:sidebar.item>
            <flux:sidebar.item icon="hand-coins" href="#">{{ __('Piutang') }}</flux:sidebar.item>
        </flux:navlist.group>
        <!--#Menu Transaksi-->
    </flux:sidebar.nav>
    <!--#Menu List-->

    <flux:sidebar.spacer />

    <!--Quick Action-->
    {{-- <flux:sidebar.nav>
        <flux:sidebar.item icon="cog-6-tooth" href="#">Settings</flux:sidebar.item>
        <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
    </flux:sidebar.nav> --}}
    <!--#Quick Action-->

    <!--Extend Menu-->
    <flux:dropdown position="top" align="start">
        <flux:sidebar.profile
            initials="{{ session('userData')->initials() }}"
            name="{{ session('userData')->fullname }}" />
            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                >
                                    {{ session('userData')->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ session('userData')->fullname }}</span>
                                <span class="truncate text-xs">{{ session('userData')->username }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Pengaturan') }}</flux:menu.item>
                </flux:menu.radio.group>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
    </flux:dropdown>
    <!--#Extend Menu-->
</flux:sidebar>
