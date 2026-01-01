<div>
    <div class="h-8"></div>
    <!--ANCHOR: DATABASE-->
    <x-animations.fade-down showTiming="50" class="grid grid-cols-1 mt-1">
        <flux:heading size="lg">Data Induk</flux:heading>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="50" class="grid grid-cols-4 gap-6 mt-2">
        <a href="{{ route('admin.master_data.registrant_database') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.users class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Pendaftar</flux:text>
        </a>

        <a wire:navigate href="{{ route('admin.master_data.student_database.index') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.file-user class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Siswa</flux:text>
        </a>
    </x-animations.fade-down>
    <!--#DATABASE-->

    <!--ANCHOR: ADMISSION VERIFICATION-->
    <x-animations.fade-down showTiming="150" class="grid grid-cols-1 mt-4">
        <flux:heading size="lg">Verifikasi Data</flux:heading>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="150" class="grid grid-cols-4 gap-6 mt-2">
        <a href="{{ route('admin.data_verification.registration_payment.payment_unpaid') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.banknotes class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Belum Pembayaran</flux:text>
        </a>

        <a href="{{ route('admin.data_verification.registration_payment.payment_paid') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.shield-check class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Sudah Pembayaran</flux:text>
        </a>

        <a href="{{ route('admin.data_verification.biodata.pending') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.user-search class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Belum Biodata</flux:text>
        </a>

        <a href="{{ route('admin.data_verification.biodata.process') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.contact-round class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Cek Biodata</flux:text>
        </a>

        <a href="{{ route('admin.data_verification.biodata.verified') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.user-check class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Biodata Valid</flux:text>
        </a>

        <a href="{{ route('admin.data_verification.student_attachment.pending') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.file-search-corner class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Belum Berkas</flux:text>
        </a>

        <a href="{{ route('admin.data_verification.student_attachment.process') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.file-text class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Cek Berkas</flux:text>
        </a>

        <a href="{{ route('admin.data_verification.student_attachment.verified') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.file-check class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Berkas Valid</flux:text>
        </a>
    </x-animations.fade-down>
    <!--#ADMISSION VERIFICATION-->
    

    <!--ANCHOR: TEST AND ANNOUNCEMENT-->
    <x-animations.fade-down showTiming="250" class="grid grid-cols-1 mt-4">
        <flux:heading size="lg">Tes dan Kelulusan</flux:heading>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="250" class="grid grid-cols-4 gap-6 mt-2">
        <a href="{{ route('admin.placement_test.absence_test.tapping') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.qr-code class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Tapping QR Tes</flux:text>
        </a>

        <a wire:navigate href="{{ route('admin.placement_test.absence_test.report') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.list-check class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Kehadiran Tes</flux:text>
        </a>

        <a wire:navigate href="{{ route('admin.placement_test.test_result') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.square-sigma class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Nilai Tes</flux:text>
        </a>
    </x-animations.fade-down>
    <!--#TEST AND ANNOUNCEMENT-->

    <!--ANCHOR: SETTINGS-->
    <x-animations.fade-down showTiming="350" class="grid grid-cols-1 mt-4">
        <flux:heading size="lg">Pengaturan</flux:heading>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="350" class="grid grid-cols-4 gap-6 mt-2">
        <a href="{{ route('admin.setting.admission_draft.academic_year') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.graduation-cap class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Tahun Akademik</flux:text>
        </a>

        <a wire:navigate href="{{ route('admin.setting.admission_draft.student_quota') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.user-check class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Kuota Penerimaan</flux:text>
        </a>

        <a wire:navigate href="{{ route('admin.setting.admission_draft.registration_fee') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.banknote-arrow-down class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Biaya Pendaftaran</flux:text>
        </a>

        <a wire:navigate href="{{ route('admin.setting.school.branch') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.school class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Cabang Pondok</flux:text>
        </a>

        <a wire:navigate href="{{ route('admin.setting.school.program') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.book-marked class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Program Pendidikan</flux:text>
        </a>
    </x-animations.fade-down>
    <!--#SETTINGS-->
</div>
