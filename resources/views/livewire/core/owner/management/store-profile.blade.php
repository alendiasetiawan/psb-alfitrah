<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Profil Toko') }}</x-slot:title>
        <x-slot:activePage>{{ __('Manajemen Data Toko') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--Action-->
    <div class="flex items-center justify-between lg:mt-4">
        <div>
            <flux:modal.trigger name="add-edit-store-modal">
                <flux:button variant="primary" icon="plus" size="sm">{{ __('Tambah') }}</flux:button>
            </flux:modal.trigger>
        </div>
        <div>
            <flux:badge color="primary">{{ __('Jumlah') }} : {{ $this->listStores->count() }}</flux:badge>
        </div>
    </div>
    <!--#Action-->

    <!--List of Stores-->
    <div class="grid md:grid-cols-3 mt-4 gap-4 mb-4">
        @forelse ($this->listStores as $store)
            <div class="col-span-1">
                <x-cards.profile-card
                    avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($store->name) }}"
                    avatarImage="{{ !empty($store->logo) ? asset('storage/' . $store->logo) : '' }}"
                    wire:key="store-{{ $store->id }}"
                >
                    <x-slot:actionMenu>
                        <x-slot:menuItem>
                            <flux:modal.trigger name="add-edit-store-modal">
                                <flux:menu.item icon="file-pen-line" wire:click="$dispatch('open-add-edit-store-modal', { id: '{{ Crypt::encrypt($store->id) }}' })">
                                    Edit
                                </flux:menu.item>
                            </flux:modal.trigger>

                            <flux:modal.trigger name="delete-store-modal">
                                <flux:menu.item icon="trash" wire:click="setDeleteValue('{{ Crypt::encrypt($store->id) }}')">
                                        Hapus
                                </flux:menu.item>
                            </flux:modal.trigger>
                        </x-slot:menuItem>
                    </x-slot:actionMenu>

                    <x-slot:title>
                        {{ $store->name }}
                    </x-slot:title>
                    <x-slot:subTitle>{{ $store->address ?? 'Alamat Kosong' }}</x-slot:subTitle>
                    <x-slot:label>
                        <flux:badge
                        color="sky"
                        icon="user">{{ \App\Helpers\FormatStringHelper::limitText($store->owner->name ?? 'Kosong') }}</flux:badge>
                        <flux:badge
                        color="sky"
                        icon="package-search">{{ $store->total_products }}</flux:badge>
                    </x-slot:label>
                </x-cards.profile-card>
            </div>
        @empty
            <div class="col-span-3">
                <x-notifications.not-found />
            </div>
        @endforelse
    </div>

    @if ($this->listStores->hasMorePages())
        <livewire:components.buttons.load-more :loadItem="10"/>
    @endif
    <!--#List of Stores-->

    <!--Modal Add Store-->
    <livewire:components.modals.add-edit-store-modal modalId='add-edit-store-modal'/>
    <!--#Modal Add Store-->

    <!--Modal Delete Store-->
    <x-modals.delete-modal modalName="delete-store-modal" wire:click="deleteStore">
        <x-slot:heading>Hapus toko?</x-slot:heading>

        @if (session('error-delete-store'))
            <div class="mt-2">
                <x-notifications.basic-alert isCloseable="true">
                    <x-slot:title>{{ session('error-delete-store') }}</x-slot:title>
                </x-notifications.basic-alert>
            </div>
        @endif

        <x-slot:content>
            @if (session('error-id-delete'))
                <div class="mt-2">
                    <x-notifications.basic-alert>
                        <x-slot:title>{{ session('error-id-delete') }}</x-slot:title>
                    </x-notifications.basic-alert>
                </div>
            @else
                <div wire:loading wire:target="setDeleteValue">
                    <x-loading.horizontal-dot/>
                </div>
                <div wire:loading.remove wire:target="setDeleteValue">
                    Anda ingin menghapus data toko <strong>{{ $storeData->name ?? '' }}</strong>
                </div>
            @endif
        </x-slot:content>
        <x-slot:closeButton>Batal</x-slot:closeButton>
        <x-slot:deleteButton>
            Hapus
        </x-slot:deleteButton>
    </x-modals.delete-modal>

</div>
