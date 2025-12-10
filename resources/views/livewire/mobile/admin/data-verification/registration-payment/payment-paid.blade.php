<div class="mb-18"> 
    <!--ANCHOR - COUNTER STUDENT STATISTIC-->
    <x-animations.fade-down showTiming="50">
        <div wire:replace>
            <x-swipers.creative-swiper>
                <x-cards.counter-card subCounterColor="soft-white" class="swiper-slide" style="width: 85vw;">
                    <x-slot:heading>
                        Total Pemasukan
                    </x-slot:heading>
                    <x-slot:mainCounter>
                        {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($this->totalIncome['sumPayment']) }}
                    </x-slot:mainCounter>
                    <x-slot:subIcon>
                        <flux:icon.hand-coins class="size-15 text-primary-400" />
                    </x-slot:subIcon>
                </x-cards.counter-card>

                <x-cards.counter-card subCounterColor="soft-white" class="swiper-slide" style="width: 85vw;">
                    <x-slot:heading>
                        Total Santri
                    </x-slot:heading>
                    <x-slot:mainCounter>
                        {{ $this->totalIncome['totalStudent'] }}
                    </x-slot:mainCounter>
                    <x-slot:subCounter>
                        Santri
                    </x-slot:subCounter>
                    <x-slot:subIcon>
                        <flux:icon.users class="size-15 text-primary-400" />
                    </x-slot:subIcon>
                </x-cards.counter-card>
            </x-swipers.creative-swiper>
        </div>
    </x-animations.fade-down>
    <!--#COUNTER STUDENT STATISTIC-->

    <!--ANCHOR: SEARCH AND FILTER-->
    <x-animations.sticky scrollHeight="160">
        <x-animations.fade-down showTiming="150">
            <div class="grid grid-cols-1 mt-4">
                <div class="flex gap-2 items-center">
                    <div class="w-11/12">
                        <flux:input placeholder="Cari nama/nomor HP santri" wire:model.live.debounce.500ms="searchStudent"
                            icon="search" />
                    </div>

                    <div class="w-1/12">
                        <flux:modal.trigger name="filter-student-modal">
                            <flux:icon.sliders-horizontal class="hover:cursor-pointer text-primary-400" />
                        </flux:modal.trigger>
                    </div>
                </div>
            </div>
        </x-animations.fade-down>
    </x-animations.sticky>
    <!--#SEARCH AND FILTER-->

    <!--ANCHOR - LIST OF STUDENTS-->
    <x-animations.fade-down showTiming="150">
        <div class="grid grid-cols-1 gap-3 mt-4">
            <!--ANCHOR - Loading Skeleton-->
            <div class="col-span-1" wire:loading>
                <div class="animate-pulse">
                    <x-loading.profile-card-skeleton />
                </div>
            </div>
            <!--#Loading Skeleton-->

            @forelse ($this->paidStudentLists as $student)
                <div class="col-span-1" wire:loading.remove>
                    <x-cards.flat-card
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}">

                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->username }} | {{ $student->gender }}</x-slot:subHeading>
                        
                        <flux:text variant="soft">
                            Tanggal Pembayaran: 
                            <strong class="text-white">{{ \App\Helpers\DateFormatHelper::indoDateTime($student->registrationInvoices[0]->paid_at) }}</strong>
                        </flux:text>

                        <flux:text variant="soft">
                            Pembayaran Via: <strong class="text-white">{{ $student->registrationInvoices[0]->payment_method }}</strong>
                        </flux:text>
                        
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
                <x-animations.not-found />
            @endforelse

            <!--ANCHOR - Load More Button-->
            @if ($this->paidStudentLists->hasMorePages())
                <livewire:components.buttons.load-more loadItem="20" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>
    <!--#LIST OF STUDENTS-->

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
                    <flux:button variant="primary" class="w-full" wire:click="setSelectedAdmissionId">Terapkan</flux:button>
                </flux:modal.close>
            </div>
            <!--#Button Action-->
        </div>
    </flux:modal>
    <!--#MODAL FILTER STUDENT-->
</div>
