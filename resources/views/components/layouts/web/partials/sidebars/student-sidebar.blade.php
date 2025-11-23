<flux:sidebar class="bg-white/20 shadow-[inset_0_2px_2px_rgba(255,255,255,0.7)] backdrop-blur-sm dark:bg-zinc-900 border-r border-white/30 dark:border-zinc-700">
    <!--Brand & Collapse button-->
    <flux:sidebar.header>
        <flux:sidebar.brand href="{{ route('student.student_dashboard') }}" wire:navigate logo="{{ asset('images/logo/alfitrah-logo.png') }}"
            logo:dark="{{ asset('images/logo/alfitrah-logo.png') }}" name="{{ config('app.name') }}" />
    </flux:sidebar.header>
    <!--#Brand & Collapse button-->

    <!--Menu List-->
    <flux:sidebar.nav>
        <flux:sidebar.item icon="home" href="{{ route('student.student_dashboard') }}"
            :current="Route::is('student.student_dashboard')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:sidebar.item>

        <!--Menu Data Siswa-->
        <flux:navlist.group :heading="__('Data Siswa')" class="grid">
            <!--Bayar Biaya Pendaftaran-->
            <flux:sidebar.item :current="Route::is('student.payment.registration_payment')" icon="banknotes"
                href="{{ route('student.payment.registration_payment') }}" wire:navigate>
                {{ __('Biaya Pendaftaran') }}
            </flux:sidebar.item>
            <!--#Bayar Biaya Pendaftaran-->

            <!--Biodata-->
            <flux:sidebar.item :current="Route::is('student.admission_data.biodata')" icon="contact-round"
                href="{{ route('student.admission_data.biodata') }}" wire:navigate>
                {{ __('Biodata') }}
            </flux:sidebar.item>
            <!--#Biodata-->

            <!--Berkas-->
            <flux:sidebar.item :current="Route::is('student.admission_data.admission_attachment')" icon="file-badge"
                href="{{ route('student.admission_data.admission_attachment') }}" wire:navigate>
                {{ __('Berkas') }}
            </flux:sidebar.item>
            <!--#Berkas-->
        </flux:navlist.group>
        <!--#Menu Data Siswa-->

        <!--Menu Tes dan Pengumuman-->
        <flux:navlist.group :heading="__('Tes dan Kelulusan')" class="grid">
            <!--Placement Test Presence-->
            <flux:sidebar.item :current="Route::is('student.placement_test.qr_presence_test')" icon="qr-code"
                href="{{ route('student.placement_test.qr_presence_test') }}" wire:navigate>
                {{ __('QR Code Tes') }}
            </flux:sidebar.item>
            <!--#Placement Test Presence-->

            <!--Test Announcement-->
            <flux:sidebar.group expandable :expanded="Route::is('student.placement_test.test_result.*')"
                icon="megaphone" heading="{{ __('Kelulusan') }}" class="grid">
                <flux:sidebar.item href="{{ route('student.placement_test.test_result.private_announcement') }}"
                    :current="Route::is('student.placement_test.test_result.private_announcement')" wire:navigate>
                    {{ __('Pengumuman') }}
                </flux:sidebar.item>

                <flux:sidebar.item href="{{ route('student.placement_test.test_result.final_registration') }}"
                    :current="Route::is('student.placement_test.test_result.final_registration')" wire:navigate>
                    {{ __('Daftar Ulang') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Test Announcement-->
        </flux:navlist.group>
        <!--#Menu Tes dan Pengumuman-->
    </flux:sidebar.nav>
    <!--#Menu List-->

    <flux:sidebar.spacer />

    <!--Extend Menu-->
    <flux:dropdown position="top" align="start">
        <flux:sidebar.profile initials="{{ session('userData')->initials() }}"
            name="{{ session('userData')->fullname }}" />
        <flux:menu class="w-[220px]">
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
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
                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Pengaturan') }}
                </flux:menu.item>
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