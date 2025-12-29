<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Tapping QR') }}</x-slot:title>
        <x-slot:activePage>{{ __('Tapping QR Absensi Tes') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="grid grid-cols-1 mt-4">
            <x-tables.basic-table :headers="['No', 'Nama Santri', 'Whatsapp', 'Cabang', 'Batch', 'Waktu Kehadiran']">

                <x-slot:content>
                    <!--NOTE: Search field for tapping--->
                    <form wire:submit="scanQr">
                        <div class="flex flex-col justify-center items-center my-10">
                            <flux:heading size="xxl">Absensi Kehadiran Tes Masuk TA {{ $academicYear }}</flux:heading>
                            <div class="flex items-center gap-2 my-3 w-3/6">
                                <flux:input
                                icon="qr-code"
                                wire:model="studentQr"
                                placeholder="QR Code"
                                autofocus
                                tabindex="1"
                                />

                                <flux:button
                                type="submit"
                                variant="primary"
                                tabindex="2">
                                    Scan
                                </flux:button>
                            </div>
                        </div>
                    </form>
                    <!--#Search field for tapping--->

                    <!--NOTE: Badge Counter-->
                    <div class="flex justify-between my-2 px-4">
                        <flux:badge
                        icon="user-check"
                        variant="solid"
                        color="green">
                            Hadir : {{ $this->presenceStudents['totalPresence'] ?? 0 }} Siswa
                        </flux:badge>

                        <flux:badge
                        icon="user-x"
                        variant="solid"
                        color="red">
                            Tidak Hadir : {{ $this->presenceStudents['totalAbsence'] ?? 0 }} Siswa
                        </flux:badge>
                    </div>
                    <!--#Badge Counter-->

                    @if (session('error-tapping'))
                        <div class="grid grid-cols-1 my-4 mx-4">
                            <x-notifications.basic-alert>
                                <x-slot:title>{{ session('error-tapping') }}</x-slot:title>
                            </x-notifications.basic-alert>
                        </div>
                    @endif
                </x-slot:content>

                <!--NOTE: Student's Table-->
                @forelse ($this->presenceStudents['studentLists'] as $presence)
                    <x-tables.row 
                        wire:key="presence{{ $presence->id }}"
                        :loop=$loop
                        :striped="true">
                            <x-tables.cell>
                                <flux:text>{{ $setCount++ }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <div class="flex flex-col items-start">
                                    <flux:text>
                                        {{ $presence->student->student_name }}
                                    </flux:text>
                                    <flux:text size="sm" variant="soft">
                                        {{ $presence->student->gender }}
                                    </flux:text>
                                </div>
                            </x-tables.cell>

                            <x-tables.cell>
                                <div class="flex items-center gap-1">
                                    <flux:text>
                                        {{ $presence->student->mobile_phone }}
                                    </flux:text>
                                </div>
                            </x-tables.cell>
                            
                            <x-tables.cell>
                                <div class="flex flex-col items-start">
                                    <flux:text>{{ $presence->student->branch_name }}</flux:text>
                                    <flux:text variant="soft" size="sm">{{ $presence->student->program_name }}</flux:text>
                                </div>
                            </x-tables.cell>
                            
                            <x-tables.cell>
                                <flux:text>{{ $presence->student->batch_name }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>
                                    {{ \App\Helpers\DateFormatHelper::indoDateTime($presence->check_in_time) }}
                                </flux:text>
                            </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.empty text="Tidak ada data yang ditemukan" :colspan="6" />
                @endforelse
                <!--#Student's Table-->
            </x-tables.basic-table>

            <!--NOTE: Pagination-->
            @if ($this->presenceStudents['studentLists']->hasMorePages())
                <div class="mt-2">
                    <livewire:components.buttons.load-more loadItem="18" />
                </div>
            @endif
            <!--#Pagination-->
    </div>
</div>
