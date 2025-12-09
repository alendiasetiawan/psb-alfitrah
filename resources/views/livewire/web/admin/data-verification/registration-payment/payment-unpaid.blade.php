<div>
    <x-navigations.breadcrumb secondLink="{{ route('admin.master_data.student_database.index') }}">
        <x-slot:title>{{ __('Belum Bayar Pendaftaran') }}</x-slot:title>
        <x-slot:activePage>{{ __('Verifikasi Belum Bayar Biaya Pendaftaran') }}</x-slot:activePage>
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
                    Jumlah: {{ $this->totalStudent }}
                </flux:badge>
            </div>
        </div>
    </x-animations.fade-down>
    <!--#SEARCH AND FILTER-->

    <!--ANCHOR: STUDENT CARD-->
    <x-animations.fade-down showTiming="150">
        <!--NOTE: Alert When Send Message Follow Up Failed-->
        @if (session('error-fu-payment'))
            <div class="grid grid-cols-1 mt-4">
                    <x-notifications.basic-alert isCloseable="true">
                        <x-slot:title>{{ session('error-fu-payment') }}</x-slot:title>
                    </x-notifications.basic-alert>
            </div>
        @endif
        <!--#Alert When Send Message Follow Up Failed-->

        <!--NOTE: Loading Indicator When Filter Apply-->
        <div class="flex items-center justify-center">
            <div wire:loading wire:target.except="fuPayment">
                <x-loading.horizontal-dot/>
            </div>
        </div>
        <!--#Loading Indicator When Filter Apply-->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
            @forelse ($this->notPaidStudentLists as $student)
                <div class="col-span-1">
                    <x-cards.flat-card
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
                    >
                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->username }}</x-slot:subHeading>
                        <x-slot:label>
                            <div wire:loading wire:target="fuPayment({{ $student->id }})">
                                <flux:icon.loading variant="mini" class="text-amber-400"/>
                            </div>
                            <div wire:loading.remove wire:target="fuPayment({{ $student->id }})">
                                <x-items.wa-icon width="25" height="25" wire:click="fuPayment({{ $student->id }})"/>
                            </div>
                        </x-slot:label>

                        <flux:text variant="soft">Tanggal Daftar: 
                            <strong class="text-white">{{ \App\Helpers\DateFormatHelper::indoDateTime($student->registration_date) }}</strong>
                        </flux:text>
                        <flux:text variant="soft">Follow Up: 
                            @if ($student->fu_payment == \App\Enums\FollowUpStatusEnum::NOT_YET)
                                <strong class="text-amber-400">Belum</strong>
                            @else
                                <strong class="text-green-400">Sudah</strong>
                            @endif
                        </flux:text>
                        
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
        </div>

        <div class="grid grid-cols-1 mt-3">
            <!--NOTE: Load More Button-->
            @if ($this->notPaidStudentLists->hasMorePages())
                <livewire:components.buttons.load-more loadItem="18" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>
    <!--#STUDENT CARD-->
</div>
