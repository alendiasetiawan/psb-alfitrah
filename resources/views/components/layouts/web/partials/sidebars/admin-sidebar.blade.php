<flux:sidebar class="bg-white/10 shadow-[inset_-2px_0px_4px_rgba(255,255,255,0.4)] backdrop-blur-sm dark:bg-zinc-900 dark:border-zinc-700">
    <!--Brand & Collapse button-->
    <flux:sidebar.header>
        <flux:sidebar.brand
            href="#"
            logo="{{ asset('images/logo/alfitrah-logo.png') }}"
            logo:dark="{{ asset('images/logo/alfitrah-logo.png') }}"
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
            :expanded="Route::is('admin.master_data.registrant_database') || Route::is('admin.master_data.student_database.*')"
            icon="database"
            heading="{{ __('Database') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('admin.master_data.registrant_database') }}"
                :current="Route::is('admin.master_data.registrant_database')"
                wire:navigate>
                    {{ __('Pendaftar') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.master_data.student_database.index') }}"
                :current="Route::is('admin.master_data.student_database.*')"
                wire:navigate>
                    {{ __('Siswa') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Database-->

            <!--Demografi Santri-->
            {{-- <flux:sidebar.item
                :current="Route::is('admin.master_data.registrant_demographic')"
                icon="map-pinned"
                href="{{ route('admin.master_data.registrant_demographic') }}"
                wire:navigate>
            {{ __('Demografi Pendaftar') }}
            </flux:sidebar.item> --}}
            <!--#Demografi Santri-->

            <!--Kuota Santri-->
            <flux:sidebar.item
                :current="Route::is('admin.master_data.monitoring_quota')"
                icon="list-checks"
                href="{{ route('admin.master_data.monitoring_quota') }}"
                wire:navigate>
            {{ __('Kuota Santri') }}
            </flux:sidebar.item>
            <!--#Kuota Santri-->
        </flux:navlist.group>
        <!--#Menu Data Induk-->

        <!--ANCHOR - Menu Verifikasi Data-->
        <flux:navlist.group :heading="__('Verifikasi Data')" class="grid">
            <!--Menu Biaya Pendaftaran-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('admin.data_verification.registration_payment.*')"
            icon="banknotes"
            heading="{{ __('Biaya Pendaftaran') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('admin.data_verification.registration_payment.payment_unpaid') }}"
                :current="Route::is('admin.data_verification.registration_payment.payment_unpaid')"
                wire:navigate>
                    {{ __('Belum') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.data_verification.registration_payment.payment_process') }}"
                :current="Route::is('admin.data_verification.registration_payment.payment_process')"
                wire:navigate>
                    {{ __('Proses') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.data_verification.registration_payment.payment_paid') }}"
                :current="Route::is('admin.data_verification.registration_payment.payment_paid')"
                wire:navigate>
                    {{ __('Selesai') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Biaya Pendaftaran-->

            <!--Menu Biodata-->
            <flux:sidebar.group
                expandable
                :expanded="Route::is('admin.data_verification.biodata.*')"
                icon="contact"
                heading="{{ __('Biodata') }}"
                class="grid">
                    <flux:sidebar.item
                    href="{{ route('admin.data_verification.biodata.pending') }}"
                    :current="Route::is('admin.data_verification.biodata.pending')"
                    wire:navigate>
                        {{ __('Belum') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                    href="{{ route('admin.data_verification.biodata.process') }}"
                    :current="Route::is('admin.data_verification.biodata.process') || Route::is('admin.data_verification.biodata.process.*')"
                    wire:navigate>
                        {{ __('Proses') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                    href="{{ route('admin.data_verification.biodata.verified') }}"
                    :current="Route::is('admin.data_verification.biodata.verified') || Route::is('admin.data_verification.biodata.verified.*')"
                    wire:navigate>
                        {{ __('Selesai') }}
                    </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Biodata-->

            <!--Menu Berkas-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('admin.data_verification.student_attachment.*')"
            icon="file-text"
            heading="{{ __('Berkas') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('admin.data_verification.student_attachment.pending') }}"
                :current="Route::is('admin.data_verification.student_attachment.pending')"
                wire:navigate>
                    {{ __('Belum') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.data_verification.student_attachment.process') }}"
                :current="Route::is('admin.data_verification.student_attachment.process') || Route::is('admin.data_verification.student_attachment.process.*')"
                wire:navigate>
                    {{ __('Proses') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.data_verification.student_attachment.verified') }}"
                :current="Route::is('admin.data_verification.student_attachment.verified') || Route::is('admin.data_verification.student_attachment.verified.*')"
                wire:navigate>
                    {{ __('Selesai') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            <!--#Menu Berkas-->
        </flux:navlist.group>
        <!--#Menu Verifikasi Data-->

        <!--Menu Tes & Kelulusan-->
        <flux:navlist.group :heading="__('Tes & Kelulusan')" class="grid">

            <!--Menu Absensi Tes-->
            <flux:sidebar.group
            expandable
            :expanded="Route::is('admin.placement_test.absence_test.*')"
            icon="fingerprint-pattern"
            heading="{{ __('Absensi Tes') }}"
            class="grid">
                <flux:sidebar.item
                href="{{ route('admin.placement_test.absence_test.tapping') }}"
                :current="Route::is('admin.placement_test.absence_test.tapping')"
                wire:navigate>
                    {{ __('Tapping QR') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                href="{{ route('admin.placement_test.absence_test.report') }}"
                :current="Route::is('admin.placement_test.absence_test.report')"
                wire:navigate>
                    {{ __('Rekap Kehadiran') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            <!--Hasil Tes-->
            <flux:sidebar.item
                :current="Route::is('admin.placement_test.test_result.*') || Route::is('admin.placement_test.test_result')"
                icon="file-check"
                href="{{ route('admin.placement_test.test_result') }}"
                wire:navigate>
            {{ __('Hasil Tes') }}
            </flux:sidebar.item>
            <!--#Hasil Tes-->
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
