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
        @forelse ($this->feePerBranchProgramLists as $fee)
            <div class="col-span-1" wire:key='quota-{{ $fee->id }}'>
                <x-cards.soft-glass-card>
                    <div class="mb-1">
                        <flux:heading size="xl">{{ $fee->branch_name }}</flux:heading>
                    </div>
                    @foreach ($fee->educationPrograms as $program)
                        <x-lists.list-group wire:key='program-{{ $program->id }}'>
                            <x-slot:title>
                                {{ $program->name }}
                            </x-slot:title>
                            <x-slot:subTitle>
                                @if ($program->admissionFees->count() != 0)
                                    Int: {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($program->admissionFees[0]->internal_registration_fee) }} | Eks: {{ \App\Helpers\FormatCurrencyHelper::convertToRupiah($program->admissionFees[0]->registration_fee) }}
                                @else
                                    Belum Ditentukan
                                @endif
                            </x-slot:subTitle>
                            <x-slot:buttonAction>
                                    <flux:modal.trigger 
                                    name="edit-admission-fee-modal" 
                                    wire:click="$dispatch('open-edit-admission-fee-modal', { id: '{{ Crypt::encrypt($program->id) }}' })"
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
    <livewire:components.modals.setting.edit-admission-fee-modal modalId="edit-admission-fee-modal" :$activeAdmission :$isMobile/>
    <!--#Add & Edit Admission Quota Modal-->
</div>
