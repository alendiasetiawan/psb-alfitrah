<div>
    <!--Admission Data-->
    <x-animations.fade-down showTiming="50">
        <div class="grid grid-cols-1 mt-4">
            <flux:heading size="lg">Data Siswa</flux:heading>
        </div>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="50" class="grid grid-cols-4 gap-6 mt-3">
        <a href="{{ route('student.payment.registration_payment') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.banknotes class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Biaya Pendaftaran</flux:text>
        </a>

        <a wire:navigate href="{{ route('student.admission_data.biodata') }}" class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.contact-round class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Biodata</flux:text>
        </a>

        <a href="{{ route('student.admission_data.admission_attachment') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.file-text class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Berkas</flux:text>
        </a>

        <a href="{{ route('student.placement_test.qr_presence_test') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.qr-code class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">QR Code</flux:text>
        </a>
    </x-animations.fade-down>
    <!--#Admission Data-->

    <!--Placement Test-->
    <x-animations.fade-down showTiming="150">
        <div class="grid grid-cols-1 mt-4">
            <flux:heading size="lg">Tes & Kelulusan</flux:heading>
        </div>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="150" class="grid grid-cols-4 gap-6 mt-3">
        <a href="{{ route('student.placement_test.test_result.private_announcement') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.megaphone class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Hasil Tes</flux:text>
        </a>

        <a href="{{ route('student.placement_test.test_result.final_registration') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.user-check class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:text size="sm" class="mt-2" variant="bold">Daftar Ulang</flux:text>
        </a>
    </x-animations.fade-down>
    <!--#Placement Test-->
</div>
