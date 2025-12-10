<div>
    <!--ANCHOR - Sticky Search and Filter Section -->
    <x-animations.sticky>
        <x-animations.fade-down showTiming="50">    
                <div class="grid grid-cols-1">
                    <flux:input placeholder="Cari nama siswa" wire:model.live.debounce.500ms="searchStudent" icon="search" />
                </div>

                <div class="flex justify-between mt-2">
                    <flux:badge variant="solid" color="primary" icon="user-check">Jumlah : {{ $this->totalProcessStudent }}</flux:badge>
                </div>
        </x-animations.fade-down>
    </x-animations.sticky>

    <!--ANCHOR: STUDENT CARD-->
    <x-animations.fade-down showTiming="150">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @forelse ($this->processStudentLists as $student)
                <div class="col-span-1">
                    <x-cards.flat-card
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}">
                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->username }} | {{ $student->gender }}</x-slot:subHeading>
                        @if ($student->registrationInvoices[0]->expiry_date < now())
                            <x-slot:label>
                                <x-items.wa-icon width="25" height="25" href="https://wa.me/{{ $student->country_code . $student->mobile_phone }}"/>
                            </x-slot:label>
                        @endif

                        <div x-data="countDown({ expiry_date: '{{ $student->registrationInvoices[0]->expiry_date }}' })">
                            <flux:text variant="soft">
                                Sisa Waktu Pembayaran:
                                @if ($student->registrationInvoices[0]->expiry_date < now())
                                    <strong class="text-white">Habis</strong>
                                @else
                                    <strong x-text="timeString" class="text-amber-400"></strong>
                                @endif
                            </flux:text>
                        </div>
                        
                        <x-slot:subContent>
                            <flux:badge color="primary" icon="school" size="sm">{{ $student->branch_name }}</flux:badge>
                            <flux:badge color="primary" icon="graduation-cap" size="sm">{{ $student->program_name }}</flux:badge>
                        </x-slot:subContent>

                        <x-slot:highlight>
                            Rp {{ \App\Helpers\FormatCurrencyHelper::convertCurrency($student->registration_fee) }}
                        </x-slot:highlight>
                        
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
            @if ($this->processStudentLists->hasMorePages())
                <livewire:components.buttons.load-more loadItem="18" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>
    <!--#STUDENT CARD-->
</div>
