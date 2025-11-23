<div class="mb-17">
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
                <flux:text variant="soft">Assalamu'alaikum,</flux:text>
                <flux:heading size="lg" variant="bold">{{ $studentQuery->student_name }}</flux:heading>
            </div>
            <!--#Greeting-->
        </div>

        <!--Quick Action-->
        <form method="POST" action="{{ route('logout') }}">
            <div class="flex gap-2">
                <a href="{{ route('student.admission_data.biodata') }}" wire:navigate>
                    <flux:icon.circle-user-round class="size-6 text-primary-400" />
                </a>
                @csrf
                <button type="submit">
                    <flux:icon.log-out class="size-6 text-primary-400" />
                </button>
            </div>
        </form>
        <!--#Quick Action-->
    </div>
    <!--#Welcome Header-->

    <!--Swiper Card-->
    <x-animations.fade-down showTiming="50">
        <div x-data="swiperContainer({
            effect: 'coverflow',
            loop: true,
            grabCursor: false,
            centeredSlides: false,
            slidesPerView: 'auto',
            spaceBetween: 5,
            coverflowEffect: {
                rotate: 50,
                stretch: 20,
                depth: 50,
                modifier: 1,
                slideShadows: false,
            },
        })" x-init="init()">
            <div class="swiper w-screen" x-ref="container">
                <div class="swiper-wrapper">
                    <!--CARD 1-->
                    <x-cards.user-card class="swiper-slide shadow-md" style="width: 85vw">
                        <x-slot:fullName>{{ $studentQuery->branch_name }}</x-slot:fullName>
                        <x-slot:position>
                            {{ $studentQuery->program_name }}
                        </x-slot:position>
                        <div class="flex justify-between mt-2 gap-2">
                            <flux:badge color="primary" variant="solid">{{ $studentQuery->reg_number }}</flux:badge>
                            <flux:badge color="primary" variant="solid">{{ $studentQuery->academic_year }}</flux:badge>
                        </div>
                    </x-cards.user-card>

                    <!--CARD 2-->
                    <x-cards.soft-glass-card class="swiper-slide" style="width: 85vw">
                        <flux:heading size="xl">Status Validasi</flux:heading>
                        <!--Registration Payment-->
                        <x-lists.list-group>
                            <x-slot:title>
                                <div class="flex items-center gap-1">
                                    Biaya Pendaftaran
                                    <flux:icon.external-link variant="micro" class="text-primary" />
                                </div>
                            </x-slot:title>
                            <x-slot:subTitle>{{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($studentQuery->registration_fee) }}</x-slot:subTitle>
                            <x-slot:buttonAction>
                                @if ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::VALID)
                                    <flux:badge color="green" variant="solid" size="sm">Valid</flux:badge>
                                @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::INVALID)
                                    <flux:badge color="red" variant="solid" size="sm">Error</flux:badge>
                                @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::PROCESS)
                                    <flux:badge color="orange" variant="solid" size="sm">Proses</flux:badge>
                                @else
                                    <flux:badge color="sky" variant="solid" size="sm">Menunggu</flux:badge>
                                @endif
                            </x-slot:buttonAction>
                        </x-lists.list-group>
                        <!--#Registration Payment-->

                        <!--Biodata-->
                        <x-lists.list-group>
                            <x-slot:title>
                                <div class="flex items-center gap-1">
                                    Biodata
                                    <a href="{{ route('student.admission_data.biodata') }}" wire:navigate>
                                        <flux:icon.external-link variant="micro" class="text-primary" />
                                    </a>
                                </div>
                            </x-slot:title>
                            <x-slot:subTitle>
                                @if ($studentQuery->biodata != \App\Enums\VerificationStatusEnum::NOT_STARTED)
                                    Sudah Diisi
                                @else
                                    Belum Diisi
                                @endif
                            </x-slot:subTitle>
                            <x-slot:buttonAction>
                                @if ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::VALID)
                                    <flux:badge color="green" variant="solid" size="sm">Valid</flux:badge>
                                @elseif ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::INVALID)
                                    <flux:badge color="red" variant="solid" size="sm">Error</flux:badge>
                                @elseif ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::PROCESS)
                                    <flux:badge color="orange" variant="solid" size="sm">Proses</flux:badge>
                                @else
                                    <flux:badge color="sky" variant="solid" size="sm">Menunggu</flux:badge>
                                @endif
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
                            <x-slot:subTitle>
                                @if ($studentQuery->attachment != \App\Enums\VerificationStatusEnum::NOT_STARTED)
                                    Sudah Diisi
                                @else
                                    Belum Diisi
                                @endif
                            </x-slot:subTitle>
                            <x-slot:buttonAction>
                                @if ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::VALID)
                                    <flux:badge color="green" variant="solid" size="sm">Valid</flux:badge>
                                @elseif ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::INVALID)
                                    <flux:badge color="red" variant="solid" size="sm">Error</flux:badge>
                                @elseif ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::PROCESS)
                                    <flux:badge color="orange" variant="solid" size="sm">Proses</flux:badge>
                                @else
                                    <flux:badge color="sky" variant="solid" size="sm">Menunggu</flux:badge>
                                @endif
                            </x-slot:buttonAction>
                        </x-lists.list-group>
                        <!--#Attachment-->
                    </x-cards.soft-glass-card>

                    <!--Card 3-->
                    <x-cards.soft-glass-card class="swiper-slide shadow-md" style="width: 85vw">
                        <flux:heading size="xl" class="mb-2" variant="bold">Hasil Tes</flux:heading>

                        <div class="flex-grow">
                            <x-lists.list-group class="mb-3">
                                <x-slot:title>Kehadiran Tes</x-slot:title>
                                <x-slot:subTitle>
                                    @if (is_null($studentQuery->placementTestPresence))
                                        Belum Tes
                                    @else
                                        Sudah Tes
                                    @endif
                                </x-slot:subTitle>
                                <x-slot:buttonAction>
                                    <a href="{{ route('student.placement_test.qr_presence_test') }}" wire:navigate>
                                        <flux:icon.qr-code class="text-primary" />
                                    </a>
                                </x-slot:buttonAction>
                            </x-lists.list-group>

                            <div class="flex justify-between mb-3">
                                <div class="flex flex-col items-center">
                                    <flux:heading size="lg">Nilai Akhir</flux:heading>
                                    <flux:text variant="soft">
                                        {{ $studentQuery->placementTestResult->final_score ?? '-' }}
                                    </flux:text>
                                </div>

                                <div class="flex flex-col items-center">
                                    <flux:heading size="lg">Hasil Akhir</flux:heading>
                                    <flux:text variant="soft">
                                        {{ $studentQuery->placementTestResult->final_result ?? '-' }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 w-full">
                            <flux:button variant="primary" 
                                icon="user-check"
                                href="{{ route('student.placement_test.test_result.final_registration') }}"
                                wire:navigate>
                                Daftar Ulang
                            </flux:button>
                        </div>
                    </x-cards.soft-glass-card>
                </div>
            </div>
        </div>
    </x-animations.fade-down>
    <!--#Swiper Card-->

    <!--Quick Menu-->
    <x-animations.fade-down showTiming="150" class="grid grid-cols-4 mt-3">
        <!-- Biodata -->
        <a href="{{ route('student.admission_data.biodata') }}" wire:navigate class="flex flex-col items-center">
            <div class="w-13 h-13 rounded-full bg-primary-100 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.contact-round class="text-primary-500 size-7" />
            </div>
            <flux:heading class="mt-2 text-dark/70">Biodata</flux:heading>
        </a>

        <!-- Berkas -->
        <a href="{{ route('student.admission_data.admission_attachment') }}" wire:navigate class="flex flex-col items-center">
            <div class="w-13 h-13 rounded-full bg-primary-100 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.file-text class="text-primary-500 size-7" />
            </div>
            <flux:heading class="mt-2 text-dark/70">Berkas</flux:heading>
        </a>

        <!-- Kelulusan -->
        <a href="{{ route('student.placement_test.test_result.private_announcement') }}" class="flex flex-col items-center" wire:navigate>
            <div class="w-13 h-13 rounded-full bg-primary-100 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.user-check class="text-primary-500 size-7" />
            </div>
            <flux:heading class="mt-2 text-dark/70">Kelulusan</flux:heading>
        </a>

        <!-- Menu -->
        <a class="flex flex-col items-center" wire:navigate href="{{ route('student.student_mega_menu') }}">
            <div class="w-13 h-13 rounded-full bg-primary-100 flex items-center justify-center shadow-xl">
                <!-- Icon di sini -->
                <flux:icon.list class="text-primary-500 size-7" />
            </div>
            <flux:heading class="mt-2 text-dark/70">Menu</flux:heading>
        </a>
    </x-animations.fade-down>
    <!--#Quick Menu-->

    <!--Timeline Student Admission-->
    <div class="grid grid-cols-1 mt-3 mb-5 gap-3 mb-6">
        <x-animations.fade-down showTiming="250">
            <x-cards.soft-glass-card>
                <flux:heading size="xl" class="mb-3" variant="bold">Alur Penerimaan Siswa Baru</flux:heading>
                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute top-0 bottom-0 left-5 w-px bg-gray-200"></div>

                    <div class="space-y-8">
                        <!--#1 Account Registration-->
                        <div class="relative flex gap-6">
                            <!-- Node Number -->
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-500 font-bold rounded-full border border-primary-300 z-10">
                                1
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <flux:heading size="lg" class="text-dark/75">Registrasi Akun</flux:heading>
                                    <div class="flex gap-1 items-center">
                                        <!--Valid Status-->
                                        <flux:badge variant="solid" size="sm" color="green" icon="check-circle">Selesai
                                        </flux:badge>
                                        <!--#Valid Status-->

                                        {{--
                                        <!--Process Status-->
                                        <flux:badge variant="solid" size="sm" color="orange" icon="check-circle">Selesai
                                        </flux:badge>
                                        <!--#Process Status--> --}}

                                        {{--
                                        <!--Invalid Status-->
                                        <flux:icon.x-circle variant="micro" class="text-red-600" />
                                        <flux:text variant="strong" class="text-red-600">Tidak Valid</flux:text>
                                        <!--#Invalid Status--> --}}
                                    </div>
                                </div>

                                <flux:text class="mt-1" variant="soft">
                                    Alhamdulillah, pendaftaran akun berhasil dan anda resmi menjadi calon siswa
                                </flux:text>
                            </div>
                        </div>
                        <!--#Account Registration-->

                        <!--#2 Registration Payment-->
                        <div class="relative flex gap-6">
                            <!-- Node Number -->
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-500 font-bold rounded-full border border-primary-300 z-10">
                                2
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <flux:heading size="lg">Bayar Pendaftaran</flux:heading>
                                    <div class="flex gap-1 items-center">
                                        {{--
                                        <!--Valid Status-->
                                        <flux:icon.check-circle variant="micro" class="text-green-600" />
                                        <flux:text variant="strong" class="text-green-600">Selesai</flux:text>
                                        <!--#Valid Status--> --}}

                                        <!--Process Status-->
                                        <flux:badge variant="solid" size="sm" color="orange" icon="refresh-cw">Proses
                                        </flux:badge>
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
            </x-cards.soft-glass-card>
        </x-animations.fade-down>
    </div>
    <!--#Timeline Student Admission-->
</div>