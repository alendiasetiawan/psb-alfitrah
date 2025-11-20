<div>
   <x-navigations.breadcrumb>
      <x-slot:title>{{ __('Daftar Ulang') }}</x-slot:title>
      <x-slot:activePage>{{ __('Informasi Teknis Daftar Ulang') }}</x-slot:activePage>
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
               <x-slot:title>Mohon maaf, halaman Daftar Ulang hanya bisa diakses oleh siswa yang dinyatakan LULUS tes masuk.</x-slot:title>
            </x-notifications.basic-alert>
         </div>
      @else
         <div class="grid grid-cols-1 mt-4">
            <div class="col-span-1">
               <x-cards.basic-card>
                  <flux:heading size="xl" class="mb-2">Instruksi Daftar Ulang</flux:heading>
                  <flux:text variant="strong" class="mb-2">
                     Kepada ananda <strong>{{ $studentName }}</strong> selamat atas kelulusannya, selanjutnya silahkan melakukan pembayaran biaya Daftar Ulang dengan rincian sebagai berikut :
                  </flux:text>
                  <flux:heading>Total Biaya</flux:heading>
                  <flux:text class="mb-2">Rp 5.500.000</flux:text>

                  <div class="flex justify-start items-center gap-2">
                     <flux:heading>Pembayaran Lunas</flux:heading>
                     <flux:badge color="green">- Rp 500.000</flux:badge>
                  </div>
                  <flux:text class="mb-2">Rp 5.000.000</flux:text>

                  <flux:heading>Pembayaran Termin</flux:heading>
                  <flux:text>Termin 1 : Rp 2.500.000</flux:text>
                  <flux:text>Termin 2 : Rp 1.500.000</flux:text>
                  <flux:text class="mb-2">Termin 3 : Rp 1.000.000</flux:text>

                  <div class="flex justify-start">
                     <flux:button icon="message-circle-more" variant="primary" wire:click='chatAdminFinalRegistration'>
                        Daftar Ulang Sekarang
                     </flux:button>
                  </div>
               </x-cards.basic-card>
            </div>
         </div>
      @endif
   @endif
</div>
