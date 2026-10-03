<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Proses Bayar Pendaftaran') }}</x-slot:title>
        <x-slot:activePage>{{ __('Verifikasi Proses Bayar Pendaftaran') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--ANCHOR: SEARCH AND FILTER-->
        <div class="grid grid-cols-2 mt-4 justify-between items-center gap-2">
            <div class="flex gap-2">
                <div class="w-4/6">
                    <flux:input
                        icon="search"
                        placeholder="Cari nama santri"
                        wire:model.live.debounce.500ms="searchStudent"
                    />
                </div>
            </div>

            <div class="flex justify-end">
                <flux:badge variant="solid" color="primary" icon="user">
                    Jumlah: {{ $this->totalProcessStudent }}
                </flux:badge>
            </div>
        </div>
    <!--#SEARCH AND FILTER-->

    <!--ANCHOR: STUDENT CARD-->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @forelse ($this->processStudentLists as $student)
                <div class="col-span-1" wire:key="student-{{ $student->id }}">
                    <x-cards.flat-card
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
                    >
                        <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
                        <x-slot:subHeading>{{ $student->username }} | {{ $student->gender }}</x-slot:subHeading>
                        <div class="flex flex-col gap-3">
                            <div>
                                <flux:badge color="{{ $student->payment_status === \App\Enums\VerificationStatusEnum::PROCESS ? 'amber' : 'red' }}">{{ $student->payment_status }}</flux:badge>
                            </div>
                            @if ($student->evidence)
                                <flux:button size="sm" icon="photo" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($student->evidence) }}" target="_blank" rel="noopener noreferrer">Lihat Bukti Transfer</flux:button>
                                @if ($student->payment_status === \App\Enums\VerificationStatusEnum::PROCESS)
                                    <flux:button variant="primary" size="sm" wire:click="openVerification({{ $student->id }})">Verifikasi Pembayaran</flux:button>
                                @elseif ($student->payment_error_msg)
                                    <flux:text variant="soft" size="sm">{{ $student->payment_error_msg }}</flux:text>
                                @endif
                            @else
                                <flux:text variant="soft">Belum ada bukti transfer. Minta siswa mengunggah bukti pembayaran.</flux:text>
                            @endif
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
    <!--#STUDENT CARD-->
    <x-modals.registration-payment-verification />
</div>
