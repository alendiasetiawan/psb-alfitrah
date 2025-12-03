<flux:sidebar class="bg-white/10 shadow-[inset_-2px_0px_4px_rgba(255,255,255,0.4)] backdrop-blur-sm dark:bg-zinc-900 dark:border-zinc-700">
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
            href="{{ route('admin.dashboard') }}"
            :current="Route::is('admin.dashboard')"
            wire:navigate>
            {{ __('Dashboard') }}
        </flux:sidebar.item>

        <!--Menu Data Induk-->
        <flux:navlist.group :heading="__('Data Induk')" class="grid">
            <!--Menu Database-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('owner.management.owner_account') || Route::is('owner.management.reseller_account')"
            icon="database"
            heading="{{ __('Database') }}"
            class="grid">
                <flux:sidebar.item
                href="#"
                :current="Route::is('owner.management.owner_account')"
                wire:navigate>
                    {{ __('Pendaftar') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="#"
                :current="Route::is('owner.management.reseller_account')"
                wire:navigate>
                    {{ __('Santri') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Database-->

            <!--Demografi Santri-->
            <flux:sidebar.item
                :current="Route::is('owner.management.store_profile')"
                icon="map-pinned"
                href="#"
                wire:navigate>
            {{ __('Demografi Pendaftar') }}
            </flux:sidebar.item>
            <!--#Demografi Santri-->

            <!--Kuota Santri-->
            <flux:sidebar.item
                :current="Route::is('owner.management.store_profile')"
                icon="list-checks"
                href="#"
                wire:navigate>
            {{ __('Kuota Santri') }}
            </flux:sidebar.item>
            <!--#Kuota Santri-->
        </flux:navlist.group>
        <!--#Menu Data Induk-->

        <!--Menu Verifikasi Data-->
        <flux:navlist.group :heading="__('Verifikasi Data')" class="grid">
            <!--Menu Biaya Pendaftaran-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('owner.warehouse.product.*')"
            icon="banknotes"
            heading="{{ __('Biaya Pendaftaran') }}"
            class="grid">
                <flux:sidebar.item
                href="#"
                :current="Route::is('owner.warehouse.product.category')"
                wire:navigate>
                    {{ __('Belum') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="#"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Proses') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="#"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Selesai') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Biaya Pendaftaran-->

            <!--Menu Biodata-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('owner.warehouse.product.*')"
            icon="contact"
            heading="{{ __('Biodata') }}"
            class="grid">
                <flux:sidebar.item
                href="#"
                :current="Route::is('owner.warehouse.product.category')"
                wire:navigate>
                    {{ __('Belum') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Proses') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Selesai') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Biodata-->

            <!--Menu Berkas-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('owner.warehouse.product.*')"
            icon="file-text"
            heading="{{ __('Berkas') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.category') }}"
                :current="Route::is('owner.warehouse.product.category')"
                wire:navigate>
                    {{ __('Belum') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Proses') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Selesai') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Berkas-->
        </flux:navlist.group>
        <!--#Menu Verifikasi Data-->

        <!--Menu Tes & Kelulusan-->
        <flux:navlist.group :heading="__('Tes & Kelulusan')" class="grid">
            <flux:sidebar.item icon="user-check" href="#">{{ __('Absensi Tes') }}</flux:sidebar.item>

            <!--Menu Hasil Tes-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('owner.warehouse.product.*')"
            icon="file-check"
            heading="{{ __('Hasil Tes') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.category') }}"
                :current="Route::is('owner.warehouse.product.category')"
                wire:navigate>
                    {{ __('Psikotes') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Bacaan Al Quran') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Wawancara') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('owner.warehouse.product.list_product') }}"
                :current="Route::is('owner.warehouse.product.list_product') || Route::is('owner.warehouse.product.add_product')"
                wire:navigate>
                    {{ __('Semua Tes') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Hasil Tes-->
        </flux:navlist.group>
        <!--#Menu Tes & Kelulusan-->

        <!--Menu Pengaturan-->
        <flux:navlist.group :heading="__('Pengaturan')" class="grid">
            {{-- <flux:sidebar.item icon="square-sigma" href="#">{{ __('Formula Nilai Tes') }}</flux:sidebar.item> --}}

            <!--Menu Draft PSB-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('admin.setting.admission_draft.*')"
            icon="settings"
            heading="{{ __('Draft PSB') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('admin.setting.admission_draft.academic_year') }}"
                :current="Route::is('admin.setting.admission_draft.academic_year')"
                wire:navigate>
                    {{ __('Tahun Akademik') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.setting.admission_draft.student_quota') }}"
                :current="Route::is('admin.setting.admission_draft.student_quota')"
                wire:navigate>
                    {{ __('Kuota Penerimaan') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.setting.admission_draft.registration_fee') }}"
                :current="Route::is('admin.setting.admission_draft.registration_fee')"
                wire:navigate>
                    {{ __('Biaya Pendaftaran') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Draft PSB-->

            <!--Menu Pondok-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('admin.setting.school.*')"
            icon="school"
            heading="{{ __('Pondok') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('admin.setting.school.branch') }}"
                :current="Route::is('admin.setting.school.branch')"
                wire:navigate>
                    {{ __('Cabang') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.setting.school.program') }}"
                :current="Route::is('admin.setting.school.program')"
                wire:navigate>
                    {{ __('Program Pendidikan') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Pondok-->
        </flux:navlist.group>
        <!--#Menu Pengaturan-->
    </flux:sidebar.nav>
    <!--#Menu List-->

    <flux:sidebar.spacer />

    <!--Extend Menu-->
    <flux:dropdown position="top" align="start">
        <flux:sidebar.profile
            initials="{{ session('userData')->initials() }}"
            name="{{ session('userData')->fullname }}" />
            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 p-2 text-start">
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:text variant="bold">{{ session('userData')->fullname }}</flux:text>
                                <flux:text variant="soft" size="sm">{{ session('userData')->username }}</flux:text>
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
