<div>
    <x-navigations.breadcrumb>
        <x-slot:title>Selesai Bayar Pendaftaran</x-slot:title>
        <x-slot:activePage>Verifikasi Selesai Bayar Pendaftaran</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--ANCHOR - COUNTER STUDENT STATISTIC-->
    <x-animations.fade-down showTiming="50">
        <div class="grid grid-cols-3 mt-4 gap-3">
            <div class="col-span-1">
                <x-cards.counter-card subCounterColor="soft-white">
                    <x-slot:heading>
                        Total Pemasukan
                    </x-slot:heading>
                    <x-slot:mainCounter>
                        {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($this->totalIncome['sumPayment']) }}
                    </x-slot:mainCounter>
                    <x-slot:subIcon>
                        <flux:icon.hand-coins class="size-15 text-primary-400"/>
                    </x-slot:subIcon>
                </x-cards.counter-card>
            </div>

            <div class="col-span-1">
                <x-cards.counter-card subCounterColor="soft-white">
                    <x-slot:heading>
                        Jumlah Pembayaran
                    </x-slot:heading>
                    <x-slot:mainCounter>
                        {{ $this->totalIncome['totalStudent'] }}
                    </x-slot:mainCounter>
                    <x-slot:subCounter>
                        Santri
                    </x-slot:subCounter>
                    <x-slot:subIcon>
                        <flux:icon.users class="size-15 text-primary-400"/>
                    </x-slot:subIcon>
                </x-cards.counter-card>
            </div>
        </div>
    </x-animations.fade-down>

    <!--ANCHOR: STUDENT LISTS-->
    <div class="grid grid-cols-1 mt-4">
        <x-animations.fade-down showTiming="50">
            <x-tables.basic-table :headers="['No', 'Nama Santri', 'Whatsapp', 'Cabang', 'Tanggal Pembayaran', 'Via', 'Nominal']">
                <x-slot:heading>
                    Tabel Siswa Selesai Pembayaran
                </x-slot:heading>

                <x-slot:action>
                    <!-- Search and Filter -->
                    <div class="grid grid-cols-2 justify-between items-center mt-4 gap-2">
                        <div class="flex gap-2">
                            <div class="w-4/6">
                                <flux:input placeholder="Cari nama/nomor HP santri" wire:model.live.debounce.500ms="searchStudent" icon="search" />
                            </div>

                            <div class="w-2/6">
                                <flux:select wire:model.live="selectedAdmissionId">
                                    @foreach ($admissionYearLists as $key => $value)
                                        <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                        </div>
                    </div>
                </x-slot:action>

                <!--NOTE: Student's Table-->
                @forelse ($this->paidStudentLists as $student)
                    <x-tables.row 
                        :striped="true" 
                        wire:key="{{ $student->id }}" 
                        :loop=$loop>
                            <x-tables.cell>
                                <flux:text>{{ $setCount++ }}</flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>
                                    {{ $student->student_name }}
                                </flux:text>
                                <flux:text variant="soft" size="sm">
                                    {{ $student->gender }}
                                </flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <div class="flex items-center gap-1">
                                    <flux:text>
                                        0{{ $student->mobile_phone }}
                                    </flux:text>
                                    <x-items.wa-icon href="https://wa.me/{{ $student->country_code }}{{ $student->mobile_phone }}"/>
                                </div>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>{{ $student->branch_name }}</flux:text>
                                <flux:text variant="soft" size="sm">{{ $student->program_name }}</flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>
                                    {{ \App\Helpers\DateFormatHelper::indoDateTime($student->registrationInvoices[0]->paid_at) }}
                                </flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>
                                    {{ $student->registrationInvoices[0]->payment_method }}
                                </flux:text>
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:text>
                                    {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($student->registrationInvoices[0]->amount) }}
                                </flux:text>
                            </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.empty text="Tidak ada data yang ditemukan" :colspan="7" />
                @endforelse
                <!--#Student's Table-->

                @if ($this->paidStudentLists->hasPages())
                    <x-slot:pagination>
                        {{ $this->paidStudentLists->links(data: ['scrollTo' => false]) }}
                    </x-slot:pagination>
                @endif
            </x-tables.basic-table>
        </x-animations.fade-down>
    </div>
</div>
