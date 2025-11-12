<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Tahun Ajaran') }}</x-slot:title>
        <x-slot:activePage>{{ __('Pengaturan Tahun Ajaran PSB') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="flex flex-row justify-between items-center md:mt-3">
        <flux:modal.trigger name="add-edit-admission-modal">
            <flux:button variant="primary" icon="plus" size="sm">
                Tambah
            </flux:button>
        </flux:modal.trigger>
    </div>

    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-3 md:mt-4">
        @forelse ($this->listAcademicYears as $year)
            <div class="col-span-1" wire:key='academic-year-{{ $year->id }}'>
                <x-cards.border-card subTextVariant="strong" subTextColor="{{ $year->status == 'Buka' ? 'green' : 'red' }}" borderColor="primary">
                    <x-slot:title>{{ $year->name }}</x-slot:title>
                    <x-slot:subTitle>{{ $year->status }}</x-slot:subTitle>
                    <x-slot:buttonAction>
                        <a x-on:click.stop>
                            <flux:dropdown offset="-5" gap="1">
                                <flux:button variant="ghost" size="xs">
                                    <flux:icon.ellipsis-vertical variant="micro" />
                                </flux:button>
                                <flux:menu>
                                    <flux:modal.trigger name="add-edit-admission-modal" 
                                    wire:click="$dispatch('open-add-edit-admission-modal', { id: '{{ Crypt::encrypt($year->id) }}' })">
                                        <flux:menu.item icon="file-pen-line">Edit</flux:menu.item>
                                    </flux:modal.trigger>

                                    <flux:modal.trigger name="delete-admission-modal({{ $year->id }})">
                                        <flux:menu.item icon="trash">Hapus</flux:menu.item>
                                    </flux:modal.trigger>

                                    <flux:modal.trigger name="add-edit-admission-batch-modal" wire:click="$dispatch('create-new-batch', { id: '{{ Crypt::encrypt($year->id) }}' })">
                                        <flux:menu.item icon="plus">Tambah Gelombang</flux:menu.item>
                                    </flux:modal.trigger>
                                </flux:menu>
                            </flux:dropdown>
                        </a>
                    </x-slot:buttonAction>

                    <!--Delete Admission Modal-->
                    <x-modals.delete-modal modalName="delete-admission-modal({{ $year->id }})" :isMobile="$isMobile" wire:click="deleteAdmission('{{ Crypt::encrypt($year->id) }}')">
                        <x-slot:heading>Konfirmasi Hapus Tahun Ajaran</x-slot:heading>
                        <!--Feedback when delete is failed-->
                        @if (session('error-delete-program'))
                            <div class="mt-2">
                                <x-notifications.basic-alert isCloseable="true">
                                    <x-slot:title>{{ session('error-delete-program') }}</x-slot:title>
                                </x-notifications.basic-alert>
                            </div>
                        @endif
                        <!--#Feedback when delete is failed-->

                        <x-slot:content>
                            Apakah anda yakin ingin menghapus tahun ajaran <strong>{{ $year->name }}</strong>
                        </x-slot:content>
                        @if (!session('error-id-delete'))
                            <x-slot:closeButton>Batal</x-slot:closeButton>
                            <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                        @endif
                    </x-modals.delete-modal>
                    <!--#Delete Admission Modal-->

                    <!--List of Batches-->
                    @foreach ($year->admissionBatches as $batch)
                        <x-lists.list-group>
                            <x-slot:title>
                                {{ $batch->name }}
                            </x-slot:title>
                            <x-slot:subTitle>
                                {{ \App\Helpers\DateFormatHelper::shortIndoDate($batch->open_date) }} 
                                - 
                                {{ \App\Helpers\DateFormatHelper::shortIndoDate($batch->close_date) }} 
                            </x-slot:subTitle>
                            <flux:text variant="strong" color="red">{{ $batch->status }}</flux:text>
                            <x-slot:buttonAction>
                                <flux:modal.trigger name="add-edit-admission-batch-modal" 
                                wire:click="$dispatch('open-add-edit-admission-batch-modal', { id: '{{ Crypt::encrypt($batch->id) }}' })">
                                    <flux:icon.pencil-square variant="mini"/>
                                </flux:modal.trigger>

                                <flux:modal.trigger name="delete-admission-batch-modal({{ $year->id }})">
                                    <flux:icon.trash variant="mini"/>
                                </flux:modal.trigger>
                            </x-slot:buttonAction>
                        </x-lists.list-group>

                        <!--Delete Admission Batch Modal-->
                        <x-modals.delete-modal modalName="delete-admission-batch-modal({{ $year->id }})" :isMobile="$isMobile" wire:click="deleteAdmissionBatch('{{ Crypt::encrypt($batch->id) }}')">
                            <x-slot:heading>Konfirmasi Hapus Gelombang PSB</x-slot:heading>
                            <!--Feedback when delete is failed-->
                            @if (session('error-delete-program'))
                                <div class="mt-2">
                                    <x-notifications.basic-alert isCloseable="true">
                                        <x-slot:title>{{ session('error-delete-program') }}</x-slot:title>
                                    </x-notifications.basic-alert>
                                </div>
                            @endif
                            <!--#Feedback when delete is failed-->

                            <x-slot:content>
                                Apakah anda yakin ingin menghapus <strong>{{ $batch->name }}</strong> 
                                PSB <strong>{{ $year->name }}</strong>
                            </x-slot:content>
                            @if (!session('error-id-delete'))
                                <x-slot:closeButton>Batal</x-slot:closeButton>
                                <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                            @endif
                        </x-modals.delete-modal>
                        <!--#Delete Admission Batch Modal-->
                    @endforeach
                    <!--#List of Batches-->
                </x-cards.border-card>
            </div>
        @empty
            <x-notifications.not-found />
        @endforelse
        
        <!--Load More Button-->
        <div class="col-span-3">
            @if ($this->listAcademicYears->hasMorePages())
                <livewire:components.buttons.load-more :loadItem="6"/>
            @endif
        </div>
        <!--#Load More Button-->
    </div>

    <!--Add & Edit Admission Modal-->
    <livewire:components.modals.setting.add-edit-admission-modal modalId="add-edit-admission-modal"/>
    <!--#Add & Edit Admission Modal-->

    <!--Add & Edit Admission Batch Modal-->
    <livewire:components.modals.setting.add-edit-admission-batch-modal modalId="add-edit-admission-batch-modal"/>
    <!--#Add & Edit Admission Batch Modal-->
</div>