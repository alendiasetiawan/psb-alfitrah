<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Akun Owner') }}</x-slot:title>
        <x-slot:activePage>{{ __('Manajemen Akun Owner') }}</x-slot:activePage>
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
                <flux:modal.trigger name="add-edit-owner-modal">
                    <flux:button variant="primary" icon="plus" size="sm">{{ __('Tambah') }}</flux:button>
                </flux:modal.trigger>
            </div>
            <div>
                <flux:badge color="primary">{{ __('Jumlah') }} : {{ $this->listOwners->count() }}</flux:badge>
            </div>
        </div>
    </div>
    <!--#Button and Badge Counter-->

    <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4 gap-4">
        @forelse ($this->listOwners as $owner)
        <div class="col-span-1" wire:key='owner-{{ $owner->id }}'>
            <x-cards.user-card src="{{ !empty($owner->user?->photo) ? asset('storage/' . $owner->user?->photo) : '' }}">
                <x-slot:fullName>{{ $owner->name }}</x-slot:fullName>
                <x-slot:position>
                    {{ $owner->user?->email ?? '-' }}
                    <br/>
                    0{{ $owner->mobile_phone }}
                </x-slot:position>
                <x-slot:counter>{{ $owner->total_store }}</x-slot:counter>
                <x-slot:label>{{ __('Toko') }}</x-slot:label>
                <x-slot:actionButton>
                    <!--Action Direct Chat-->
                    <flux:button as="link" size="sm-circle" variant="filled" square
                        href="https://wa.me/{{ $owner->country_code }}{{ $owner->mobile_phone }}" target="_blank">
                        <flux:icon.chat-bubble-oval-left-ellipsis variant="micro" />
                    </flux:button>
                    <!--#Action Direct Chat-->

                    <!--Action More-->
                    <x-dropdowns.basic>
                        <x-slot:icon>
                            <flux:icon.ellipsis-vertical variant="micro" />
                        </x-slot:icon>
                        <flux:modal.trigger name="add-edit-owner-modal">
                            <x-dropdowns.menu-item
                            iconName="file-pen-line"
                            wire:click="$dispatch('open-add-edit-owner-modal', { id: '{{ Crypt::encrypt($owner->id) }}' })">
                                Edit
                            </x-dropdowns.menu-item>
                        </flux:modal.trigger>
                        <flux:modal.trigger name="delete-owner-modal({{ $owner->id }})">
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
            <x-modals.delete-modal modalName="delete-owner-modal({{ $owner->id }})" :isMobile="$isMobile" wire:click="deleteOwner('{{ Crypt::encrypt($owner->id) }}')">
                <x-slot:heading>Konfirmasi Hapus Owner</x-slot:heading>
                <!--Feedback when delete is failed-->
                @if (session('error-delete-owner'))
                    <div class="mt-2">
                        <x-notifications.basic-alert isCloseable="true">
                            <x-slot:title>{{ session('error-delete-owner') }}</x-slot:title>
                        </x-notifications.basic-alert>
                    </div>
                @endif
                <!--#Feedback when delete is failed-->

                <x-slot:content>
                    Apakah anda yakin ingin menghapus data owner <strong>{{ $owner->name }}</strong>
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

        @if ($this->listOwners->hasMorePages())
            <div class="col-span-3">
                <livewire:components.buttons.load-more :load-item="10"/>
            </div>
        @endif
    </div>

    <!--Modal Add/Edit Owner Data-->
    <livewire:components.modals.add-edit-owner-modal modalId="add-edit-owner-modal"/>
    <!--#Modal Add/Edit Owner Data-->
</div>
