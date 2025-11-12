<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Akun Reseller') }}</x-slot:title>
        <x-slot:activePage>{{ __('Manajemen Akun Reseller') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="flex justify-start mt-3" wire:ignore>
        <x-navigations.pill-tab
        hrefOne="{{ route('owner.management.owner_account') }}"
        hrefTwo="{{ route('owner.management.reseller_account') }}" >
            <x-slot:tabOne>{{ __('Owner') }}</x-slot:tabOne>
            <x-slot:tabTwo>{{ __('Reseller') }}</x-slot:tabTwo>
        </x-navigations.pill-tab>
    </div>


    <!--Button and Badge Counter-->
    <div class="flex flex-col justify-between">
        <div class="flex items-center justify-between mt-3">
            <div>
                <flux:modal.trigger name="add-edit-reseller-modal">
                    <flux:button variant="primary" icon="plus" size="sm">{{ __('Tambah') }}</flux:button>
                </flux:modal.trigger>
            </div>
            <div>
                <flux:badge color="primary">{{ __('Jumlah') }} : 0</flux:badge>
            </div>
        </div>
    </div>
    <!--#Button and Badge Counter-->

    <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4 gap-4">
        @forelse ($this->listResellers as $reseller)
        <div class="col-span-1" wire:key='owner-{{ $reseller->id }}'>
            <x-cards.user-card src="{{ !empty($reseller->user?->photo) ? asset('storage/' . $reseller->user?->photo) : '' }}">
                <x-slot:fullName>{{ $reseller->name }}</x-slot:fullName>
                <x-slot:position>
                    {{ $reseller->user?->email ?? '-' }}
                    <br/>
                    0{{ $reseller->mobile_phone }}
                </x-slot:position>
                <x-slot:counter>{{ $reseller->transactionRecapitulation?->total ?? 0 }}</x-slot:counter>
                <x-slot:label>{{ __('Transaksi') }}</x-slot:label>
                <x-slot:actionButton>
                    <!--Action Direct Chat-->
                    <flux:button as="link" size="sm-circle" variant="filled" square
                        href="https://wa.me/{{ $reseller->country_code }}{{ $reseller->mobile_phone }}" target="_blank">
                        <flux:icon.chat-bubble-oval-left-ellipsis variant="micro" />
                    </flux:button>
                    <!--#Action Direct Chat-->

                    <!--Action More-->
                    <x-dropdowns.basic>
                        <x-slot:icon>
                            <flux:icon.ellipsis-vertical variant="micro" />
                        </x-slot:icon>
                        <flux:modal.trigger name="add-edit-reseller-modal">
                            <x-dropdowns.menu-item
                            iconName="file-pen-line"
                            wire:click="$dispatch('open-add-edit-reseller-modal', { id: '{{ Crypt::encrypt($reseller->id) }}' })">
                                Edit
                            </x-dropdowns.menu-item>
                        </flux:modal.trigger>
                        <flux:modal.trigger name="delete-reseller-modal({{ $reseller->id }})">
                            <x-dropdowns.menu-item
                                iconName="trash">
                                Hapus
                            </x-dropdowns.menu-item>
                        </flux:modal.trigger>
                    </x-dropdowns.basic>
                    <!--#Action More-->
                </x-slot:actionButton>
            </x-cards.user-card>

            <!--Modal Delete Data-->
            <x-modals.delete-modal modalName="delete-reseller-modal({{ $reseller->id }})" :isMobile="$isMobile" wire:click="deleteReseller('{{ Crypt::encrypt($reseller->id) }}')">
                <x-slot:heading>Konfirmasi Hapus Reseller</x-slot:heading>
                <!--Feedback when delete is failed-->
                @if (session('error-delete-reseller'))
                    <div class="mt-2">
                        <x-notifications.basic-alert isCloseable="true">
                            <x-slot:title>{{ session('error-delete-reseller') }}</x-slot:title>
                        </x-notifications.basic-alert>
                    </div>
                @endif
                <!--#Feedback when delete is failed-->

                <x-slot:content>
                    Apakah anda yakin ingin menghapus data reseller <strong>{{ $reseller->name }}</strong>
                </x-slot:content>

                @if (!session('error-id-delete'))
                    <x-slot:closeButton>Batal</x-slot:closeButton>
                    <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                @endif
            </x-modals.delete-modal>
            <!--#Modal Delete Data-->
        </div>
        @empty
        <div class="col-span-3">
            <x-notifications.not-found />
        </div>
        @endforelse

        @if ($this->listResellers->hasMorePages())
            <div class="col-span-3">
                <livewire:components.buttons.load-more :load-item="10"/>
            </div>
        @endif
    </div>

    <!--Modal Add/Edit Reseller Data-->
    <livewire:components.modals.add-edit-reseller-modal modalId="add-edit-reseller-modal"/>
    <!--#Modal Add/Edit Reseller Data-->
</div
