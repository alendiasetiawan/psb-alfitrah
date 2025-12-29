<div>
    <x-navigations.breadcrumb>
        <x-slot:title>Hasil Tes</x-slot:title>
        <x-slot:activePage>Rekapitulasi Nilai Hasil Tes</x-slot:activePage>
    </x-navigations.breadcrumb>

    @if ($showAlertNotification)
        <div class="grid grid-cols-1 mt-4">
            <x-notifications.basic-alert isCloseable="true">
                <x-slot:title>Gagal mengirimkan notifikasi, silahkan follow up manual!</x-slot:title>
            </x-notifications.basic-alert>
        </div>
    @endif  

    @if (session('failed-sent-notification'))
        <div class="grid grid-cols-1 mt-4">
            <x-notifications.basic-alert isCloseable="true">
                <x-slot:title>{{ session('failed-sent-notification') }}</x-slot:title>
            </x-notifications.basic-alert>
        </div>
    @endif


    <div class="grid-grid-cols-1 mt-4">
        <div class="col-span-1">
            {{-- <x-animations.fade-down showTiming="50"> --}}
                <x-tables.basic-table :headers="[
                    'No',
                    'Nama',
                    'Cabang',
                    'Psikotes',
                    'Al Quran',
                    'Wawancara',
                    'Nilai Akhir',
                    'Hasil Akhir',
                    'Opsi',
                    ]">
                    <x-slot:heading>
                        Tabel Nilai Hasil Tes Tahun Ajaran {{ $admissionYear }}
                    </x-slot:heading>

                    <x-slot:label>
                        <div class="flex gap-2">
                            <flux:badge variant="solid" color="green" icon="user-check" shadow-variant="soft-shadow">Lulus
                                : {{ $this->totalResult['totalPass'] }}</flux:badge>
                            <flux:badge variant="solid" color="red" icon="user-x" shadow-variant="soft-shadow">Tidak
                                Lulus : {{ $this->totalResult['totalFail'] }}</flux:badge>
                        </div>
                    </x-slot:label>

                    <x-slot:action>
                        <!-- NOTE: Search and Filter -->
                        <div class="grid grid-cols-1 justify-between items-center mt-4 gap-2">
                            <div class="col-span-1">
                                <div class="flex gap-2 items-end">
                                    <div class="w-4/12">
                                        <flux:input placeholder="Ketik nama siswa" label="Cari Siswa"
                                            wire:model.live.debounce.500ms="searchStudent" icon="search" />
                                    </div>

                                    <div class="w-2/12">
                                        <flux:select wire:model.live="limitData" label="Tampilkan">
                                            <flux:select.option value="10">
                                                10 Data
                                            </flux:select.option>
                                            <flux:select.option value="30">
                                                30 Data
                                            </flux:select.option>
                                            <flux:select.option value="50">
                                                50 Data
                                            </flux:select.option>
                                            <flux:select.option value="100">
                                                100 Data
                                            </flux:select.option>
                                        </flux:select>
                                    </div>

                                    <div class="w-2/12">
                                        <flux:select wire:model.live="selectedOrderBy" label="Urutkan">
                                            <flux:select.option value="{{ \App\Enums\OrderDataEnum::PUBLICATION }}">
                                                Publikasi (Hold)
                                            </flux:select.option>
                                            <flux:select.option value="{{ \App\Enums\OrderDataEnum::FINAL_SCORE }}">
                                                Nilai Akhir (Tertinggi)
                                            </flux:select.option>
                                        </flux:select>
                                    </div>

                                    <div class="w-1/12">
                                        <flux:modal.trigger name="filter-student-modal">
                                            <flux:button icon="sliders-horizontal" variant="outline">
                                                Filter
                                            </flux:button>
                                        </flux:modal.trigger>
                                    </div>

                                    <div class="w-1/12">
                                        <flux:modal.trigger name="confirm-release-modal">
                                            <flux:button variant="primary" wire:click="$dispatch('open-confirm-release-modal')">
                                                Release Hasil
                                            </flux:button>
                                        </flux:modal.trigger>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- #Search and Filter -->

                        <!--NOTE: Pill Badge Active Filter-->
                        @if ($isFilterActive)
                            <div class="grid grid-cols-1 gap-2 mt-4">
                                <div class="flex gap-2 items-center">
                                    <flux:badge variant="solid" color="sky">
                                        {{ $admissionYear }}
                                    </flux:badge>

                                    @if ($admissionBatchName)
                                        <flux:badge variant="solid" color="sky">
                                            {{ $admissionBatchName }}
                                        </flux:badge>
                                    @endif

                                    @if ($branchName)
                                        <flux:badge variant="solid" color="sky">
                                            {{ $branchName }}
                                        </flux:badge>
                                    @endif

                                    <a href="#" wire:click="resetFilter">
                                        <div class="flex gap-1 items-center hover:cursor-pointer">
                                            <flux:icon.x class="text-amber-400" variant="mini" />
                                            <flux:text class="text-amber-400">Reset Filter</flux:text>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <!--#Pill Badge Active Filter-->

                    </x-slot:action>

                    <!--NOTE: Student's Table-->
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

                        <x-tables.row wire:key="result{{ $student->id }}" :striped="true" :loop="$loop">
                            <x-tables.cell>
                                <flux:text>{{ $setCount++ }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>
                                    {{ $student->student_name }}
                                </flux:text>
                                <div class="flex items-center gap-1">
                                    <flux:text variant="soft" size="sm">
                                        {{ $student->batch_name }} - 
                                    </flux:text>
                                    <flux:text>
                                        <strong class="{{ $publicationColor }}">
                                            {{ $student->publication_status }}
                                        </strong>
                                    </flux:text>
                                </div>
                                
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>{{ $student->branch_name }}</flux:text>
                                <flux:text variant="soft" size="sm">{{ $student->program_name }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>{{ $student->psikotest_score ?? '-' }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>{{ $student->read_quran_score ?? '-' }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <div class="flex flex-col items-start gap-1">
                                    <flux:badge icon="users" color="{{ $parentInterviewColor }}" size="sm">
                                        {{ $student->parent_interview ?? '-' }}
                                    </flux:badge>

                                    <flux:badge icon="user" color="{{ $studentInterviewColor }}" size="sm">
                                        {{ $student->student_interview ?? '-' }}
                                    </flux:badge>
                                </div>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>{{ $student->final_score ?? '-' }}</flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <flux:text>
                                    <strong class="{{ $finalResultColor }}">
                                        {{ $student->final_result }}
                                    </strong>
                                </flux:text>
                            </x-tables.cell>

                            <x-tables.cell>
                                <a x-on:click.stop>
                                    <flux:dropdown offset="-5" gap="1">
                                        <flux:button variant="ghost" size="xs" class="hover:cursor-pointer">
                                            <flux:icon.ellipsis-vertical variant="mini" class="text-white" />
                                        </flux:button>
                                        <flux:menu wire:key="menu{{ $student->id }}">
                                            <flux:modal.trigger name="detail-test-result-modal">
                                                <flux:menu.item icon="eye" wire:click="$dispatch('open-detail-test-result-modal', {
                                                id: '{{ Crypt::encrypt($student->id) }}' })">
                                                    Detail
                                                </flux:menu.item>
                                            </flux:modal.trigger>

                                            <flux:menu.item icon="file-pen-line" wire:click="formEditScore( '{{ Crypt::encrypt($student->id) }}' )">
                                                Edit Nilai
                                            </flux:menu.item>

                                            <flux:modal.trigger name="publication-modal">
                                                <flux:menu.item icon="megaphone" wire:click="$dispatch('open-publication-modal', {
                                                id: '{{ Crypt::encrypt($student->test_id) }}' })">
                                                    Publikasi
                                                </flux:menu.item>
                                            </flux:modal.trigger>
                                        </flux:menu>
                                    </flux:dropdown>
                                </a>
                            </x-tables.cell>
                        </x-tables.row>
                    @empty
                        <x-tables.empty text="Tidak ada data yang ditemukan" :colspan="9" />
                    @endforelse
                    <!--#Student's Table-->

                    @if ($this->testResultStudents->hasPages())
                        <x-slot:pagination>
                            {{ $this->testResultStudents->links(data: ['scrollTo' => false]) }}
                        </x-slot:pagination>
                    @endif
                </x-tables.basic-table>
            {{-- </x-animations.fade-down> --}}
        </div>
    </div>

    <!--ANCHOR - FLYOUT MODAL FILTER STUDENT-->
    <flux:modal name="filter-student-modal" variant="flyout" class="w-3/12" closable="true">
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
                </div>
            </div>
            <!--#Button Action-->
        </div>
    </flux:modal>
    <!--#MODAL FILTER STUDENT-->

    <!--ANCHOR: PUBLICATION MODAL-->
    <livewire:components.modals.placement-test.set-publication-test-result-modal modalId="publication-modal"/>
    <!--#PUBLICATION MODAL-->

    <!--ANCHOR: DETAIL TEST RESULT MODAL-->
    <livewire:components.modals.placement-test.detail-test-result-modal modalId="detail-test-result-modal" :$isMobile/>
    <!--#DETAIL TEST RESULT MODAL-->

    <!--ANCHOR: CONFIRM RELEASE TEST MODAL-->
    <livewire:components.modals.placement-test.confirm-release-modal modalId="confirm-release-modal" :$isMobile/>
    <!--#CONFIRM RELEASE TEST MODAL-->
</div>
