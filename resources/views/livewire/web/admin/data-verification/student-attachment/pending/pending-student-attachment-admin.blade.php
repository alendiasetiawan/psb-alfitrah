<div>
    <x-navigations.breadcrumb>
        <x-slot:title>Belum Upload Berkas</x-slot:title>
        <x-slot:activePage>Verifikasi Belum Upload Berkas</x-slot:activePage>
    </x-navigations.breadcrumb> 

    <!--ANCHOR: SEARCH AND FILTER-->
    <x-animations.fade-down>
        <div class="grid grid-cols-2 mt-4 justify-between items-center gap-2">
            <div class="flex gap-2">
                <div class="w-4/6">
                    <flux:input
                        icon="search"
                        placeholder="Cari nama santri"
                        wire:model.live.debounce.500ms="searchStudent"
                    />
                </div>

                <div class="w-2/6">
                    <flux:select wire:model.live="selectedAdmissionId" >
                        @foreach ($admissionYearLists as $key => $value)
                            <flux:select.option value="{{ $key }}">{{ $value }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <div class="flex justify-end">
                <flux:badge variant="solid" color="primary" icon="user">
                    {{ $this->totalPendingAttachment }} Santri
                </flux:badge>
            </div>
        </div>
    </x-animations.fade-down>
    <!--#SEARCH AND FILTER-->

    <!--ANCHOR: STUDENT CARD-->
    <x-animations.fade-down showTiming="150">
        <!--NOTE: Alert When Send Message Follow Up Failed-->
        @if (session('error-fu-attachment'))
            <div class="grid grid-cols-1 mt-4">
                    <x-notifications.basic-alert isCloseable="true">
                        <x-slot:title>{{ session('error-fu-attachment') }}</x-slot:title>
                    </x-notifications.basic-alert>
            </div>
        @endif
        <!--#Alert When Send Message Follow Up Failed-->

        <!--NOTE: Loading Indicator When Filter Apply-->
        <div class="flex items-center justify-center">
            <div wire:loading wire:target="searchStudent, selectedAdmissionId">
                <x-loading.horizontal-dot/>
            </div>
        </div>
        <!--#Loading Indicator When Filter Apply-->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <!--ANCHOR: Looping student biodata-->
            @forelse ($this->pendingAttachmentStudents as $student)
                <div class="col-span-1" wire:key="student-{{ $student->id }}">
                    <x-cards.flat-card
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
                    >
                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->username }} | {{ $student->gender }}</x-slot:subHeading>
                        <x-slot:label>
                            <div wire:loading wire:target="fuAttachment({{ $student->id }})">
                                <flux:icon.loading variant="mini" class="text-amber-400"/>
                            </div>
                            <div wire:loading.remove wire:target="fuAttachment({{ $student->id }})">
                                <x-items.wa-icon width="25" height="25" wire:click="fuAttachment({{ $student->id }})"/>
                            </div>
                        </x-slot:label>

                        <flux:text variant="soft">Daftar: 
                            <strong class="text-white">
                                {{ \App\Helpers\DateFormatHelper::shortIndoDate($student->registration_date) }}
                                ({{ \App\Helpers\DateFormatHelper::humanReadIndo($student->registration_date) }})
                            </strong>
                        </flux:text>

                        <div class="flex justify-between items-center">
                            <flux:text variant="soft">Follow Up: 
                                @if ($student->fu_attachment == \App\Enums\FollowUpStatusEnum::NOT_YET)
                                    <strong class="text-amber-400">Belum</strong>
                                @else
                                    <strong class="text-green-400">Sudah</strong>
                                @endif
                            </flux:text>

                            <div class="flex items-end">
                                <flux:text variant="soft">Bayar:
                                @if ($student->registration_payment == \App\Enums\VerificationStatusEnum::VALID)
                                    <strong class="text-green-400">Sudah</strong>
                                @else
                                    <strong class="text-amber-400">Belum</strong>
                                @endif
                                </flux:text>
                            </div>
                        </div>
                        
                        <x-slot:subContent>
                            <flux:badge color="primary" icon="school" size="sm">{{ $student->branch_name }}</flux:badge>
                            <flux:badge color="primary" icon="graduation-cap" size="sm">{{ $student->program_name }}</flux:badge>
                        </x-slot:subContent>
                        
                    </x-cards.flat-card>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3">
                    <x-animations.not-found />
                </div>
            @endforelse
            <!--#Looping student biodata-->
        </div>

        <div class="grid grid-cols-1 mt-3">
            <!--NOTE: Load More Button-->
            @if ($this->pendingAttachmentStudents->hasMorePages())
                <livewire:components.buttons.load-more loadItem="18" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>
    <!--#STUDENT CARD-->
</div>
