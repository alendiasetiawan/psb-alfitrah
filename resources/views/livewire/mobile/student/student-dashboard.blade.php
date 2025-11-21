<div class="mb-12">
   <!--Welcome Header-->
   <div class="flex flex-row justify-between items-center mb-2">
      <div class="flex items-start gap-2">
         <!--Main Logo-->
         <div class="flex items-center">
            <img src="{{ asset('images/logo/alfitrah-logo.png') }}" alt="logo" width="40" height="40" />
         </div>
         <!--#Main Logo-->

         <!--Greeting-->
         <div class="flex flex-col items-start">
            <flux:text variant="subtle">Assalamu'alaikum,</flux:text>
            <flux:heading size="lg">Alendia Desta</flux:heading>
         </div>
         <!--#Greeting-->
      </div>

      <!--Quick Action-->
      <div class="flex gap-2">
         <flux:icon.circle-user-round class="size-6 text-gray-500" />
         <flux:icon.log-out class="size-6 text-gray-500" />
      </div>
      <!--#Quick Action-->
   </div>
   <!--#Welcome Header-->

   <!--Swiper Card-->
   <x-animations.fade-down showTiming="50">
      <div 
         x-data="swiperContainer({
            effect: 'coverflow',
            loop: true,
            grabCursor: true,
            centeredSlides: false,
            slidesPerView: 'auto',
            spaceBetween: 5,
            coverflowEffect: {
               rotate: 50,
               stretch: 20,
               depth: 50,
               modifier: 1,
               slideShadows: true,
            },
         })"
         x-init="init()">
         <div class="swiper w-screen" x-ref="container">
            <div class="swiper-wrapper">
               <!--CARD 1-->
                  <x-cards.user-card class="swiper-slide" style="width: 85vw">
                     <x-slot:fullName>Al Fitrah 2 Klapanunggal</x-slot:fullName>
                     <x-slot:position>
                        SMA Tahfidz
                     </x-slot:position>
                     <div class="flex justify-between mt-2 gap-2">
                        <flux:badge color="primary">2526-1107-01</flux:badge>
                        <flux:badge color="primary">2027-2028</flux:badge>
                     </div>
                  </x-cards.user-card>

               <!--CARD 2-->
                  <x-cards.basic-card class="swiper-slide" style="width: 85vw">
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

               <!--Card 3-->
                  <x-cards.basic-card class="swiper-slide" style="width: 85vw">
                     <flux:heading size="xl" class="mb-2">Hasil Tes</flux:heading>

                     <div class="flex-grow">
                        <x-lists.list-group class="mb-3">
                           <x-slot:title>Kehadiran Tes</x-slot:title>
                           <x-slot:subTitle>Belum</x-slot:subTitle>
                           <x-slot:buttonAction>
                              <flux:icon.qr-code class="text-primary" />
                           </x-slot:buttonAction>
                        </x-lists.list-group>

                        <div class="flex justify-between mb-3">
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
         </div>
      </div>
   </x-animations.fade-down>
   <!--#Swiper Card-->

   <!--Quick Menu-->
   <x-animations.fade-down showTiming="150" class="grid grid-cols-4 mt-3">
      <!-- Biodata -->
      <button class="flex flex-col items-center">
         <div class="w-15 h-15 rounded-full bg-primary-400 flex items-center justify-center shadow-xl">
            <!-- Icon di sini -->
            <flux:icon.contact-round class="text-white size-8" />
         </div>
         <flux:heading class="mt-2">Biodata</flux:heading>
      </button>

      <!-- Berkas -->
      <button class="flex flex-col items-center">
         <div class="w-15 h-15 rounded-full bg-primary-400 flex items-center justify-center shadow-xl">
            <!-- Icon di sini -->
            <flux:icon.file-text class="text-white size-8" />
         </div>
         <flux:heading class="mt-2">Berkas</flux:heading>
      </button>

      <!-- Kelulusan -->
      <button class="flex flex-col items-center">
         <div class="w-15 h-15 rounded-full bg-primary-400 flex items-center justify-center shadow-xl">
            <!-- Icon di sini -->
            <flux:icon.user-check class="text-white size-8" />
         </div>
         <flux:heading class="mt-2">Kelulusan</flux:heading>
      </button>

      <!-- Menu -->
      <button class="flex flex-col items-center">
         <div class="w-15 h-15 rounded-full bg-primary-400 flex items-center justify-center shadow-xl">
            <!-- Icon di sini -->
            <flux:icon.list class="text-white size-8" />
         </div>
         <flux:heading class="mt-2">Menu</flux:heading>
      </button>
   </x-animations.fade-down>
   <!--#Quick Menu-->

   <!--Timeline Student Admission-->
   <div class="grid grid-cols-1 mt-3">
      <x-animations.fade-down showTiming="250">
         <x-cards.basic-card class="shadow-lg">
            <flux:heading size="xl" class="mb-3">Alur Penerimaan Siswa Baru</flux:heading>
            <div class="relative">
               <!-- Vertical Line -->
               <div class="absolute top-0 bottom-0 left-5 w-px bg-gray-200"></div>

               <div class="space-y-8">
                  <!--#1 Account Registration-->
                  <div class="relative flex gap-6">
                     <!-- Node Number -->
                     <div
                        class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-600 font-bold rounded-full border border-primary-300 z-10">
                        1
                     </div>

                     <!-- Content -->
                     <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                           <flux:heading size="xl">Registrasi Akun</flux:heading>
                           <div class="flex gap-1 items-center">
                              <!--Valid Status-->
                              <flux:icon.check-circle variant="micro" class="text-green-600" />
                              <flux:text variant="strong" class="text-green-600">Selesai</flux:text>
                              <!--#Valid Status-->

                              {{--
                              <!--Process Status-->
                              <flux:icon.refresh-cw variant="micro" class="text-amber-600" />
                              <flux:text variant="strong" class="text-amber-600">Proses</flux:text>
                              <!--#Process Status--> --}}

                              {{--
                              <!--Invalid Status-->
                              <flux:icon.x-circle variant="micro" class="text-red-600" />
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
                     <div
                        class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-600 font-bold rounded-full border border-primary-300 z-10">
                        2
                     </div>

                     <!-- Content -->
                     <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                           <flux:heading size="xl">Bayar Pendaftaran</flux:heading>
                           <div class="flex gap-1 items-center">
                              {{--
                              <!--Valid Status-->
                              <flux:icon.check-circle variant="micro" class="text-green-600" />
                              <flux:text variant="strong" class="text-green-600">Selesai</flux:text>
                              <!--#Valid Status--> --}}

                              <!--Process Status-->
                              <flux:icon.refresh-cw variant="micro" class="text-amber-600" />
                              <flux:text variant="strong" class="text-amber-600">Proses</flux:text>
                              <!--#Process Status-->

                              {{--
                              <!--Invalid Status-->
                              <flux:icon.x-circle variant="micro" class="text-red-600" />
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
      </x-animations.fade-down>
   </div>
   <!--#Timeline Student Admission-->
</div>