<div class="mb-18">
    <!--ANCHOR - Sticky Search and Filter Section -->
    <x-animations.fixed-top :title="$title" :link="$link">
        <x-navigations.flat-tab>
            <x-navigations.flat-tab-item href="admin.data_verification.biodata.pending" label="Belum" />
            <x-navigations.flat-tab-item href="admin.data_verification.biodata.process" label="Proses" :isActive="true" />
            <x-navigations.flat-tab-item href="admin.data_verification.biodata.verified" label="Valid" />
        </x-navigations.flat-tab>

        <div class="grid grid-cols-1 mt-3">
            <flux:input placeholder="Cari nama siswa" wire:model.live.debounce.500ms="searchStudent" icon="search" />
        </div>

        <div class="flex justify-between mt-2">
            <flux:badge variant="solid" color="primary" icon="user-check">{{ $this->totalProcessBiodataStudent }} Santri
            </flux:badge>

            <flux:badge variant="solid" color="primary" icon="graduation-cap">{{ $admissionYear }}</flux:badge>
        </div>
    </x-animations.fixed-top>

    <div class="h-40"></div>

    @if (session('notification-failed'))
        <div class="grid grid-cols-1 mt-4">
            <div class="col-span-1">
                <x-notifications.basic-alert :isCloseable="true">
                    <x-slot:title>
                        {{ session('notification-failed') }}
                    </x-slot:title>
                </x-notifications.basic-alert>
            </div>
        </div>
    @endif

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
            @forelse ($this->processBiodataStudents as $student)
                <div class="col-span-1" wire:key="student-{{ $student->id }}">
                    <x-cards.flat-card wire:click="verifyStudent('{{ Crypt::encrypt($student->id) }}')" clickable="true"
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
                        avatarImage="{{ !empty($student->user_photo) ? asset($student->user_photo) : '' }}">
                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->username }} | {{ $student->gender }}</x-slot:subHeading>

                        <x-slot:label>
                            @if ($student->biodata == \App\Enums\VerificationStatusEnum::PROCESS)
                                <flux:badge color="orange" size="sm" variant="solid">Proses</flux:badge>
                            @else
                                <flux:badge color="red" size="sm" variant="solid">Invalid</flux:badge>
                            @endif
                        </x-slot:label>

                        <flux:text variant="soft">
                            Tanggal Update: <strong
                                class="text-white">{{ \App\Helpers\DateFormatHelper::indoDateTime($student->modified_at) }}</strong>
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
            @if ($this->processBiodataStudents->hasMorePages())
                <livewire:components.buttons.load-more loadItem="18" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>
    <!--#STUDENT CARD-->
</div>
