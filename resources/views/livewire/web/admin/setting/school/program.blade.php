<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Program Pendidikan') }}</x-slot:title>
        <x-slot:activePage>{{ __('Pengaturan Program Pendidikan') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--Add Program Button-->
    <div class="flex flex-row justify-between items-center md:mt-3">
        <flux:modal.trigger name="add-edit-education-program-modal">
            <flux:button variant="primary" icon="plus" size="sm">
                Tambah
            </flux:button>
        </flux:modal.trigger>

        <div class="md:w-1/4">
            <flux:select wire:model.live='selectedAdmissionId'>
                @foreach ($academicYearLists as $key => $value)
                    <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </div>
    <!--#Add Program Button-->

    <!--List Of Programss-->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-3 gap-3">
        @forelse ($this->listEducationPrograms as $branch)
            <div class="col-span-1">
                <x-lists.stacked-list>
                    <x-slot:headerTitle>
                        {{ $branch->name }}
                    </x-slot:headerTitle>
                    <!--Looping Program Lists-->
                    @forelse ($branch->educationPrograms as $program)
                        <x-lists.stacked-list-item wire:key='program-{{ $program->id }}'> 
                            <x-slot:title>{{ $program->name }}</x-slot:title>
                            <x-slot:action>
                                <div class="flex justify-center">
                                    <flux:modal.trigger name="add-edit-education-program-modal"
                                    wire:click="$dispatch('open-add-edit-education-program-modal', { id: '{{ Crypt::encrypt($program->id); }}' })">
                                        <flux:icon.pencil-square variant="mini" class="text-white"/>
                                    </flux:modal.trigger>

                                    <flux:modal.trigger name="delete-program-modal({{ $program->id }})">
                                        <flux:icon.trash variant="mini" class="text-white"/>
                                    </flux:modal.trigger>
                                </div>
                            </x-slot:action>
                            <x-slot:description>
                                {{ $program->total_student }} Santri
                            </x-slot:description>
                        </x-lists.stacked-list-item>

                        <!--Modal Delete Program-->
                        <x-modals.delete-modal modalName="delete-program-modal({{ $program->id }})" :isMobile="$isMobile" wire:click="deleteProgram('{{ Crypt::encrypt($program->id) }}')">
                            <x-slot:heading>Konfirmasi Hapus Program</x-slot:heading>
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
                                Apakah anda yakin ingin menghapus data program <strong>{{ $program->name }}</strong> cabang <strong>{{ $branch->name }}</strong>
                            </x-slot:content>
                            @if (!session('error-id-delete'))
                                <x-slot:closeButton>Batal</x-slot:closeButton>
                                <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                            @endif
                        </x-modals.delete-modal>
                        <!--#Modal Delete Program-->
                    @empty
                    <div class="py-2">
                        <x-notifications.not-found />
                    </div>
                    @endforelse
                    <!--#Looping Program Lists-->
                </x-lists.stacked-list>
            </div>
        @empty
            <x-notifications.not-found />     
        @endforelse

    </div>
    <!--#List Of Programs-->

    <!--Add Edit Program Modal-->
    <livewire:components.modals.setting.add-edit-education-program-modal modalId="add-edit-education-program-modal"/>
    <!--#Add Edit Program Modal-->
</div>
