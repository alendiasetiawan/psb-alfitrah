<div class="mb-17">
    <!--ANCHOR:Welcome Header-->
    <x-animations.fade-down showTiming="50">
        <div class="flex flex-row justify-between items-center">
            <div class="flex items-start gap-2">
                <!--Main Logo-->
                <div class="flex items-center">
                    <img src="{{ asset('images/logo/alfitrah-logo.png') }}" alt="logo" width="40" height="40" />
                </div>
                <!--#Main Logo-->

                <!--Greeting-->
                <div class="flex flex-col items-start">
                    <flux:text variant="soft">Assalamu'alaikum,</flux:text>
                    <flux:heading size="lg" variant="bold">{{ session('userData')->fullname }}</flux:heading>
                </div>
                <!--#Greeting-->
            </div>

            <!--Quick Action-->
            <form method="POST" action="{{ route('logout') }}">
                <div class="flex gap-2">
                    @csrf
                    <button type="submit">
                        <flux:icon.log-out class="size-6 text-primary-400" />
                    </button>
                </div>
            </form>
            <!--#Quick Action-->
        </div>
    </x-animations.fade-down>
    <!--#Welcome Header-->

    <!--ANCHOR: Swiper Card-->
    <x-animations.fade-down showTiming="150">
        <div x-data="swiperContainer({
            effect: 'coverflow',
            loop: true,
            grabCursor: true,
            slidesPerView: 'auto',
            centeredSlides: true,
            spaceBetween: 5,
            coverflowEffect: {
                rotate: 50,
                stretch: 20,
                depth: 300,
                modifier: 1.5,
                slideShadows: true,
            },
        })" x-init="init()" class="mt-4 relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw]">
            <div class="swiper" x-ref="container">
                <div class="swiper-wrapper items-stretch">
                    <!--CARD: Total Registrant-->
                        <x-cards.counter-card class="swiper-slide h-full" style="width: 85vw">
                            <x-slot:heading>Total Pendaftar</x-slot:heading>
                            <x-slot:mainCounter>{{ $counterStatistic->total_registrant }}</x-slot:mainCounter>
                            <x-slot:subCounter>Siswa</x-slot:subCounter>
                            <x-slot:subIcon>
                                <flux:icon.users class="text-primary size-16"/>
                            </x-slot:subIcon>
                        </x-cards.counter-card>
                    <!--#Total Registrant-->

                    <!--CARD: Total Payment-->
                    <x-cards.counter-card class="swiper-slide h-full" style="width: 85vw">
                        <x-slot:heading>Pemasukan Pendaftaran</x-slot:heading>
                        <x-slot:mainCounter>{{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($counterStatistic->total_payment) }}</x-slot:mainCounter>
                        <x-slot:subIcon>
                            <flux:icon.hand-coins class="text-primary size-16"/>
                        </x-slot:subIcon>
                    </x-cards.counter-card>
                    <!--#Total Payment-->

                    <!--CARD: Total Student-->
                        <x-cards.counter-card class="swiper-slide h-full" style="width: 85vw">
                            <x-slot:heading>Total Lulus</x-slot:heading>
                            <x-slot:mainCounter>{{ $counterStatistic->total_student_pass }}</x-slot:mainCounter>
                            <x-slot:subCounter>Siswa</x-slot:subCounter>
                            <x-slot:subIcon>
                                <flux:icon.user-check class="text-primary size-16"/>
                            </x-slot:subIcon>
                        </x-cards.counter-card>
                    <!--#Total Student-->
                </div>
            </div>
        </div>
    </x-animations.fade-down>
    <!--#Swiper Card-->

    <!--ANCHOR:Quick Menu-->
    <x-animations.fade-down showTiming="150" class="grid grid-cols-5 mt-4">
        <!-- Biodata -->
        <a href="{{ route('admin.master_data.registrant_database') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.users class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:heading class="mt-2 text-dark/70">Pendaftar</flux:heading>
        </a>

        <!-- Berkas -->
        <a href="{{ route('admin.data_verification.biodata.pending') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.contact-round class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:heading class="mt-2 text-dark/70">Biodata</flux:heading>
        </a>

        <!-- Berkas -->
        <a href="{{ route('student.admission_data.admission_attachment') }}" wire:navigate class="flex flex-col items-center text-center">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.file-text class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:heading class="mt-2 text-dark/70">Berkas</flux:heading>
        </a>

        <!-- Kelulusan -->
        <a href="{{ route('student.placement_test.test_result.private_announcement') }}" class="flex flex-col items-center text-center" wire:navigate>
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.user-check class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:heading class="mt-2 text-dark/70">Siswa</flux:heading>
        </a>

        <!-- Menu -->
        <a class="flex flex-col items-center text-center" wire:navigate href="{{ route('student.student_mega_menu') }}">
            <x-cards.soft-glass-card rounded="rounded-full" class="w-13 h-13 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.list class="text-primary-300 size-7" />
            </x-cards.soft-glass-card>
            <flux:heading class="mt-2 text-dark/70">Menu</flux:heading>
        </a>
    </x-animations.fade-down>
    <!--#Quick Menu-->

    <!--ANCHOR: CHART-->
    <x-animations.fade-down showTiming="250">
        <div class="grid grid-cols-1 mt-4 gap-4">
            <!---CARD: Bar Chart Total Registrant Per Program-->
            <div class="col-span-1">
                <x-cards.soft-glass-card>
                    <flux:heading size="xl">Jumlah Pendaftar Per Program</flux:heading>
                    <div style="height: 200px;">
                        <livewire:livewire-column-chart key="{{ $this->registrantPerProgram->reactiveKey() }}" :column-chart-model="$this->registrantPerProgram"/>
                    </div>
                </x-cards.soft-glass-card>
            </div>
            <!--#Bar Chart Total Registrant Per Program-->

            <!--CARD: Pie Chart Percentage Payment Success-->
            <div class="col-span-1">
                <x-cards.soft-glass-card class="h-full">
                    <flux:heading size="xl">Persentase Pembayaran</flux:heading>
                    <div style="height: 275px;">
                        <livewire:livewire-radial-chart key="{{ $this->percentageTotalPaymentSuccess->reactiveKey() }}" :radial-chart-model="$this->percentageTotalPaymentSuccess"/>
                    </div>
                    <div class="flex justify-center items-center gap-1 px-2">
                        <div class="flex items-center gap-1">
                            <flux:icon.banknotes variant="mini" class="text-primary"/>
                            <flux:text>Pembayaran : {{ $this->countPaymentSuccess->total_payment_success }} dari {{ $this->countPaymentSuccess->total_registrant }} Siswa </flux:text>
                        </div>  
                    </div>
                </x-cards.soft-glass-card>
            </div>
            <!--#Pie Chart Percentage Payment Success-->
        </div>
    </x-animations.fade-down>
    <!--#CHART-->
</div>