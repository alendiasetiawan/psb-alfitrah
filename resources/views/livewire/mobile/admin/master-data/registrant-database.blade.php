<div class="mb-18">
    <!--ANCHOR - Sticky Search and Filter Section -->
    <x-animations.sticky>
        <x-animations.fade-down showTiming="50">    
                <div class="grid grid-cols-1">
                    <flux:input placeholder="Cari nama/username pendaftar" wire:model.live.debounce.500ms="searchStudent" icon="search" />
                </div>

                <div class="flex justify-between mt-3 gap-3">
                    <flux:select wire:model.live="selectedAdmissionId">
                        @foreach ($admissionYearLists as $key => $value)
                            <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:badge variant="solid" color="primary" icon="user-check">Jumlah : {{ $totalStudent }}</flux:badge>
                </div>
        </x-animations.fade-down>
    </x-animations.sticky>

    <!--ANCHOR - List of Students-->
    <x-animations.fade-down showTiming="150">    
        <div class="grid md:grid-cols-2 grid-cols-1 gap-3 mt-2">
            @forelse ($this->registrantLists as $registrant)
                <div class="col-span-1">
                    <x-cards.profile-card
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($registrant->student_name) }}"
                        avatarImage="{{ !empty($registrant->user_photo) ? asset('storage/' . $registrant->user_photo) : '' }}"
                        wire:key="registrant-{{ $registrant->id }}">
                        <x-slot:title>{{ $registrant->student_name }}</x-slot:title>

                        <x-slot:subTitle>
                            {{ $registrant->username }}
                            |
                            {{ \App\Helpers\DateFormatHelper::indoDateTime($registrant->registration_date) }}
                        </x-slot:subTitle>

                        <x-slot:actionMenu>
                            <x-slot:menuItem>
                                <flux:modal.trigger name="detail-registrant-modal" wire:click="$dispatch('open-detail-registrant-modal', { id: '{{ Crypt::encrypt($registrant->id) }}' })">
                                    <flux:menu.item icon="eye">Detail</flux:menu.item>
                                </flux:modal.trigger>

                                <flux:modal.trigger name="delete-student-modal({{ $registrant->id }})">
                                    <flux:menu.item icon="trash">Hapus</flux:menu.item>
                                </flux:modal.trigger>
                            </x-slot:menuItem>
                        </x-slot:actionMenu>

                        <x-slot:label>
                            <flux:badge color="primary" icon="school" size="sm">{{ $registrant->branch_name }}</flux:badge>
                            <flux:badge color="primary" icon="graduation-cap" size="sm">{{ $registrant->program_name }}</flux:badge>
                        </x-slot:label>
                    </x-cards.profile-card>

                    <!--ANCHOR - MODAL DELETE STUDENT-->
                    <x-modals.delete-modal modalName="delete-student-modal({{ $registrant->id }})" :isMobile="$isMobile" wire:click="deleteStudent('{{ Crypt::encrypt($registrant->id) }}')">
                        <x-slot:heading>Konfirmasi Hapus Pendaftar</x-slot:heading>
                        <!--Feedback when delete is failed-->
                        @if (session('error-delete-student'))
                            <div class="mt-2">
                                <x-notifications.basic-alert isCloseable="true">
                                    <x-slot:title>{{ session('error-delete-student') }}</x-slot:title>
                                </x-notifications.basic-alert>
                            </div>
                        @endif
                        <!--#Feedback when delete is failed-->

                        <x-slot:content>
                            Apakah anda yakin ingin menghapus data pendaftar <strong>{{ $registrant->student_name }}</strong>?
                            <br/><br/>
                            <div class="flex items-center text-amber-400 gap-2">
                                <flux:icon.triangle-alert/>
                                <flux:text class="text-amber-400">Data yang sudah dihapus, tidak bisa dikembalikan!</flux:text>
                            </div>
                        </x-slot:content>

                        @if (!session('error-id-delete'))
                            <x-slot:closeButton>Batal</x-slot:closeButton>
                            <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                        @endif
                    </x-modals.delete-modal>
                    <!--#MODAL DELETE STUDENT-->
                </div>
            @empty
                <x-animations.not-found/>
            @endforelse

            <!--ANCHOR - Load More Button-->
            <livewire:components.buttons.load-more loadItem="20"/>
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>

    <!--ANCHOR - DETAIL REGISTRANT MODAL-->
    @if ($this->registrantLists->hasMorePages())
        <livewire:components.modals.master-data.detail-registrant-modal modalId="detail-registrant-modal" :$isMobile/>
    @endif
    <!--#DETAIL REGISTRANT MODAL-->
</div>
