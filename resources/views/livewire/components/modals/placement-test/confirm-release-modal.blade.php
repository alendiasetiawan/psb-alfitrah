<div>
    <flux:modal name="confirm-release-modal" variant="{{ $isMobile ? 'flyout' : '' }}" position="{{ $isMobile ? 'bottom' : '' }}">
        <flux:heading size="xxl">Konfirmasi Release Hasil Tes</flux:heading>
        <form wire:submit="releaseTestResult">
            <div class="space-y-4 mt-5">
                <!--SECTION: Alert error process release-->
                @if (session('error-release'))
                    <div class="grid grid-cols-1">
                        <x-notifications.basic-alert isCloseable="true">
                            <x-slot:title>{{ session('error-release') }}</x-slot:title>
                        </x-notifications.basic-alert>
                    </div>
                @endif
                <!--#Alert error process release-->

                <!--SECTION: Text info release-->   
                <div class="grid grid-cols-1 gap-3">
                    <flux:text>
                        Apakah anda yakin ingin release hasil tes?
                        <br />
                        Sistem akan release hasil tes dan mengirimkan notifikasi whatsapp kepada santri dengan status <strong class="text-amber-400">Hold</strong> dan hasil tes <strong class="text-amber-400"> != Menunggu</strong>
                        <br />
                        <br />
                        Jumlah total santri yang akan release : <strong class="text-amber-400">{{ $holdStudents?->count() }}</strong>
                        <br />
                        @if ($totalHoldStudents < 1)
                            <x-notifications.basic-alert>
                                <x-slot:title>Tidak ada santri yang bisa diproses</x-slot:title>
                            </x-notifications.basic-alert>
                        @else
                            <x-notifications.basic-alert variant="warning" icon="triangle-alert">
                                <x-slot:title>Untuk menghindari blokir Whatsapp, notifikasi akan dikirim sekitar 30 - 60 menit setelah release hasil tes dengan jeda 10 detik antar pesan</x-slot:title>
                            </x-notifications.basic-alert>
                        @endif
                    </flux:text>
                </div>
                <!--#Text info release-->

                <!--SECTION: ACTION BUTTON-->
                <div class="flex items-end gap-2">
                    <flux:button
                        type="submit"
                        variant="primary"
                        :disabled="$totalHoldStudents < 1"
                        :loading="false">
                            <x-items.loading-indicator wireTarget="releaseTestResult">
                                <x-slot:buttonName>Ya, Release</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                    </flux:button>
                    <flux:modal.close>
                        <flux:button variant="filled">Batal</flux:button>
                    </flux:modal.close>
                </div>
                <!--#ACTION BUTTON-->
            </div>
        </form>
    </flux:modal>
</div>
