@props(['payment', 'paymentAccount', 'evidence' => null, 'mobile' => false])

@php
    $isValid = $payment->payment_status === \App\Enums\VerificationStatusEnum::VALID;
    $isProcessing = $payment->payment_status === \App\Enums\VerificationStatusEnum::PROCESS && $payment->evidence;
    $bankName = $paymentAccount['bank_name'];
    $accountNumber = $paymentAccount['account_number'];
    $accountName = $paymentAccount['account_name'];
    $branchPhone = preg_replace('/\D+/', '', (string) $payment->branch_mobile_phone);
    if (str_starts_with($branchPhone, '0')) {
        $branchPhone = '62' . substr($branchPhone, 1);
    }
    $adminMessage = "Halo Admin, saya sudah transfer biaya pendaftaran atas nama *{$payment->student_name}*. Mohon untuk ditindaklanjuti, terima Kasih";
@endphp

<x-cards.soft-glass-card>
    <div @class(['grid grid-cols-1 items-start gap-6', 'lg:grid-cols-2 lg:gap-8' => ! $mobile])>
        <div class="flex min-w-0 flex-col gap-5">
            <div class="flex flex-col gap-2">
                <flux:heading variant="bold" size="xl">{{ $isValid ? 'Pembayaran Berhasil' : 'Pembayaran Biaya Pendaftaran' }}</flux:heading>
                <flux:text variant="soft">{{ $payment->student_name }} · {{ $payment->branch_name }} · {{ $payment->program_name }}</flux:text>
                <div>
                    <flux:badge color="{{ $isValid ? 'green' : ($isProcessing ? 'amber' : 'zinc') }}">{{ $payment->payment_status ?? 'Belum' }}</flux:badge>
                </div>
            </div>

            <div class="flex flex-col gap-1 rounded-xl bg-white/10 p-4">
                <flux:text variant="soft">Biaya Pendaftaran</flux:text>
                <flux:heading variant="bold" size="xxl">{{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($payment->registration_fee) }}</flux:heading>
            </div>

            @if (! $isValid && ! $isProcessing)
                @if ($payment->payment_status === \App\Enums\VerificationStatusEnum::INVALID)
                    <x-notifications.basic-alert>
                        <x-slot:title>Bukti Transfer Tidak Valid</x-slot:title>
                        <x-slot:subTitle>{{ $payment->admissionVerification?->payment_error_msg ?: 'Silakan unggah ulang bukti transfer yang benar dan terbaca jelas.' }}</x-slot:subTitle>
                    </x-notifications.basic-alert>
                @elseif ($payment->payment_status === \App\Enums\PaymentStatusEnum::EXPIRED)
                    <x-notifications.basic-alert variant="warning" icon="clock">
                        <x-slot:title>Pembayaran sebelumnya sudah kedaluwarsa.</x-slot:title>
                        <x-slot:subTitle>Jika sudah melakukan transfer, silakan unggah bukti transfer untuk diverifikasi admin.</x-slot:subTitle>
                    </x-notifications.basic-alert>
                @endif

                <div class="flex flex-col gap-3">
                    <flux:heading variant="bold">Instruksi Transfer</flux:heading>
                    @if ($bankName && $accountNumber && $accountName)
                        <flux:text variant="soft">Transfer sesuai nominal biaya pendaftaran ke rekening berikut, lalu unggah bukti transfer.</flux:text>
                        <dl class="flex flex-col gap-2 rounded-xl bg-white/10 p-4 text-white">
                            <div class="flex flex-wrap justify-between gap-2"><dt>Bank</dt><dd class="font-semibold">{{ $bankName }}</dd></div>
                            <div class="flex flex-wrap items-center justify-between gap-2" x-data="{ copied: false, copyError: false }">
                                <dt>Nomor Rekening</dt>
                                <dd class="flex max-w-full flex-wrap items-center gap-2">
                                    <span x-ref="accountNumber" class="break-all font-semibold select-all">{{ $accountNumber }}</span>
                                    <flux:button
                                        type="button"
                                        aria-label="Salin nomor rekening"
                                        x-on:click="
                                            copyError = false;
                                            try {
                                                await navigator.clipboard.writeText($refs.accountNumber.textContent.trim());
                                                copied = true;
                                                setTimeout(() => copied = false, 2000);
                                            } catch (error) {
                                                copied = false;
                                                copyError = true;
                                            }
                                        "
                                    >
                                        <span x-text="copied ? 'Tersalin' : 'Salin'">Salin</span>
                                    </flux:button>
                                    <span class="sr-only" role="status" x-text="copied ? 'Nomor rekening berhasil disalin.' : ''"></span>
                                    <p x-cloak x-show="copyError" role="alert" class="w-full text-sm text-white">Tidak dapat menyalin otomatis. Pilih nomor rekening lalu salin secara manual.</p>
                                </dd>
                            </div>
                            <div class="flex flex-wrap justify-between gap-2"><dt>Atas Nama</dt><dd class="font-semibold">{{ $accountName }}</dd></div>
                        </dl>
                    @else
                        <flux:text variant="soft">Hubungi admin untuk mendapatkan rekening tujuan. Setelah transfer sesuai nominal biaya pendaftaran, unggah bukti transfer melalui formulir yang tersedia.</flux:text>
                        @if (config('services.whatsapp.phone'))
                            <flux:button icon="phone" href="https://wa.me/{{ config('services.whatsapp.phone') }}" target="_blank" rel="noopener noreferrer">Hubungi Admin</flux:button>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        <div class="flex min-w-0 flex-col gap-5">
            @if (session('upload-payment-success'))
                <x-notifications.basic-alert variant="success" icon="check-circle">
                    <x-slot:title>{{ session('upload-payment-success') }}</x-slot:title>
                </x-notifications.basic-alert>
            @endif

            @if (session('upload-payment-failed'))
                <x-notifications.basic-alert>
                    <x-slot:title>{{ session('upload-payment-failed') }}</x-slot:title>
                </x-notifications.basic-alert>
            @endif

            @if ($isValid)
                <flux:text variant="soft">Pembayaran Anda telah diverifikasi. Silakan melanjutkan pengisian biodata dan melengkapi berkas.</flux:text>
                <div class="flex flex-wrap gap-3">
                    <flux:button variant="primary" icon="contact-round" href="{{ route('student.admission_data.biodata') }}" wire:navigate>Isi Biodata</flux:button>
                    <flux:button variant="primary" icon="file-badge" href="{{ route('student.admission_data.admission_attachment') }}" wire:navigate>Lengkapi Berkas</flux:button>
                </div>
            @elseif ($isProcessing)
                <x-notifications.basic-alert variant="warning" icon="clock">
                    <x-slot:title>Menunggu Verifikasi Admin</x-slot:title>
                    <x-slot:subTitle>Bukti transfer sudah diterima dan sedang diperiksa. Status akan diperbarui setelah verifikasi selesai.</x-slot:subTitle>
                </x-notifications.basic-alert>
                <div class="flex flex-wrap gap-3">
                    {{-- $refresh sends a commit without a method name; an empty target avoids inferring $refresh. --}}
                    <flux:button wire:click="$refresh" :loading="false" wire:loading.attr="disabled" wire:target="">
                        <flux:icon.refresh-cw variant="micro" wire:loading.remove />
                        <flux:icon.loading variant="micro" wire:loading />
                        <span wire:loading.remove>Perbarui Status</span>
                        <span wire:loading role="status">Memperbarui Status...</span>
                    </flux:button>
                    @if ($branchPhone !== '')
                        <flux:button icon="phone" href="https://wa.me/{{ $branchPhone }}?text={{ rawurlencode($adminMessage) }}" target="_blank" rel="noopener noreferrer">Hubungi Admin</flux:button>
                    @endif
                </div>
            @else
                <form wire:submit="saveEvidence" class="flex flex-col gap-4">
                    <flux:heading variant="bold">Lampirkan Bukti Transfer</flux:heading>
                    <flux:field>
                        <flux:label>Bukti Transfer</flux:label>
                        <flux:input type="file" wire:model="evidence" accept="image/jpeg,image/png,.jpg,.jpeg,.png" />
                        <flux:description class="!text-white/75">Format JPG, JPEG, atau PNG. Ukuran maksimal 5 MB. Pastikan nominal, tanggal, dan rekening tujuan terbaca jelas.</flux:description>
                        <flux:error name="evidence" />
                    </flux:field>
                    <flux:text wire:loading wire:target="evidence" role="status" variant="soft">Mengunggah gambar, mohon tunggu...</flux:text>
                    @if ($evidence && !$errors->has('evidence'))
                        <img src="{{ $evidence->temporaryUrl() }}" alt="Preview bukti transfer yang akan dikirim" class="max-h-80 w-full rounded-xl object-contain" />
                    @endif
                    <flux:button type="submit" variant="primary" icon="upload" wire:loading.attr="disabled" wire:target="evidence,saveEvidence">
                        <span wire:loading.remove wire:target="saveEvidence">Kirim Bukti Transfer</span>
                        <span wire:loading wire:target="saveEvidence">Menyimpan...</span>
                    </flux:button>
                </form>
            @endif

            @if ($payment->evidence)
                <flux:modal.trigger name="previous-payment-evidence">
                    <flux:button type="button" icon:trailing="eye" class="w-full">Bukti Transfer Sebelumnya</flux:button>
                </flux:modal.trigger>
            @endif
        </div>
    </div>
</x-cards.soft-glass-card>

@if ($payment->evidence)
    <flux:modal name="previous-payment-evidence" :closable="true" class="w-full max-w-2xl max-h-[90dvh] overflow-y-auto">
        <div class="flex flex-col gap-4">
            <flux:heading variant="bold" size="xl" class="pe-8">Bukti Transfer Sebelumnya</flux:heading>
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($payment->evidence) }}" alt="Bukti transfer biaya pendaftaran {{ $payment->student_name }}" class="max-h-[70dvh] w-full rounded-xl object-contain" />
        </div>
    </flux:modal>
@endif
