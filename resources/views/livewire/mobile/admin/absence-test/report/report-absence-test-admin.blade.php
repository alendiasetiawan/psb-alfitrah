<div>
    <div class="my-3">
        <x-navigations.flat-tab>
            <x-navigations.flat-tab-item 
                href="admin.placement_test.absence_test.tapping" 
                label="Tapping" 
            />
            <x-navigations.flat-tab-item
                :isActive="true" 
                activeTextColor="text-white" 
                label="Rekap" />
        </x-navigations.flat-tab>
    </div>

    <div class="grid grid-cols-1 space-y-2">
        <!--ANCHOR: SEARCH AND FILTER-->
        <div class="col-span-1">
            <div class="flex items-center gap-4">
                <div class="w-11/12">
                    <flux:input placeholder="Cari nama santri" wire:model.live.debounce.500ms="searchStudent"
                        icon="search" />
                </div>

                <div class="w-1/12">
                    <flux:modal.trigger name="filter-student-modal">
                        <flux:icon.sliders-horizontal class="hover:cursor-pointer text-primary-400" />
                    </flux:modal.trigger>
                </div>
            </div>
        </div>

        <div class="col-span-1">
            <div class="flex justify-between items-center">
                <flux:badge icon="graduation-cap" color="primary" variant="solid">
                    {{ $admissionYear }}
                </flux:badge>

                @if ($selectedAdmissionBatchId)
                    <flux:badge icon="layers" color="primary" variant="solid">
                        {{ $admissionBatchName }}
                    </flux:badge>
                @endif
            </div>
        </div>
        <!--#SEARCH AND FILTER-->
    </div>

    <!--NOTE: Loading Indicator When Filter Apply-->
    <div class="flex items-center justify-center mb-3">
        <div wire:loading wire:target="searchStudent">
            <x-loading.horizontal-dot topMargin="mt-3"/>
        </div>
    </div>
    <!--#Loading Indicator When Filter Apply-->

    <x-animations.fade-down showTiming="150">
        <div class="grid grid-cols-1 gap-3">
            @forelse($this->presenceReportStudents['studentLists'] as $student)    
                <div class="col-span-1">
                    <x-cards.flat-card>
                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->gender }} | 0{{ $student->mobile_phone }}</x-slot:subHeading>

                        <x-slot:label>
                            <flux:modal.trigger name="set-absence-student-modal">
                                @if (empty($student->placementTestPresence))
                                    <flux:icon.fingerprint-pattern class="text-red-400 hover:cursor-pointer" wire:click="$dispatch('blank-absence-student-modal', { id: '{{ Crypt::encrypt($student->id) }}' })"/>
                                @else
                                    <flux:icon.fingerprint-pattern class="text-green-400 hover:cursor-pointer" wire:click="$dispatch('filled-absence-student-modal', { id: '{{ Crypt::encrypt($student->id) }}' })"/>
                                @endif
                            </flux:modal.trigger>
                        </x-slot:label>

                        <flux:text variant="soft">Batch : <strong class="text-white">{{ $student->batch_name }}</strong></flux:text>

                        @if (empty($student->placementTestPresence))
                            <flux:text color="amber">Tidak Hadir</flux:text>
                        @else
                            <flux:text variant="soft">Status : <strong class="text-white">Hadir</strong></flux:text>
                            <flux:text variant="soft">
                                Waktu :  <strong class="text-white">{{ \App\Helpers\DateFormatHelper::indoDateTime($student->placementTestPresence->check_in_time) }}</strong>
                            </flux:text>
                        @endif

                        <x-slot:subContent>
                            <flux:badge color="primary" icon="school" size="sm">{{ $student->branch_name }}
                            </flux:badge>
                            <flux:badge color="primary" icon="graduation-cap" size="sm">
                                {{ $student->program_name }}</flux:badge>
                        </x-slot:subContent>
                    </x-cards.flat-card>
                </div>
            @empty
                <div class="col-span-1">
                    <x-animations.not-found />
                </div>
            @endforelse
        </div>

        <div class="grid grid-cols-1 mt-3">
            <!--NOTE: Load More Button-->
            @if ($this->presenceReportStudents['studentLists']->hasMorePages())
                <livewire:components.buttons.load-more loadItem="20" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>


    <!--ANCHOR - FLYOUT MODAL FILTER STUDENT-->
    <flux:modal name="filter-student-modal" variant="flyout" class="w-11/12" closable="true">
        <flux:heading size="xl">Filter Data</flux:heading>
        <div class="space-y-4 mt-5">
            <!--Filter Academic Year-->
            <div class="grid grid-cols-1 gap-3">
                <flux:select wire:model="filterAdmissionId" label="Pilih Tahun Ajaran">
                    @foreach ($admissionYearLists as $key => $value)
                        <flux:select.option value="{{ $key }}">
                            {{ $value }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select label="Pilih Batch" wire:model="filterAdmissionBatchId" placeholder="Semua Batch">
                    @foreach ($admissionBatchLists as $key => $value)
                        <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <!--#Filter Academic Year-->

            <!--Button Action-->
            <div class="fixed bottom-0 left-0 right-0 p-6">
                    <div class="flex flex-col gap-2">
                        <flux:modal.close>
                            <flux:button variant="primary" class="w-full" wire:click="setFilter">
                                Terapkan
                            </flux:button>
                        </flux:modal.close>


                        @if ($isFilterActive)
                        <flux:modal.close>
                            <flux:button variant="filled" class="w-full" wire:click="resetFilter">
                                Hapus Filter
                            </flux:button>
                        </flux:modal.close>
                        @endif
                    </div>
            </div>
            <!--#Button Action-->
        </div>
    </flux:modal>
    <!--#MODAL FILTER STUDENT-->

    <!--ANCHOR: SET ABSENCE STUDENT MODAL-->
    <livewire:components.modals.placement-test.set-absence-student-modal modalId="set-absence-student-modal"/>
    <!--#SET ABSENCE STUDENT MODAL-->
</div>
