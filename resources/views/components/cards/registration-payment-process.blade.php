@props(['student'])

<x-cards.flat-card
    avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
    labelClass="self-start shrink-0"
    actionButtonMargin="mt-1"
>
    <x-slot:heading>{{ $student->student_name }}</x-slot:heading>
    <x-slot:subHeading>{{ $student->username }} | {{ $student->gender }}</x-slot:subHeading>
    <x-slot:label>
        <flux:badge color="{{ $student->payment_status === \App\Enums\VerificationStatusEnum::PROCESS ? 'amber' : 'red' }}">{{ $student->payment_status }}</flux:badge>
    </x-slot:label>

    @if (! $student->evidence)
        <flux:text variant="soft">Belum ada bukti transfer. Minta siswa mengunggah bukti pembayaran.</flux:text>
    @elseif ($student->payment_status !== \App\Enums\VerificationStatusEnum::PROCESS && $student->payment_error_msg)
        <flux:text variant="soft" size="sm">{{ $student->payment_error_msg }}</flux:text>
    @endif

    <x-slot:subContent>
        <flux:badge color="primary" icon="school" size="sm">{{ $student->branch_name }}</flux:badge>
        <flux:badge color="primary" icon="graduation-cap" size="sm">{{ $student->program_name }}</flux:badge>
    </x-slot:subContent>

    <x-slot:highlight>
        Rp {{ \App\Helpers\FormatCurrencyHelper::convertCurrency($student->registration_fee) }}
    </x-slot:highlight>

    @if ($student->evidence && $student->payment_status === \App\Enums\VerificationStatusEnum::PROCESS)
        <x-slot:actionButton>
            <flux:button variant="primary" class="w-full" wire:click="openVerification({{ $student->id }})">Verifikasi Pembayaran</flux:button>
        </x-slot:actionButton>
    @endif
</x-cards.flat-card>
