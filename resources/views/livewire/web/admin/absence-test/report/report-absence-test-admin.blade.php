<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Rekap Kehadiran Tes') }}</x-slot:title>
        <x-slot:activePage>{{ __('Rekap Kehadiran Santri Tes') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--ANCHOR: STUDENT LISTS-->
    <div class="grid grid-cols-1 mt-4">
            <x-tables.basic-table :headers="['No', 'Nama Santri', 'Whatsapp', 'Cabang', 'Batch', 'Waktu Kehadiran', 'Absensi']">
                <x-slot:heading>
                    Tabel Kehadiran Tes Masuk
                </x-slot:heading>

                <x-slot:action>
                    <!-- NOTE: Search and Filter -->
                    <div class="grid grid-cols-1 justify-between items-center mt-4 gap-2">
                        <div class="col-span-1">
                            <div class="flex gap-2">
                                <div class="w-4/12">
                                    <flux:input placeholder="Cari nama santri" wire:model.live.debounce.500ms="searchStudent" icon="search" />
                                </div>

                                <div class="w-2/12">
                                    <flux:select wire:model.live="selectedAdmissionId">
                                        @foreach ($admissionYearLists as $key => $value)
                                            <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>

                                <div class="w-3/12">
                                    <flux:select wire:model.live="selectedAdmissionBatchId" placeholder="Semua Batch">
                                        @foreach ($admissionBatchLists as $key => $value)
                                            <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>

                                @if ($isFilterActive)
                                    <div class="w-3/12 flex items-center gap-1 hover:cursor-pointer" wire:click="resetFilter">
                                        <flux:icon.refresh-cw class="size-4 text-amber-400"/>
                                        <flux:text color="amber">
                                            Reset Filter
                                        </flux:text>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- #Search and Filter -->

                    <!--NOTE: Counter-->
                    <div class="flex items-center gap-2 mt-4">
                        <flux:badge variant="solid" color="green" icon="user-check">
                            Hadir :  {{ $this->presenceReportStudents['totalPresence'] }} Santri
                        </flux:badge>
                        <flux:badge variant="solid" color="red" icon="user-x">
                            Tidak Hadir :  {{ $this->presenceReportStudents['totalAbsence'] }} Santri
                        </flux:badge>
                    </div>
                    <!--#Counter-->
                </x-slot:action>

                <!--NOTE: Student's Table-->
                @forelse ($this->presenceReportStudents['studentLists'] as $student)
                    <x-tables.row 
                        wire:key="presence{{ $student->id }}" 
                        :striped="true" 
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
                                <flux:text>{{ $student->batch_name }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>
                                    @if (empty($student->placementTestPresence))
                                        <flux:text color="amber">Tidak Hadir</flux:text>
                                    @else
                                        {{ \App\Helpers\DateFormatHelper::indoDateTime($student->placementTestPresence->check_in_time) }}
                                    @endif
                                </flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:modal.trigger name="set-absence-student-modal">
                                    @if (empty($student->placementTestPresence))
                                        <flux:icon.fingerprint-pattern class="text-red-400 hover:cursor-pointer" wire:click="$dispatch('blank-absence-student-modal', { id: '{{ Crypt::encrypt($student->id) }}' })"/>
                                    @else
                                        <flux:icon.fingerprint-pattern class="text-green-400 hover:cursor-pointer" wire:click="$dispatch('filled-absence-student-modal', { id: '{{ Crypt::encrypt($student->id) }}' })"/>
                                    @endif
                                </flux:modal.trigger>
                            </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.empty text="Tidak ada data yang ditemukan" :colspan="7" />
                @endforelse
                <!--#Student's Table-->

                @if ($this->presenceReportStudents['studentLists']->hasPages())
                    <x-slot:pagination>
                        {{ $this->presenceReportStudents['studentLists']->links(data: ['scrollTo' => false]) }}
                    </x-slot:pagination>
                @endif
            </x-tables.basic-table>
    </div>

    <!--ANCHOR: SET ABSENCE STUDENT MODAL-->
    <livewire:components.modals.placement-test.set-absence-student-modal modalId="set-absence-student-modal"/>
    <!--#SET ABSENCE STUDENT MODAL-->

</div>
