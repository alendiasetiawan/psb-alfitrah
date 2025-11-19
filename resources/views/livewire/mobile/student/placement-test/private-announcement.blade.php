<div>
   @if (!$isCanSeeResult)
      <div class="grid-cols-1 mt-4">
         <x-notifications.basic-alert>
            <x-slot:title>Mohon maaf, halaman ini hanya bisa diakses setelah "Pembayaran Pendaftaran, Biodata dan Berkas" santri dinyatakan "Valid"</x-slot:title>
         </x-notifications.basic-alert>
      </div>
   @elseif (is_null($testResultQuery->placementTestResult))
      <div class="grid-cols-1 mt-4">
         <x-notifications.basic-alert variant="warning" icon="triangle-alert">
            <x-slot:title>Hasil tes bisa dilihat setelah santri mengikuti tes dan diumumkan oleh panitia</x-slot:title>
         </x-notifications.basic-alert>
      </div>
   @else
      <div class="grid grid-cols-1 mt-4">
         <div class="col-span-1">
            <x-cards.basic-card>
               <flux:heading size="xl" class="mb-3">Informasi Hasil Tes</flux:heading>
               <flux:text variant="strong">
                  Panitia PSB Al Fitrah Islamic School <strong>Tahun Ajaran {{ $testResultQuery->academic_year }}</strong> menetapkan bahwa data di bawah ini
               </flux:text>

               <div class="grid md:grid-cols-2 mt-2">
                  <div class="col-span-1">
                     <flux:text variant="strong" size="lg">Nama Santri</flux:text>
                     <flux:text variant="ghost">{{ $testResultQuery->student_name }}</flux:text>
                  </div>

                  <div class="col-span-1">
                     <flux:text variant="strong" size="lg">Jenis Kelamin</flux:text>
                     <flux:text variant="ghost">{{ $testResultQuery->gender }}</flux:text>
                  </div>
               </div>

               <div class="grid md:grid-cols-2 mt-2">
                  <div class="col-span-1">
                     <flux:text variant="strong" size="lg">Cabang</flux:text>
                     <flux:text variant="ghost">{{ $testResultQuery->branch_name }}</flux:text>
                  </div>

                  <div class="col-span-1">
                     <flux:text variant="strong" size="lg">Program</flux:text>
                     <flux:text variant="ghost">{{ $testResultQuery->program_name }}</flux:text>
                  </div>
               </div>

               <flux:text variant="strong" class="mt-3">telah mengikuti rangkaian proses tes seleksi masuk dengan hasil sebagai berikut:</flux:text>

               <div class="flex-1">
                  <flux:modal.trigger name="detail-announcement-modal">
                     <flux:button icon="eye" variant="primary" class="mt-3">
                        Lihat Hasil
                     </flux:button>
                  </flux:modal.trigger>
               </div>   
            </x-cards.basic-card>

            <!--Modal Detail Announcement-->
            <flux:modal name="detail-announcement-modal" class="md:w-100" :closable="false" variant="flyout" position="bottom">
               <div class="flex flex-col justify-center items-center">
                  @if ($testResultQuery->placementTestResult->final_result == 'Lulus')
                     <flux:icon.check-badge class="size-24 text-green-600 mb-2"/>
                  @else
                     <flux:icon.octagon-x class="size-24 text-red-600 mb-2"/>
                  @endif
                  
                  <flux:text variant="strong">
                     Hasil tes ananda <strong>{{ $testResultQuery->student_name }}</strong> adalah :
                  </flux:text>
                  <flux:heading size="xxl">{{ $testResultQuery->placementTestResult->final_result ?? '-' }}</flux:heading>
                  <flux:heading size="xxl">{{ $testResultQuery->placementTestResult->final_score ?? '-' }}</flux:heading>

                  @if ($testResultQuery->placementTestResult->final_result == 'Lulus')
                     <flux:text>Baarakallahu fiikum</flux:text>
                  @else
                     <flux:text>Jazakumullahu khoiron</flux:text>
                  @endif
               </div>

               <div class="flex justify-center gap-2 mt-2">
                  <a href="{{ route('student.placement_test.test_result.final_registration') }}" wire:navigate>
                     <flux:button variant="primary">
                        Daftar Ulang
                     </flux:button>
                  </a>

                  <flux:modal.close>
                     <flux:button variant="filled">
                           Tutup
                     </flux:button>
                  </flux:modal.close>
               </div>
            </flux:modal>
            <!--#Modal Detail Announcement-->
         </div>
      </div>
   @endif
</div>
