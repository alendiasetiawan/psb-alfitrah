<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Pembayaran') }}</x-slot:title>
        <x-slot:activePage>{{ __('Pembayaran Biaya Pendaftaran') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="flex justify-center mt-4">
        <div class="md:w-4/6 lg:w-3/6 w-full">
            <x-animations.fade-down showTiming="50">
                @if ($this->detailPayment->payment_status == \App\Enums\VerificationStatusEnum::VALID)
                    <!--PAYMENT SUCCESS-->
                    <x-cards.soft-glass-card>
                        <div class="flex flex-col items-center mb-2">
                            <img src="{{ asset('images/background/payment.png') }}" width="300" height="auto" />
                            <flux:heading variant="bold" size="xxl" class="mt-2">Pembayaran Berhasil</flux:heading>
                        </div>
                        <flux:text variant="soft">
                            Terima kasih atas pembayaran anda, berikut informasi selengkapnya :
                        </flux:text>

                        <div class="flex justify-between mt-4 mb-2">
                            <flux:text variant="soft">Nominal</flux:text>
                            <flux:text variant="bold">
                                {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($this->detailPayment->registration_fee) }}
                            </flux:text>
                        </div>

                        <div class="flex justify-between mb-2">
                            <flux:text variant="soft">ID</flux:text>
                            <flux:text variant="bold">
                                {{ $this->detailPayment->registrationInvoices[0]->invoice_id }}
                            </flux:text>
                        </div>

                        <div class="flex justify-between mb-2">
                            <flux:text variant="soft">Pembayaran Via</flux:text>
                            <flux:text variant="bold">
                                {{ $this->detailPayment->registrationInvoices[0]->payment_method ?? 'Transfer' }}
                            </flux:text>
                        </div>

                        <div class="flex justify-between mb-4">
                            <flux:text variant="soft">Waktu</flux:text>
                            <flux:text variant="bold">
                                {{ \App\Helpers\DateFormatHelper::indoDateTime($this->detailPayment->registrationInvoices[0]->paid_at) ?? '-' }}
                            </flux:text>
                        </div>

                        <flux:text variant="soft">
                            Anda bisa melanjutkan tahapan proses penerimaan siswa baru, silahkan pilih menu di bawah ini :
                        </flux:text>

                        <div class="flex justify-center mx-auto gap-3 mt-4">
                            <flux:button 
                                variant="primary" 
                                size="base-circle" 
                                icon="contact-round"
                                href="{{ route('student.admission_data.biodata') }}"
                                wire:navigate
                                >
                                    Isi Biodata
                            </flux:button>

                            <flux:button 
                                variant="primary" 
                                size="base-circle" 
                                icon="file-badge"
                                href="{{ route('student.admission_data.admission_attachment') }}"
                                wire:navigate
                                >
                                    Lengkapi Berkas
                            </flux:button>
                        </div>
                    </x-cards.soft-glass-card>
                    <!--#PAYMENT SUCCESS-->
                @else
                    <x-cards.soft-glass-card>
                        <flux:heading variant="bold" size="xl" class="mb-2">Instruksi Pembayaran</flux:heading>

                        @if (session('create-invoice-failed'))
                            <!--Alert when create invoice failed-->
                            <x-notifications.basic-alert class="mb-2">
                                <x-slot:title>{{ session('create-invoice-failed') }}</x-slot:title>
                            </x-notifications.basic-alert>
                            <!--Alert when create invoice failed-->
                        @endif

                        @if ($isPendingPayment)
                            <!--When user have pending payment-->
                            <flux:text variant="soft">
                                Anda memiliki tagihan biaya pendaftaran yang belum dibayar sebagai berikut :
                            </flux:text>

                            <div class="flex justify-between mt-4 mb-2">
                                <flux:text variant="soft">Nominal </flux:text>
                                <flux:text variant="bold">
                                    {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($this->detailPayment->registrationInvoices[0]->amount) }}
                                </flux:text>
                            </div>
                            <div class="flex justify-between mb-2">
                                <flux:text variant="soft">Waktu Checkout</flux:text>
                                <flux:text variant="bold">
                                    {{ \App\Helpers\DateFormatHelper::indoDateTime($this->detailPayment->registrationInvoices[0]->created_at) }}
                                </flux:text>
                            </div>
                            <div class="flex justify-between mb-2">
                                <flux:text variant="soft">Batas Waktu</flux:text>
                                <flux:text variant="bold">
                                    {{ \App\Helpers\DateFormatHelper::indoDateTime($this->detailPayment->registrationInvoices[0]->expiry_date) }}
                                </flux:text>
                            </div>
                            <div class="flex justify-between mb-4">
                                <flux:text variant="soft">Sisa Waktu</flux:text>
                                <div x-data="countDown({ expiry_date: '{{ $this->detailPayment->registrationInvoices[0]->expiry_date }}' })">
                                    <flux:badge variant="solid" color="red">
                                        <span x-text="timeString"></span>
                                    </flux:badge>
                                </div>
                            </div>

                            <flux:text variant="soft">Segera selesaikan pembayaran anda sebelum waktu habis, apabila ada kendala atau kesulitan silahkan hubungi admin</flux:text>

                            <flux:button 
                                variant="primary" 
                                class="mt-4 w-full" 
                                size="base-circle" 
                                icon="hand-coins"
                                href="{{ $this->detailPayment->registrationInvoices[0]->payment_url }}"
                            >Bayar Sekarang</flux:button>
                            <!--#When user have pending payment-->
                        @else
                            <flux:text variant="soft">
                                Kepada ananda <strong class="text-white">Nafiysah</strong>, untuk melanjutkan tahapan proses penerimaan siswa baru silahkan melakukan pembayaran biaya pendaftaran sebagai berikut:
                            </flux:text>    

                            <div class="bg-white/20 shadow-[inset_0px_2px_3px_rgba(255,255,255,0.5)] backdrop-blur-sm backdrop-filter rounded-xl mt-4 mb-4 p-3">
                                <flux:heading variant="bold" class="font-bold" size="xxl">{{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($this->detailPayment->registration_fee) }}</flux:heading>
                            </div>

                            <flux:text variant="soft">Pembayaran dapat dilakukan dengan berbagai pilihan metode pembayaran seperti <strong class="text-white">transfer bank, qris, e-money dan lain sebagainya.</strong> Silahkan checkout untuk melanjutkan proses pembayaran</flux:text>

                            <flux:button 
                                variant="primary" 
                                class="mt-4 w-full" 
                                size="base-circle" 
                                icon="credit-card"
                                wire:click="createInvoice"
                            >Checkout Pembayaran</flux:button>
                        @endif
                    </x-cards.soft-glass-card>
                @endif
            </x-animations.fade-down>
        </div>
    </div>
</div>
