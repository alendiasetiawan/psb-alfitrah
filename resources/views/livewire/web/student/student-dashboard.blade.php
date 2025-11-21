<div>
   <!--Timeline Student Admission-->
   <div class="grid grid-cols-1 mt-4">
      <x-cards.basic-card>
         <flux:heading size="xl" class="mb-3">Alur Penerimaan Siswa Baru</flux:heading>
         <div class="relative">
            <!-- Vertical Line -->
            <div class="absolute top-0 bottom-0 left-5 w-px bg-gray-200"></div>

            <div class="space-y-8">
               <!--#1 Account Registration-->
               <div class="relative flex gap-6">
                  <!-- Node Number -->
                  <div class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-600 font-bold rounded-full border border-primary-300 z-10">
                     1
                  </div>

                  <!-- Content -->
                  <div class="flex-1">
                     <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <flux:heading size="xl">Registrasi Akun</flux:heading>
                        <div class="flex gap-1 items-center">
                           <!--Valid Status-->
                           <flux:icon.check-circle variant="micro" class="text-green-600"/>
                           <flux:text variant="strong" class="text-green-600">Selesai</flux:text>
                           <!--#Valid Status-->

                           {{-- <!--Process Status-->
                           <flux:icon.refresh-cw variant="micro" class="text-amber-600"/>
                           <flux:text variant="strong" class="text-amber-600">Proses</flux:text>
                           <!--#Process Status--> --}}

                           {{-- <!--Invalid Status-->
                           <flux:icon.x-circle variant="micro" class="text-red-600"/>
                           <flux:text variant="strong" class="text-red-600">Tidak Valid</flux:text>
                           <!--#Invalid Status--> --}}
                        </div>
                     </div>
                     
                     <flux:text variant="subtle" class="mt-1">
                        Alhamdulillah, pendaftaran akun berhasil dan anda resmi menjadi calon siswa
                     </flux:text>
                  </div>
               </div>
               <!--#Account Registration-->

               <!--#2 Registration Payment-->
               <div class="relative flex gap-6">
                  <!-- Node Number -->
                  <div class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-600 font-bold rounded-full border border-primary-300 z-10">
                     2
                  </div>

                  <!-- Content -->
                  <div class="flex-1">
                     <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <flux:heading size="xl">Bayar Pendaftaran</flux:heading>
                        <div class="flex gap-1 items-center">
                           {{-- <!--Valid Status-->
                           <flux:icon.check-circle variant="micro" class="text-green-600"/>
                           <flux:text variant="strong" class="text-green-600">Selesai</flux:text>
                           <!--#Valid Status--> --}}

                           <!--Process Status-->
                           <flux:icon.refresh-cw variant="micro" class="text-amber-600"/>
                           <flux:text variant="strong" class="text-amber-600">Proses</flux:text>
                           <!--#Process Status-->

                           {{-- <!--Invalid Status-->
                           <flux:icon.x-circle variant="micro" class="text-red-600"/>
                           <flux:text variant="strong" class="text-red-600">Tidak Valid</flux:text>
                           <!--#Invalid Status--> --}}
                        </div>
                     </div>
                     
                     <flux:text variant="subtle" class="mt-1">
                        Alhamdulillah, pendaftaran akun berhasil dan anda resmi menjadi calon siswa
                     </flux:text>
                  </div>
               </div>
               <!--#Registration Payment-->
            </div>
         </div>
      </x-cards.basic-card>
   </div>
   <!--#Timeline Student Admission-->

   <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4 gap-3 items-stretch">
      <!--Student Account-->
      <div class="col-span-1">
         <x-cards.user-card class="h-full">
            <x-slot:fullName>Alendia Desta Setiawan</x-slot:fullName>
            <x-slot:position>
               Laki-Laki | 083927-0392 | 2027-2028
            </x-slot:position>
            <div class="flex justify-between items-center mt-4">
               <flux:badge color="primary" icon="school">Al Fitrah 1 Jonggol</flux:badge>
               <flux:badge color="primary" icon="briefcase">SMA Tahfidz</flux:badge>
            </div>

         </x-cards.user-card>
      </div>
      <!--#Student Account-->

      <!--Student Biodata and Attachment-->
      <div class="col-span-1">
         <x-cards.basic-card class="h-full space-y-2">
            <flux:heading size="xl">Status Validasi</flux:heading>
            <!--Registration Payment-->
            <x-lists.list-group>
               <x-slot:title>
                  <div class="flex items-center gap-1">
                     Biaya Pendaftaran
                     <flux:icon.external-link variant="micro" class="text-primary" />
                  </div>
               </x-slot:title>
               <x-slot:subTitle>Rp 350.000</x-slot:subTitle>
               <x-slot:buttonAction>
                  <flux:badge color="green">Valid</flux:badge>
               </x-slot:buttonAction>
            </x-lists.list-group>
            <!--#Registration Payment-->

            <!--Biodata-->
            <x-lists.list-group>
               <x-slot:title>
                  <div class="flex items-center gap-1">
                     Biodata
                     <flux:icon.external-link variant="micro" class="text-primary" />
                  </div>
               </x-slot:title>
               <x-slot:subTitle>Belum Diisi</x-slot:subTitle>
               <x-slot:buttonAction>
                  <flux:badge color="green">Valid</flux:badge>
               </x-slot:buttonAction>
            </x-lists.list-group>
            <!--#Biodata-->

            <!--Attachment-->
            <x-lists.list-group>
               <x-slot:title>
                  <div class="flex items-center gap-1">
                     Berkas
                     <flux:icon.external-link variant="micro" class="text-primary" />
                  </div>
               </x-slot:title>
               <x-slot:subTitle>Belum Diisi</x-slot:subTitle>
               <x-slot:buttonAction>
                  <flux:badge color="green">Valid</flux:badge>
               </x-slot:buttonAction>
            </x-lists.list-group>
            <!--#Attachment-->
         </x-cards.basic-card>
      </div>
      <!--#Student Biodata and Attachment-->

      <!--QR Code for Test Presence-->
      <div class="col-span-1">
         <x-cards.basic-card class="h-full">
            <flux:heading size="xl" class="mb-2">Hasil Tes</flux:heading>

            <div class="flex-grow">
               <x-lists.list-group class="mb-4">
                  <x-slot:title>Kehadiran Tes</x-slot:title>
                  <x-slot:subTitle>Belum</x-slot:subTitle>
                  <x-slot:buttonAction>
                     <flux:icon.qr-code class="text-primary" />
                  </x-slot:buttonAction>
               </x-lists.list-group>

               <div class="flex justify-between mb-4">
                  <div class="flex flex-col items-center">
                     <flux:heading size="lg">Nilai Akhir</flux:heading>
                     <flux:text>80</flux:text>
                  </div>

                  <div class="flex flex-col items-center">
                     <flux:heading size="lg">Hasil Akhir</flux:heading>
                     <flux:text>Lulus</flux:text>
                  </div>
               </div>
            </div>

            <div class="grid grid-cols-1 w-full">
               <flux:button variant="primary" icon="user-check">
                  Daftar Ulang
               </flux:button>
            </div>
         </x-cards.basic-card>
      </div>
      <!--#QR Code for Test Presence-->
   </div>
</div>