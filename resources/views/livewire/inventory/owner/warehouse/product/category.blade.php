<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Kategori Produk') }}</x-slot:title>
        <x-slot:activePage>{{ __('Manajemen Kategori Produk') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--Search box and add button-->
    <div class="flex justify-between md:mt-4">
        <div class="md:w-1/3">
            <flux:input placeholder="Cari Kategori" class="w-full" wire:model.live.debounce.500ms="searchCategory"/>
        </div>
        <flux:modal.trigger name="add-edit-product-category-modal">
            <flux:button variant="primary" icon="plus">
                Tambah
            </flux:button>
        </flux:modal.trigger>
    </div>
    <!--#Search box and add button-->

    <!--Category List Group-->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4">
        <div class="col-span-1">
            <x-lists.stacked-list>
                <x-slot:headerTitle>
                    Daftar Kategori
                </x-slot:headerTitle>
                <x-slot:headerBadge>
                    {{ $totalCategory }} Kategori
                </x-slot:headerBadge>
                <!--Looping Category Lists-->
                @forelse ($this->categoryLists() as $category)
                    <x-lists.stacked-list-item wire:key='category-{{ $category->id }}'>
                        <x-slot:title>{{ $category->name }}</x-slot:title>
                        <x-slot:action>
                            <div class="flex justify-center">
                                <flux:modal.trigger name="add-edit-product-category-modal"
                                wire:click="$dispatch('open-add-edit-category-modal', { id: '{{ Crypt::encrypt($category->id); }}' })">
                                    <flux:icon.pencil-square variant="mini"/>
                                </flux:modal.trigger>

                                <flux:modal.trigger name="delete-category-modal({{ $category->id }})">
                                    <flux:icon.trash variant="mini"/>
                                </flux:modal.trigger>
                            </div>
                        </x-slot:action>
                        <x-slot:description>
                            @if (!empty($category->description))
                                {{ $category->description }}
                                <br/>
                            @endif
                            {{ $category->total_product }} Produk
                        </x-slot:description>
                    </x-lists.stacked-list-item>

                    <!--Modal Delete Category-->
                    <x-modals.delete-modal modalName="delete-category-modal({{ $category->id }})" :isMobile="$isMobile" wire:click="deleteCategory('{{ Crypt::encrypt($category->id) }}')">
                        <x-slot:heading>Konfirmasi Hapus Kategori</x-slot:heading>
                        <!--Feedback when delete is failed-->
                        @if (session('error-delete-category'))
                            <div class="mt-2">
                                <x-notifications.basic-alert isCloseable="true">
                                    <x-slot:title>{{ session('error-delete-category') }}</x-slot:title>
                                </x-notifications.basic-alert>
                            </div>
                        @endif
                        <!--#Feedback when delete is failed-->

                        <x-slot:content>
                            Apakah anda yakin ingin menghapus data category <strong>{{ $category->name }}</strong>
                        </x-slot:content>
                        @if (!session('error-id-delete'))
                            <x-slot:closeButton>Batal</x-slot:closeButton>
                            <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                        @endif
                    </x-modals.delete-modal>
                    <!--#Modal Delete Category-->
                @empty
                    <x-notifications.not-found />
                @endforelse
                <!--#Looping Category Lists-->

                @if ($this->categoryLists->hasMorePages())
                    <livewire:components.buttons.load-more :load-item="10"/>
                @endif
            </x-lists.stacked-list>
        </div>
    </div>
    <!--#Category List Group-->

    <!--Modal Add/Edit Category-->
    <livewire:components.modals.add-edit-product-category-modal modalId="add-edit-product-category-modal"/>
    <!--#Modal Add/Edit Category-->
</div>
