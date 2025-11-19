<div>
   @if ($isCanCreateQr)       
      <div class="flex justify-center mt-4"
      x-data="{
         ...countDownTimer({
            countdown: 60,
         }),
         isShowQrCode: false,
      }"
      x-on:show-qr-code.window="
      isShowQrCode = true,
      resetTimer()
      " 
      x-on:hide-qr-code.window="
      isShowQrCode = false,
      " 
      >
         <x-cards.basic-card class="md:w-4/6 lg:w-3/6">
            @if ($isAttended)
               <!--Attandance alert-->
               <div class="flex flex-col items-center">
                  <flux:icon.check-check class="size-25" color="green"/>
               </div>
               <flux:text variant="strong">
                  Terima kasih atas kehadiran ananda <strong>{{ $presenceTestQuery->student_name }}</strong>
                  dalam pelaksanaan Tes Seleksi Masuk Al Fitrah Islamic School
                  <br/> <br/>
                  Cabang : <strong>{{ $presenceTestQuery->branch_name }}</strong><br/>
                  Program : <strong>{{ $presenceTestQuery->program_name }}</strong> <br/>
                  Tanggal : 
                     <strong>
                        {{ \App\Helpers\DateFormatHelper::shortIndoDate($presenceTestQuery->placementTestPresence->check_in_time) }}
                     </strong> <br/>
                  Waktu Kehadiran : 
                     <strong>
                        {{ \App\Helpers\DateFormatHelper::shortTime($presenceTestQuery->placementTestPresence->check_in_time) }}
                     </strong> 
                  <br/> <br/>
                  Kami akan segera menghubungi anda melalui pesan Whatsapp apabila hasil tes telah diumumkan
               </flux:text>
               <!--#Attandance alert-->
            @else
            <!--Generate and Show QR Code-->
               <div class="flex flex-col items-center space-y-3">
                  <flux:heading size="xl">{{ __('QR Code Kehadiran Tes Masuk') }}</flux:heading>

                  <template x-if="isShowQrCode && countdown > 0">
                     @if (!is_null($presenceTestQuery->testQrCode))
                        {{ QrCode::size(250)->generate($presenceTestQuery->testQrCode?->qr) }}
                     @endif
                  </template>

                  <template x-if="!isShowQrCode || countdown === 0">
                     <flux:icon.scan-line class="size-50"/>
                  </template>

                  <template x-if="isShowQrCode && countdown > 0">
                     <!--Timer Expired-->
                     <div class="flex justify-between">
                        <flux:text variant="strong">
                              <flux:icon.alarm-clock variant="mini"/>
                              <div class="ml-1">
                                 Kode QR Berlaku Selama : 
                                 <span x-text="countdown"></span>
                              </div>
                        </flux:text>
                     </div>
                     <!--#Timer Expired-->
                  </template>
                  
                  <template x-if="!isShowQrCode || countdown === 0">
                     <flux:text variant="ghost" class="text-center">
                        Buat QR Code dan tunjukan kepada petugas untuk absensi kehadiran tes
                     </flux:text>
                  </template>

                  <!--Button Create-->
                  <template x-if="!isShowQrCode || countdown === 0">
                     <flux:button variant="primary" wire:click="generateQrCode">
                        Buat QR Code
                     </flux:button>
                  </template>
                  <!--#Button Create-->
               </div>
            <!--#Generate and Show QR Code-->
            @endif
         </x-cards.basic-card>
      </div>
   @else
   <!--Alert When Student Can't Create QR Code-->
   <div class="grid grid-cols-1 mt-4">
      <x-notifications.basic-alert>
         <x-slot:title>Mohon maaf, halaman ini hanya bisa diakses setelah "Biodata dan Berkas" santri dinyatakan "Valid"</x-slot:title>
         <x-slot:subTitle>
               Lengkapi biodata dan berkas <flux:link href="{{ route('student.admission_data.biodata') }}" wire:navigate class="text-blue-500">disini</flux:link> 
         </x-slot:subTitle>
      </x-notifications.basic-alert>
   </div>
   <!--#Alert When Student Can't Create QR Code-->
   @endif
</div>
