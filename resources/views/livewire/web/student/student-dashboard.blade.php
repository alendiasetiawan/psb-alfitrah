<div>
    <!--Timeline Student Admission-->
    <div class="grid grid-cols-1 mt-4">
        <x-animations.fade-down showTiming="50">
            <x-cards.soft-glass-card>
                <flux:heading size="xl" class="mb-3" variant="bold">Alur Penerimaan Siswa Baru</flux:heading>
                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute top-0 bottom-0 left-5 w-px bg-gray-200"></div>

                    <div class="space-y-6">
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
                                    <flux:heading size="lg" variant="bold">Registrasi Akun</flux:heading>
                                    <div class="flex gap-1 items-center">
                                        <!--Valid Status-->
                                        <flux:badge variant="solid" size="sm" color="green" icon="check-circle">Selesai
                                        </flux:badge>
                                        <!--#Valid Status-->
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
                                    <flux:heading size="lg" variant="bold">Bayar Pendaftaran</flux:heading>
                                    <div class="flex gap-1 items-center">
                                        @if ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::VALID)
                                            <flux:badge color="green" variant="solid" size="sm" icon="check-circle">Valid</flux:badge>
                                        @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::INVALID)
                                            <flux:badge color="red" variant="solid" size="sm" icon="x-circle">Tidak Valid</flux:badge>
                                        @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::PROCESS)
                                            <flux:badge color="orange" variant="solid" size="sm" icon="refresh-cw">Proses</flux:badge>
                                        @else
                                            <flux:badge color="gray" variant="solid" size="sm" icon="circle-minus">Belum</flux:badge>
                                        @endif
                                    </div>
                                </div>

                                <flux:text variant="soft" class="mt-1">
                                    @if ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::VALID)
                                        Terima kasih atas pembayaran anda, jazakumullahu khoiron
                                    @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::INVALID)
                                        Upss.. Terjadi kesalahan dalam pembayaran, silahkan coba lagi
                                        <flux:link href="{{ route('student.payment.registration_payment') }}" wire:navigate>
                                            disini
                                        </flux:link>
                                    @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::PROCESS)
                                        Segera selesaikan pembayaran anda agar bisa melanjutkan pendaftaran
                                    @else
                                        Anda belum melunasi biaya pendaftaran, segera lakukan pembayaran
                                        <flux:link href="{{ route('student.payment.registration_payment') }}" wire:navigate>
                                            disini
                                        </flux:link>
                                    @endif
                                </flux:text>
                            </div>
                        </div>
                        <!--#Registration Payment-->

                        <!--#3 Biodata-->
                        <div class="relative flex gap-6">
                            <!-- Node Number -->
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-500 font-bold rounded-full border border-primary-300 z-10">
                                3
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <flux:heading size="lg" variant="bold">Mengisi Biodata</flux:heading>
                                    <div class="flex gap-1 items-center">
                                        @if ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::VALID)
                                            <flux:badge color="green" variant="solid" size="sm" icon="check-circle">Valid</flux:badge>
                                        @elseif ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::INVALID)
                                            <flux:badge color="red" variant="solid" size="sm" icon="x-circle">Tidak Valid</flux:badge>
                                        @elseif ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::PROCESS)
                                            <flux:badge color="orange" variant="solid" size="sm" icon="refresh-cw">Proses</flux:badge>
                                        @else
                                            <flux:badge color="gray" variant="solid" size="sm" icon="circle-minus">Belum</flux:badge>
                                        @endif
                                    </div>
                                </div>

                                <flux:text variant="soft" class="mt-1">
                                    @if ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::VALID)
                                        Selamat, biodata anda sudah valid
                                    @elseif ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::INVALID)
                                        Terdapat kesalahan dalam pengisian biodata, mohon untuk melakukan perbaikan 
                                        <flux:link wire:navigate href="{{ route('student.admission_data.biodata') }}">
                                            disini
                                        </flux:link>
                                    @elseif ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::PROCESS)
                                        Kami sedang melakukan pengecekan biodata anda, mohon kesediaannya untuk menunggu.
                                    @else
                                        Anda belum melengkapi biodata, silahkan mengisi 
                                        <flux:link href="">
                                            <span>disini</span>
                                        </flux:link>
                                    @endif
                                </flux:text>
                            </div>
                        </div>
                        <!--#Biodata-->

                        <!--#4 Berkas-->
                        <div class="relative flex gap-6">
                            <!-- Node Number -->
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-500 font-bold rounded-full border border-primary-300 z-10">
                                4
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <flux:heading size="lg" variant="bold">Melampirkan Berkas</flux:heading>
                                    <div class="flex gap-1 items-center">
                                        @if ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::VALID)
                                            <flux:badge color="green" variant="solid" size="sm" icon="check-circle">Valid</flux:badge>
                                        @elseif ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::INVALID)
                                            <flux:badge color="red" variant="solid" size="sm" icon="x-circle">Tidak Valid</flux:badge>
                                        @elseif ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::PROCESS)
                                            <flux:badge color="orange" variant="solid" size="sm" icon="refresh-cw">Proses</flux:badge>
                                        @else
                                            <flux:badge color="gray" variant="solid" size="sm" icon="circle-minus">Belum</flux:badge>
                                        @endif
                                    </div>
                                </div>

                                <flux:text variant="soft" class="mt-1">
                                    @if ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::VALID)
                                        Selamat, berkas anda sudah valid
                                    @elseif ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::INVALID)
                                        Terdapat kesalahan pada berkas yang anda lampirkan, mohon untuk melakukan perbaikan 
                                        <flux:link wire:navigate href="{{ route('student.admission_data.admission_attachment') }}">
                                            disini
                                        </flux:link>
                                    @elseif ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::PROCESS)
                                        Kami sedang melakukan pengecekan berkas anda, mohon kesediaannya untuk menunggu.
                                    @else
                                        Anda belum melampirkan berkas, silahkan mengisi 
                                        <flux:link href="">
                                            <span>disini</span>
                                        </flux:link>
                                    @endif
                                </flux:text>
                            </div>
                        </div>
                        <!--#Berkas-->

                        <!--#5 Mengikuti Tes-->
                        <div class="relative flex gap-6">
                            <!-- Node Number -->
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-500 font-bold rounded-full border border-primary-300 z-10">
                                5
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <flux:heading size="lg" variant="bold">Mengikuti Tes Masuk</flux:heading>
                                    <div class="flex gap-1 items-center">
                                        @if (is_null($studentQuery->placementTestPresence))
                                            <flux:badge color="gray" variant="solid" size="sm" icon="circle-minus">Belum Tes</flux:badge>
                                        @else
                                            <flux:badge color="green" variant="solid" size="sm" icon="check-circle">Sudah Tes</flux:badge>
                                        @endif
                                    </div>
                                </div>

                                <flux:text variant="soft" class="mt-1">
                                    @if (is_null($studentQuery->placementTestPresence))
                                        Untuk mengikuti tes, anda harus menunjukan QR Code ke panitia. Cek 
                                        <flux:link wire:navigate href="{{ route('student.placement_test.qr_presence_test') }}">
                                            disini
                                        </flux:link>
                                    @else
                                        Terima kasih atas kehadiran anda, kami akan segera mengumumkan hasilnya
                                    @endif
                                </flux:text>
                            </div>
                        </div>
                        <!--#Mengikuti Tes-->

                        <!--#6 Pengumuman Kelulusan-->
                        <div class="relative flex gap-6">
                            <!-- Node Number -->
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-500 font-bold rounded-full border border-primary-300 z-10">
                                6
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <flux:heading size="lg" variant="bold">Pengumuman Kelulusan </flux:heading>
                                    <div class="flex gap-1 items-center">
                                        @if (is_null($studentQuery->placementTestResult))
                                            <flux:badge color="gray" variant="solid" size="sm" icon="circle-minus">Menunggu</flux:badge>
                                        @else
                                            <flux:badge color="green" variant="solid" size="sm" icon="check-circle">Sudah Diumumkan</flux:badge>
                                        @endif
                                    </div>
                                </div>

                                <flux:text variant="soft" class="mt-1">
                                    @if (is_null($studentQuery->placementTestPresence))
                                        Kelulusan akan diumumkan setelah anda mengikuti tes
                                    @else
                                        @if (is_null($studentQuery->placementTestResult))
                                            Kami sedang melakukan perhitungan hasil tes, mohon kesediaannya untuk menunggu
                                        @else
                                            Anda bisa melihat hasil tes 
                                            <flux:link wire:navigate href="{{ route('student.placement_test.test_result.private_announcement') }}">
                                                disini
                                            </flux:link>
                                        @endif
                                    @endif
                                </flux:text>
                            </div>
                        </div>
                        <!--#Pengumuman Kelulusan-->

                        <!--#7 Daftar Ulang-->
                        <div class="relative flex gap-6">
                            <!-- Node Number -->
                            <div
                                class="w-10 h-10 flex items-center justify-center bg-primary-100 text-primary-500 font-bold rounded-full border border-primary-300 z-10">
                                7
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <flux:heading size="lg" variant="bold">Daftar Ulang </flux:heading>
                                    <div class="flex gap-1 items-center">
                                        @if (is_null($studentQuery->placementTestResult))
                                            <flux:badge color="gray" variant="solid" size="sm" icon="circle-minus">Menunggu</flux:badge>
                                        @else
                                            @if ($studentQuery->placementTestResult->final_result == \App\Enums\PlacementTestEnum::RESULT_PASS)
                                                <flux:badge color="green" variant="solid" size="sm" icon="check-circle">Lanjutkan</flux:badge>
                                            @else
                                                <flux:badge color="red" variant="solid" size="sm" icon="x-circle">Berhenti</flux:badge>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <flux:text variant="soft" class="mt-1">
                                    @if (is_null($studentQuery->placementTestResult))
                                        Anda bisa melakukan daftar ulang setelah hasil tes diumumkan
                                    @else
                                        @if ($studentQuery->placementTestResult->final_result == \App\Enums\PlacementTestEnum::RESULT_PASS)
                                            Anda bisa melakukan daftar ulang
                                            <flux:link wire:navigate href="{{ route('student.placement_test.test_result.final_registration') }}">
                                                disini
                                            </flux:link>
                                        @else
                                            Pembayaran uang pangkal bagi santri yang dinyatakan lulus
                                        @endif
                                    @endif
                                </flux:text>
                            </div>
                        </div>
                        <!--#Daftar Ulang-->
                    </div>
                </div>
            </x-cards.soft-glass-card>
        </x-animations.fade-down>
    </div>
    <!--#Timeline Student Admission-->

    <x-animations.fade-down showTiming="150">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4 gap-4 items-stretch">
            <!--Student Account-->
            <div class="col-span-1">
                <x-cards.user-card class="h-full">
                    <x-slot:fullName>{{ $studentQuery->branch_name }}</x-slot:fullName>
                    <x-slot:position>
                        {{ $studentQuery->program_name }}
                    </x-slot:position>
                    <div class="flex justify-between mt-2 gap-2">
                        <flux:badge color="primary" variant="solid">{{ $studentQuery->reg_number }}</flux:badge>
                        <flux:badge color="primary" variant="solid">{{ $studentQuery->academic_year }}</flux:badge>
                    </div>
                </x-cards.user-card>
            </div>
            <!--#Student Account-->

            <!--Student Biodata and Attachment-->
            <div class="col-span-1">
                <x-cards.soft-glass-card class="h-full">
                    <flux:heading size="xl">Status Validasi</flux:heading>
                    <!--Registration Payment-->
                    <x-lists.list-group>
                        <x-slot:title>
                            <div class="flex items-center gap-1">
                                Biaya Pendaftaran
                                <a href="{{ route('student.payment.registration_payment') }}" wire:navigate>
                                    <flux:icon.external-link variant="micro" class="text-primary" />
                                </a>
                            </div>
                        </x-slot:title>
                        <x-slot:subTitle>{{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($studentQuery->registration_fee) }}</x-slot:subTitle>
                        <x-slot:buttonAction>
                            @if ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::VALID)
                                <flux:badge color="green" variant="solid" size="sm">Valid</flux:badge>
                            @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::INVALID)
                                <flux:badge color="red" variant="solid" size="sm">Tidak Valid</flux:badge>
                            @elseif ($studentQuery->registration_payment == \App\Enums\VerificationStatusEnum::PROCESS)
                                <flux:badge color="orange" variant="solid" size="sm">Proses</flux:badge>
                            @else
                                <flux:badge color="gray" variant="solid" size="sm">Menunggu</flux:badge>
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
                                <flux:badge color="red" variant="solid" size="sm">Tidak Valid</flux:badge>
                            @elseif ($studentQuery->biodata == \App\Enums\VerificationStatusEnum::PROCESS)
                                <flux:badge color="orange" variant="solid" size="sm">Proses</flux:badge>
                            @else
                                <flux:badge color="gray" variant="solid" size="sm">Menunggu</flux:badge>
                            @endif
                        </x-slot:buttonAction>
                    </x-lists.list-group>
                    <!--#Biodata-->

                    <!--Attachment-->
                    <x-lists.list-group>
                        <x-slot:title>
                            <div class="flex items-center gap-1">
                                Berkas
                                <a href="{{ route('student.admission_data.admission_attachment') }}" wire:navigate>
                                    <flux:icon.external-link variant="micro" class="text-primary" />
                                </a>
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
                                <flux:badge color="red" variant="solid" size="sm">Tidak Valid</flux:badge>
                            @elseif ($studentQuery->attachment == \App\Enums\VerificationStatusEnum::PROCESS)
                                <flux:badge color="orange" variant="solid" size="sm">Proses</flux:badge>
                            @else
                                <flux:badge color="gray" variant="solid" size="sm">Menunggu</flux:badge>
                            @endif
                        </x-slot:buttonAction>
                    </x-lists.list-group>
                    <!--#Attachment-->
                </x-cards.soft-glass-card>
            </div>
            <!--#Student Biodata and Attachment-->

            <!--QR Code for Test Presence-->
            <div class="col-span-1">
                <x-cards.soft-glass-card class="h-full">
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
            <!--#QR Code for Test Presence-->
        </div>
    </x-animations.fade-down>
</div>