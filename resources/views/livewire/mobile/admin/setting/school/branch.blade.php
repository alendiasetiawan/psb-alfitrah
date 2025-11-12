<div>
    <div class="grid grid-cols-1 mb-4 mt-4">
        <div class="col-span-1">
            <flux:modal.trigger name="add-edit-branch-modal">
                <flux:button variant="primary" icon="plus" size="sm">{{ __('Tambah Cabang') }}</flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($this->listBranches as $branch)
            <div class="col-span-1" wire:key='branch-{{ $branch->id }}'>
                <x-cards.product-card src="{{ $branch->photo ? asset('storage/'.$branch->photo) : null }}">
                    <x-slot:category>{{ $branch->mobile_phone }}</x-slot:category>
                    <x-slot:productTitle>{{ $branch->name }}</x-slot:productTitle>
                    <x-slot:productDescription>
                        {{ Str::limit($branch->address, 100, '...') }}
                        @if (!is_null($branch->map_link))
                        <br/>
                        <a href="{{ $branch->map_link }}" target="_blank" class="text-blue-500">
                            Lihat Map
                        </a>
                        @endif
                    </x-slot:productDescription>
                    <x-slot:price>
                        {{ $branch->total_program }} Program
                    </x-slot:price>
                    <x-slot:buttonAction>
                        <flux:button.group>
                            <flux:modal.trigger name="add-edit-branch-modal">
                                <flux:button 
                                variant="primary" 
                                size="sm"
                                wire:click="$dispatch('open-add-edit-branch-modal', { id: '{{ Crypt::encrypt($branch->id) }}' })"
                                >
                                    Edit
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal.trigger name="delete-branch-modal({{ $branch->id }})">
                                <flux:button variant="filled" size="sm">Hapus</flux:button>
                            </flux:modal.trigger>
                        </flux:button.group>
                    </x-slot:buttonAction>
                </x-cards.product-card>

                <!--Modal Delete Cabang-->
                <x-modals.delete-modal modalName="delete-branch-modal({{ $branch->id }})" :isMobile="$isMobile" wire:click="deleteBranch('{{ Crypt::encrypt($branch->id) }}')">
                    <x-slot:heading>Konfirmasi Hapus Cabang</x-slot:heading>
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
                        Apakah anda yakin ingin menghapus data cabang <strong>{{ $branch->name }}</strong>
                    </x-slot:content>

                    @if (!session('error-id-delete'))
                        <x-slot:closeButton>Batal</x-slot:closeButton>
                        <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                    @endif
                </x-modals.delete-modal>
                <!--#Modal Delete Cabang-->
            </div>
        @empty
            <div class="col-span-3">
                <x-notifications.not-found />
            </div>
        @endforelse
    </div>

    <!--Modal Add/Edit Branch-->
    <livewire:components.modals.setting.add-edit-branch-modal modalId="add-edit-branch-modal"/>
    <!--#Modal Add/Edit Branch-->
</div>