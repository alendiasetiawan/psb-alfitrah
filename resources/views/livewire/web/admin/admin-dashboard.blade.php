<div>
    <!--ANCHOR: COUNTER STATISTIC-->
    <x-animations.fade-down>
        <div class="flex items-start mt-4">
            <flux:heading size="xl">PSB Tahun Ajaran {{ $activeAdmission->name }}</flux:heading>
        </div>
        <div class="grid grid-cols-3 mt-2 gap-4">
            <!--CARD: Total Payment-->
            <div class="col-span-1">
                <x-cards.counter-card>
                    <x-slot:heading>Total Pemasukan Pendaftaran</x-slot:heading>
                    <x-slot:mainCounter>{{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($counterStatistic->total_payment) }}</x-slot:mainCounter>
                    <x-slot:subIcon>
                        <flux:icon.hand-coins class="text-primary size-16"/>
                    </x-slot:subIcon>
                </x-cards.counter-card>
            </div>
            <!--#Total Payment-->

            <!--CARD: Total Registrant-->
            <div class="col-span-1">
                <x-cards.counter-card>
                    <x-slot:heading>Total Pendaftar</x-slot:heading>
                    <x-slot:mainCounter>{{ $counterStatistic->total_registrant }}</x-slot:mainCounter>
                    <x-slot:subCounter>Siswa</x-slot:subCounter>
                    <x-slot:subIcon>
                        <flux:icon.users class="text-primary size-16"/>
                    </x-slot:subIcon>
                </x-cards.counter-card>
            </div>
            <!--#Total Registrant-->

            <!--CARD: Total Student-->
            <div class="col-span-1">
                <x-cards.counter-card>
                    <x-slot:heading>Total Lulus</x-slot:heading>
                    <x-slot:mainCounter>{{ $counterStatistic->total_student_pass }}</x-slot:mainCounter>
                    <x-slot:subCounter>Siswa</x-slot:subCounter>
                    <x-slot:subIcon>
                        <flux:icon.user-check class="text-primary size-16"/>
                    </x-slot:subIcon>
                </x-cards.counter-card>
            </div>
            <!--#Total Student-->
        </div>
    </x-animations.fade-down>
    <!--#COUNTER STATISTIC-->

    <!--ANCHOR: CHART STATISTIC-->
    <x-animations.fade-down showTiminng="150">
        <div class="grid lg:grid-cols-12 mt-4 gap-4 items-stretch">
            <!---CARD: Bar Chart Total Registrant Per Program-->
            <div class="col-span-7">
                <x-cards.soft-glass-card class="h-full">
                    <flux:heading size="xl">Jumlah Pendaftar Per Program</flux:heading>
                    <div style="height: 275px;">
                        <livewire:livewire-column-chart key="{{ $this->registrantPerProgram->reactiveKey() }}" :column-chart-model="$this->registrantPerProgram"/>
                    </div>
                </x-cards.soft-glass-card>
            </div>

            <!--CARD: Pie Chart Percentage Payment Success-->
            <div class="col-span-5">
                <x-cards.soft-glass-card class="h-full">
                    <flux:heading size="xl">Persentase Pembayaran</flux:heading>
                    <div style="height: 275px;">
                        <livewire:livewire-radial-chart key="{{ $this->percentageTotalPaymentSuccess->reactiveKey() }}" :radial-chart-model="$this->percentageTotalPaymentSuccess"/>
                    </div>
                    <div class="flex justify-between items-center gap-4 px-8">
                        <div class="flex items-center gap-1">
                            <flux:icon.users variant="mini" class="text-primary"/>
                            <flux:text>Pendaftar : {{ $this->countPaymentSuccess->total_registrant }} Siswa</flux:text>
                        </div>

                        <div class="flex items-center gap-1">
                            <flux:icon.banknotes variant="mini" class="text-primary"/>
                            <flux:text>Pembayaran : {{ $this->countPaymentSuccess->total_payment_success }} Siswa </flux:text>
                        </div>  
                    </div>
                </x-cards.soft-glass-card>
            </div>
            <!--#Pie Chart Percentage Payment Success-->
        </div>
    </x-animations.fade-down>
    <!--#CHART STATISTIC-->

    <!--ANCHOR: ADMISSION STATUS MONITORING-->
    <x-animations.fade-down showTiminng="250">
        <div class="grid lg:grid-cols-12 mt-4 gap-4 items-stretch">
            <!--CARD: Biodata Status-->
            <div class="col-span-4">
                <x-cards.soft-glass-card class="h-full">
                    <flux:heading size="xl" class="mb-4">Status Biodata</flux:heading>
                    <div class="space-y-2">
                        <!--NOTE: Pending Biodata-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-gray-200 p-2 rounded-md">
                                    <flux:icon.triangle-alert variant="mini" class="text-gray-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Belum Lengkap</flux:text>
                                    <flux:text variant="soft">{{ $biodataStatus->total_pending }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.biodata.pending') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Pending Biodata-->

                        <!--NOTE: Process Biodata-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-amber-200 p-2 rounded-md">
                                    <flux:icon.refresh-cw variant="mini" class="text-amber-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Proses</flux:text>
                                    <flux:text variant="soft">{{ $biodataStatus->total_process }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.biodata.process') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Process Biodata-->

                        <!--NOTE: Invalid Biodata-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-red-200 p-2 rounded-md">
                                    <flux:icon.x variant="mini" class="text-red-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Tidak Valid</flux:text>
                                    <flux:text variant="soft">{{ $biodataStatus->total_invalid }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.biodata.process') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Invalid Biodata-->

                        <!--NOTE: Valid Biodata-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-green-200 p-2 rounded-md">
                                    <flux:icon.check-check variant="mini" class="text-green-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Valid</flux:text>
                                    <flux:text variant="soft">{{ $biodataStatus->total_valid }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.biodata.verified') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Valid Biodata-->
                    </div>
                </x-cards.soft-glass-card>
            </div>
            <!--#Biodata Status-->

            <!--CARD: Student Attachment Status-->
            <div class="col-span-4">
                <x-cards.soft-glass-card class="h-full">
                    <flux:heading size="xl" class="mb-4">Status Berkas</flux:heading>
                    <div class="space-y-2">
                        <!--NOTE: Pending Attachment-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-gray-200 p-2 rounded-md">
                                    <flux:icon.triangle-alert variant="mini" class="text-gray-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Belum Lengkap</flux:text>
                                    <flux:text variant="soft">{{ $studentAttachmentStatus->total_pending }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.student_attachment.pending') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Pending Attachment-->

                        <!--NOTE: Process Attachment-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-amber-200 p-2 rounded-md">
                                    <flux:icon.refresh-cw variant="mini" class="text-amber-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Proses</flux:text>
                                    <flux:text variant="soft">{{ $studentAttachmentStatus->total_process }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.student_attachment.process') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Process Attachment-->

                        <!--NOTE: Invalid Attachment-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-red-200 p-2 rounded-md">
                                    <flux:icon.x variant="mini" class="text-red-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Tidak Valid</flux:text>
                                    <flux:text variant="soft">{{ $studentAttachmentStatus->total_invalid }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.student_attachment.process') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Invalid Attachment-->

                        <!--NOTE: Valid Attachment-->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="bg-green-200 p-2 rounded-md">
                                    <flux:icon.check-check variant="mini" class="text-green-600"/>
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold">Valid</flux:text>
                                    <flux:text variant="soft">{{ $studentAttachmentStatus->total_valid }} Santri</flux:text>
                                </div>
                            </div>
                            <a href="{{ route('admin.data_verification.student_attachment.verified') }}" wire:navigate>
                                <flux:icon.external-link variant="mini" class="text-primary"/>
                            </a>
                        </div>
                        <!--#Valid Attachment-->
                    </div>
                </x-cards.soft-glass-card>
            </div>
            <!--#Student Attachment Status-->

            <!--CARD: Latest Registrant-->
            <div class="col-span-4">
                <x-cards.soft-glass-card class="h-full">
                    <flux:heading size="xl" class="mb-4">Pendaftar Terbaru</flux:heading>
                    <div class="space-y-2">
                        @forelse ($latestRegistrants as $registrant)
                            <div class="flex justify-between items-center" wire:key="registrant{{ $registrant->id }}">
                                <div class="flex flex-col items-start">
                                    <flux:text variant="bold" size="lg" class="truncate max-w-[200px]">{{ $registrant->student_name }}</flux:text>
                                    <flux:text variant="soft" size="sm">{{ $registrant->branch_name }} - {{ $registrant->program_name }}</flux:text>
                                </div>
                                <div class="flex flex-col items-end">
                                    <flux:text variant="soft" size="sm">
                                        {{ \App\Helpers\DateFormatHelper::shortIndoDate($registrant->created_at) }}
                                    </flux:text>
                                    <flux:text variant="soft" size="sm">
                                        {{ \App\Helpers\DateFormatHelper::shortTime($registrant->created_at) }}
                                    </flux:text>
                                </div>
                            </div>
                        @empty
                            <x-animations.not-found width="150" height="150"/>
                        @endforelse

                        <div class="flex justify-center">
                            <flux:button
                            icon="users"
                            variant="primary"
                            class="w-full"
                            href="{{ route('admin.master_data.registrant_database') }}"
                            wire:navigate>
                                Selengkapnya
                            </flux:button>
                        </div>
                    </div>
                </x-cards.soft-glass-card>
            </div>
            <!--#Latest Registrant-->

        </div>
    </x-animations.fade-down>
    <!--#ADMISSION STATUS MONITORING-->

</div>
