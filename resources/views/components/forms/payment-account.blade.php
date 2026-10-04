<x-cards.soft-glass-card>
    <form wire:submit="save" class="flex flex-col gap-5">
        <div class="flex flex-col gap-2">
            <flux:heading variant="bold" size="xl">Rekening Pembayaran</flux:heading>
            <flux:text variant="soft">Rekening ini digunakan sebagai tujuan transfer biaya pendaftaran seluruh siswa.</flux:text>
        </div>

        @if (session('payment-account-success'))
            <x-notifications.basic-alert variant="success" icon="check-circle" role="status">
                <x-slot:title>{{ session('payment-account-success') }}</x-slot:title>
            </x-notifications.basic-alert>
        @endif

        @if (session('payment-account-failed'))
            <x-notifications.basic-alert role="alert">
                <x-slot:title>{{ session('payment-account-failed') }}</x-slot:title>
            </x-notifications.basic-alert>
        @endif

        <flux:field>
            <flux:label>Nama Bank</flux:label>
            <flux:input wire:model="bankName" type="text" maxlength="255" placeholder="Contoh: Bank Syariah Indonesia" required />
            <flux:error name="bankName" />
        </flux:field>

        <flux:field>
            <flux:label>Nomor Rekening</flux:label>
            <flux:input wire:model="accountNumber" type="text" inputmode="numeric" maxlength="50" placeholder="Masukkan nomor rekening" required />
            <flux:description class="!text-white/75">Masukkan angka saja, tanpa spasi atau tanda baca.</flux:description>
            <flux:error name="accountNumber" />
        </flux:field>

        <flux:field>
            <flux:label>Nama Pemilik Rekening</flux:label>
            <flux:input wire:model="accountName" type="text" maxlength="255" placeholder="Nama sesuai buku rekening" required />
            <flux:error name="accountName" />
        </flux:field>

        <flux:button type="submit" variant="primary" :loading="false" wire:loading.attr="disabled" wire:target="save">
            <flux:icon.check variant="micro" wire:loading.remove wire:target="save" />
            <flux:icon.loading variant="micro" wire:loading wire:target="save" />
            <span wire:loading.remove wire:target="save">Simpan Rekening</span>
            <span wire:loading wire:target="save" role="status">Menyimpan...</span>
        </flux:button>
    </form>
</x-cards.soft-glass-card>
