<flux:modal name="verify-payment" class="w-full max-w-xl">
    @if ($this->selectedPayment)
        <form wire:submit="verifyPayment" class="flex flex-col gap-4">
            <flux:heading size="xl">Verifikasi Bukti Transfer</flux:heading>
            <flux:text>{{ $this->selectedPayment->student->name }} · {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($this->selectedPayment->amount) }}</flux:text>
            <x-animations.fancybox>
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($this->selectedPayment->evidence) }}" data-fancybox="registration-payment-verification-{{ $this->selectedPayment->student_id }}" data-caption="Bukti transfer {{ $this->selectedPayment->student->name }}" aria-label="Perbesar bukti transfer {{ $this->selectedPayment->student->name }}">
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($this->selectedPayment->evidence) }}" alt="Bukti transfer {{ $this->selectedPayment->student->name }}" class="max-h-96 w-full rounded-xl object-contain" />
                </a>
            </x-animations.fancybox>
            <flux:select label="Hasil Verifikasi" wire:model.live="paymentStatus" placeholder="Pilih status">
                <flux:select.option value="Valid">Valid</flux:select.option>
                <flux:select.option value="Tidak Valid">Tidak Valid</flux:select.option>
            </flux:select>
            @if ($this->paymentStatus === \App\Enums\VerificationStatusEnum::INVALID)
                <flux:textarea label="Alasan Penolakan" wire:model="invalidReason" rows="3" maxlength="500" />
            @endif
            <flux:error name="invalidReason" />
            @if (session('verify-payment-failed'))
                <x-notifications.basic-alert>
                    <x-slot:title>{{ session('verify-payment-failed') }}</x-slot:title>
                </x-notifications.basic-alert>
            @endif
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="verifyPayment">Simpan Verifikasi</flux:button>
        </form>
    @endif
</flux:modal>
