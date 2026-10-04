<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Pembayaran') }}</x-slot:title>
        <x-slot:activePage>{{ __('Pembayaran Biaya Pendaftaran') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="mt-4 flex justify-center">
        <div class="w-full max-w-6xl">
            <x-animations.fade-down showTiming="50">
                <x-cards.registration-payment :payment="$this->detailPayment" :payment-account="$this->paymentAccount" :evidence="$evidence" />
            </x-animations.fade-down>
        </div>
    </div>
</div>
