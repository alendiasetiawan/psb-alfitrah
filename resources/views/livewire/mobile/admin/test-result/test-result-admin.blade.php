<div>
    <x-animations.fixed-top top="top-0">
        <div class="grid grid-cols-1 space-y-2">
            <flux:heading variant="dark-bold" size="xl">Hasil Tes Calon Siswa</flux:heading>
            <!--ANCHOR: SEARCH AND FILTER-->
            <div class="col-span-1">
                <div class="flex items-center gap-4">
                    <div class="w-11/12">
                        <flux:input placeholder="Cari nama siswa" wire:model.live.debounce.500ms="searchStudent"
                            icon="search" />
                    </div>

                    <div class="w-1/12">
                        <flux:modal.trigger name="filter-student-modal">
                            <flux:icon.sliders-horizontal class="hover:cursor-pointer text-primary-400" />
                        </flux:modal.trigger>
                    </div>
                </div>
            </div>
            <!--#SEARCH AND FILTER-->

            <!--ANCHOR: RELEASE AND COUNTER-->
            <div class="col-span-1">
                <div class="flex justify-between items-center">
                    <flux:modal.trigger name="confirm-release-modal">
                        <flux:button variant="primary" size="sm"
                            wire:click="$dispatch('open-confirm-release-modal')">
                            Release Hasil
                        </flux:button>
                    </flux:modal.trigger>

                    <div class="flex gap-2">
                        <flux:badge variant="solid" color="green" icon="user-check" shadow-variant="soft-shadow"
                            size="sm">
                            {{ $this->totalResult['totalPass'] }}
                        </flux:badge>
                        <flux:badge variant="solid" color="red" icon="user-x" shadow-variant="soft-shadow"
                            size="sm">
                            {{ $this->totalResult['totalFail'] }}
                        </flux:badge>
                    </div>
                </div>
            </div>
            <!--#RELEASE AND COUNTER-->
        </div>

        <!--NOTE: Pill Badge Active Filter-->
        @if ($isFilterActive)
            <div class="grid grid-cols-1 gap-2 mt-2">
                <div class="flex gap-2 items-center">
                    <flux:badge variant="solid" color="sky" size="sm">
                        {{ $admissionYear }}
                    </flux:badge>

                    @if ($admissionBatchName)
                        <flux:badge variant="solid" color="sky" size="sm">
                            {{ $admissionBatchName }}
                        </flux:badge>
                    @endif

                    @if ($branchName)
                        <flux:badge variant="solid" color="sky" size="sm">
                            {{ $branchName }}
                        </flux:badge>
                    @endif
                </div>
            </div>
        @endif
        <!--#Pill Badge Active Filter-->
    </x-animations.fixed-top>

    <!-- Spacer to prevent content from hiding under fixed header -->
    <div class="h-23"></div>

    <!--NOTE: Loading Indicator When Filter Apply-->
    <div class="flex items-center justify-center">
        <div wire:loading wire:target="searchStudent">
            <x-loading.horizontal-dot />
        </div>
    </div>
    <!--#Loading Indicator When Filter Apply-->

    <!--ANCHOR: STUDENT LISTS-->
    <x-animations.fade-down>
        <div class="grid grid-cols-1 gap-4">
            @forelse ($this->testResultStudents as $student)
                @php
                    $finalResultColor =
                        $student->final_result == \App\Enums\PlacementTestEnum::RESULT_PASS
                            ? 'text-green-400'
                            : ($student->final_result == \App\Enums\PlacementTestEnum::RESULT_FAIL
                                ? 'text-red-400'
                                : 'text-white/50');

                    $parentInterviewColor =
                        $student->parent_interview == \App\Enums\PlacementTestEnum::INTERVIEW_PASS
                            ? 'green'
                            : ($student->parent_interview == \App\Enums\PlacementTestEnum::INTERVIEW_FAIL
                                ? 'red'
                                : 'primary');

                    $studentInterviewColor =
                        $student->student_interview == \App\Enums\PlacementTestEnum::INTERVIEW_PASS
                            ? 'green'
                            : ($student->student_interview == \App\Enums\PlacementTestEnum::INTERVIEW_FAIL
                                ? 'red'
                                : 'primary');

                    $publicationColor =
                        $student->publication_status == \App\Enums\PlacementTestEnum::PUBLICATION_RELEASE
                            ? 'text-green-400'
                            : 'text-amber-400';
                @endphp
                <div class="col-span-1" wire:key="result{{ $student->id }}">
                    <flux:modal.trigger name="detail-test-result-modal">
                        <x-cards.flat-card
                            wire:click="$dispatch('open-detail-test-result-modal', { id: '{{ Crypt::encrypt($student->id) }}' })"
                            clickable="true"
                            avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
                            avatarImage="{{ !empty($student->user_photo) ? asset($student->user_photo) : '' }}">
                            <x-slot:heading>
                                {{ $student->student_name }}
                            </x-slot:heading>

                            <x-slot:subHeading>
                                {{ $student->batch_name }}
                                -
                                <strong class="{{ $publicationColor }}">
                                    {{ $student->publication_status }}
                                </strong>
                            </x-slot:subHeading>

                            <x-slot:label>
                                <a x-on:click.stop>
                                    <flux:dropdown offset="-5" gap="1">
                                        <flux:button variant="ghost" size="xs" class="hover:cursor-pointer">
                                            <flux:icon.ellipsis-vertical variant="micro" class="text-white" />
                                        </flux:button>
                                        
                                        <flux:menu>
                                            <flux:menu.item icon="file-pen-line" wire:click="#">
                                                Edit Nilai
                                            </flux:menu.item>

                                            <flux:modal.trigger name="publication-modal">
                                                <flux:menu.item icon="megaphone"
                                                    wire:click="$dispatch('open-publication-modal', {
                                                    id: '{{ Crypt::encrypt($student->test_id) }}' })">
                                                    Publikasi
                                                </flux:menu.item>
                                            </flux:modal.trigger>
                                        </flux:menu>
                                    </flux:dropdown>
                                </a>
                            </x-slot:label>

                            <flux:text variant="soft">
                                Nilai Akhir : <strong class="text-white">{{ $student->final_score ?? '-' }}</strong>
                            </flux:text>

                            <flux:text variant="soft">
                                Hasil Akhir : <strong
                                    class="{{ $finalResultColor }}">{{ $student->final_result ?? '-' }}</strong>
                            </flux:text>

                            <x-slot:subContent>
                                <flux:badge color="primary" icon="school" size="sm">
                                    {{ $student->branch_name }}
                                </flux:badge>

                                <flux:badge color="primary" icon="graduation-cap" size="sm">
                                    {{ $student->program_name }}
                                </flux:badge>
                            </x-slot:subContent>
                        </x-cards.flat-card>
                    </flux:modal.trigger>
                </div>
            @empty
                <div class="col-span-1">
                    <x-animations.not-found />
                </div>
            @endforelse
        </div>
    </x-animations.fade-down>
    <!--#STUDENT LISTS-->


    <!--ANCHOR - FLYOUT MODAL FILTER STUDENT-->
    <flux:modal name="filter-student-modal" variant="flyout" class="w-/12" closable="true">
        <flux:heading size="xl">Filter Data</flux:heading>
        <div class="space-y-4 mt-5">
            <div class="grid grid-cols-1 gap-3">
                <!--Filter Academic Year-->
                <flux:select wire:model.live="filterAdmissionId" label="Pilih Tahun Ajaran">
                    @foreach ($admissionYearLists as $key => $value)
                        <flux:select.option value="{{ $key }}">
                            {{ $value }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <!--#Filter Academic Year-->

                <!--Filter Batch-->
                <flux:select label="Pilih Batch" wire:model="filterAdmissionBatchId" placeholder="Semua Batch">
                    @foreach ($admissionBatchLists as $key => $value)
                        <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                    @endforeach
                </flux:select>
                <!--#Filter Batch-->

                <!--Filter Branch-->
                <flux:select label="Pilih Cabang" wire:model="filterBranchId" placeholder="Semua Cabang">
                    @foreach ($branchLists as $key => $value)
                        <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                    @endforeach
                </flux:select>
                <!--#Filter Branch-->
            </div>

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
                                Reset Filter
                            </flux:button>
                        </flux:modal.close>
                    @endif
                </div>
            </div>
            <!--#Button Action-->
        </div>
    </flux:modal>
    <!--#MODAL FILTER STUDENT-->

    <!--ANCHOR: PUBLICATION MODAL-->
    <livewire:components.modals.placement-test.set-publication-test-result-modal modalId="publication-modal"
        :$isMobile />
    <!--#PUBLICATION MODAL-->

    <!--ANCHOR: DETAIL TEST RESULT MODAL-->
    <livewire:components.modals.placement-test.detail-test-result-modal modalId="detail-test-result-modal"
        :$isMobile />
    <!--#DETAIL TEST RESULT MODAL-->

    <!--ANCHOR: CONFIRM RELEASE TEST MODAL-->
    <livewire:components.modals.placement-test.confirm-release-modal modalId="confirm-release-modal" :$isMobile />
    <!--#CONFIRM RELEASE TEST MODAL-->
</div>
