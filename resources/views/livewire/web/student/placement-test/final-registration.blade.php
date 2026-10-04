<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Daftar Ulang') }}</x-slot:title>
    <x-slot:activePage>{{ __('Daftar Ulang Sekarang') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    @if (is_null($studentQuery->placementTestResult))
    <div class="grid-cols-1 mt-4">
        <x-notifications.basic-alert>
            <x-slot:title>Mohon maaf, anda belum bisa mengakses halaman ini</x-slot:title>
        </x-notifications.basic-alert>
    </div>
    @else
        @if ($studentQuery->placementTestResult->final_result != 'Lulus')
        <div class="grid-cols-1 mt-4">
            <x-notifications.basic-alert variant="warning" icon="triangle-alert">
                <x-slot:title>Mohon maaf, halaman Daftar Ulang hanya bisa diakses oleh siswa yang dinyatakan LULUS tes
                    masuk.</x-slot:title>
            </x-notifications.basic-alert>
        </div>
        @else
        <x-animations.fade-down showTiming="50">
            <div class="grid grid-cols-1 mt-4">
                <div class="col-span-1">
                    <x-cards.soft-glass-card>
                        <flux:heading size="xl" class="mb-2">Daftar Ulang</flux:heading>
                        <flux:text variant="soft" class="mb-4">
                            Kepada ananda <strong>{{ $studentName }}</strong> selamat atas kelulusannya, selanjutnya silahkan
                            melanjutkan proses daftar ulang.
                        </flux:text>

                        <div class="flex justify-start">
                            <flux:button icon="message-circle-more" variant="primary" wire:click='chatAdminFinalRegistration'>
                                Daftar Ulang Sekarang
                            </flux:button>
                        </div>
                    </x-cards.soft-glass-card>
                </div>
            </div>
        </x-animations.fade-down>
        @endif
    @endif
</div>
