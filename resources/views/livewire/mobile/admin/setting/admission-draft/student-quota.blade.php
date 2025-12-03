<div class="mb-17">
    <!--Admission Batch Filter-->
    <div class="grid lg:grid-cols-6 md:grid-cols-4 md:mt-4">
        <div class="col-span-1">
            <flux:field>
                <flux:label>Pilih Tahun Ajaran</flux:label>
                <flux:select wire:model.live='selectedAdmissionId'>
                    @foreach ($academicYearLists as $key => $value)
                        <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
    </div>
    <!--Admission Batch Filter-->

    <!--Quota List For Each Education Program-->
    <div class="grid lg:grid-cols-3 md:grid-cols-2 mt-4 gap-3">
        @forelse ($this->quotaPerBranchLists as $quota)
            <div class="col-span-1" wire:key='quota-{{ $quota->id }}'>
                <x-cards.soft-glass-card>
                    <div class="mb-1">
                        <flux:heading size="xl">{{ $quota->branch_name }}</flux:heading>
                    </div>
                    @foreach ($quota->educationPrograms as $program)
                        <x-lists.list-group wire:key='program-{{ $program->id }}'>
                            <x-slot:title>
                                {{ $program->name }}
                            </x-slot:title>

                            <x-slot:subTitle>
                                {{ $program->admissionQuotas[0]->amount ?? 'Belum Ditentukan' }} 
                                @if ($program->admissionQuotas->count() != 0)
                                    Santri
                                @endif
                            </x-slot:subTitle>

                            @if ($program->admissionQuotas->count() != 0)
                                <flux:badge variant="solid" size="sm" color="{{ $program->admissionQuotas[0]->status == 'Tutup' ? 'rose' : 'green' }}">
                                    {{ $program->admissionQuotas[0]->status ?? '' }}
                                </flux:badge>
                            @endif
                            
                            <x-slot:buttonAction>
                                <flux:modal.trigger 
                                name="edit-admission-quota-modal" 
                                wire:click="$dispatch('open-edit-admission-quota-modal', { id: '{{ Crypt::encrypt($program->id) }}' })"
                                >
                                    <flux:icon.pencil-square variant="mini" class="text-white"/>
                                </flux:modal.trigger>
                            </x-slot:buttonAction>
                        </x-lists.list-group>
                    @endforeach
                </x-cards.soft-glass-card>
            </div>
        @empty
            <x-notifications.not-found />
        @endforelse
    </div>
    <!--#Quota List For Each Education Program-->

    <!--Add & Edit Admission Quota Modal-->
    <livewire:components.modals.setting.edit-admission-quota-modal modalId="edit-admission-quota-modal" :$activeAdmission :$isMobile/>
    <!--#Add & Edit Admission Quota Modal-->
</div>
