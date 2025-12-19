<div class="mb-18">
    <!--ANCHOR - Sticky Search and Filter Section -->
    <x-animations.sticky>
        <x-animations.fade-down showTiming="50">
            <!--NOTE: Input Search-->
            <div class="grid grid-cols-1 mt-4">
                <div class="flex gap-2 items-center">
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
            <!--#Input Search-->

            <!--NOTE: Total Student-->
            <div class="flex justify-between items-center mt-2">
                <flux:badge variant="solid" color="primary" icon="user-check">
                    {{ $this->totalVerifiedBiodataStudent }}
                    Santri
                </flux:badge>
                <flux:heading size="md">{{ $admissionYear }}</flux:heading>
            </div>
            <!--#Total Student-->
        </x-animations.fade-down>
    </x-animations.sticky>

    <!--NOTE: Loading Indicator When Filter Apply-->
    <div class="flex items-center justify-center">
        <div wire:loading wire:target="searchStudent">
            <x-loading.horizontal-dot />
        </div>
    </div>
    <!--#Loading Indicator When Filter Apply-->

    <!--ANCHOR: STUDENT CARD-->
    <x-animations.fade-down showTiming="150">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @forelse ($this->verifiedStudentBiodatas as $student)
                <div class="col-span-1" wire:key="student-{{ $student->id }}">
                    <x-cards.flat-card wire:click="showStudentData('{{ Crypt::encrypt($student->id) }}')"
                        clickable="true"
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
                        avatarImage="{{ !empty($student->user_photo) ? asset($student->user_photo) : '' }}">
                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->username }} | {{ $student->gender }}</x-slot:subHeading>

                        <flux:text variant="soft">
                            NISN: <strong
                                class="text-white">{{ $student->nisn }}</strong>
                        </flux:text>

                        <flux:text variant="soft">
                            Asal Sekolah: <strong
                                class="text-white">{{ $student->old_school_name }}</strong>
                        </flux:text>

                        <flux:text variant="soft">
                            Tanggal Verifikasi: <strong
                                class="text-white">{{ \App\Helpers\DateFormatHelper::indoDateTime($student->biodata_verified_at) }}</strong>
                        </flux:text>

                        <x-slot:subContent>
                            <flux:badge color="primary" icon="school" size="sm">{{ $student->branch_name }}
                            </flux:badge>
                            <flux:badge color="primary" icon="graduation-cap" size="sm">
                                {{ $student->program_name }}</flux:badge>
                        </x-slot:subContent>

                    </x-cards.flat-card>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3">
                    <x-animations.not-found />
                </div>
            @endforelse
        </div>

        <div class="grid grid-cols-1 mt-3">
            <!--NOTE: Load More Button-->
            @if ($this->verifiedStudentBiodatas->hasMorePages())
                <livewire:components.buttons.load-more loadItem="18" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>
    <!--#STUDENT CARD-->


    <!--ANCHOR - FLYOUT MODAL FILTER STUDENT-->
    <flux:modal name="filter-student-modal" variant="flyout" class="w-11/12" closable="true">
        <flux:heading size="xl">Filter Data</flux:heading>
        <div class="space-y-4 mt-5">
            <!--Filter Academic Year-->
            <div class="grid grid-cols-1">
                <flux:select wire:model="filterAdmissionId" label="Pilih Tahun Ajaran">
                    @foreach ($admissionYearLists as $key => $value)
                        <flux:select.option value="{{ $key }}">{{ $value }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <!--#Filter Academic Year-->

            <!--Button Action-->
            <div class="fixed bottom-0 left-0 right-0 p-6">
                <flux:modal.close>
                    <flux:button variant="primary" class="w-full" wire:click="setSelectedAdmissionId">Terapkan
                    </flux:button>
                </flux:modal.close>
            </div>
            <!--#Button Action-->
        </div>
    </flux:modal>
    <!--#MODAL FILTER STUDENT-->
</div>
