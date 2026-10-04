<div class="mb-20">
    <div class="mt-4">
        <x-animations.fade-down showTiming="50">
            <x-cards.registration-payment :payment="$this->detailPayment" :payment-account="$this->paymentAccount" :evidence="$evidence" :mobile="true" />
        </x-animations.fade-down>
    </div>
</div>
