<div class="mb-17">
    <div class="grid grid-cols-1">
        <flux:input placeholder="Cari nama/username pendaftar" wire:model.live.debounce.500ms="searchStudent" icon="search" />
    </div>

    <!--ANCHOR - Filter Data -->
    <div class="flex justify-between mt-3 gap-3">
        <flux:select wire:model.live="selectedAdmissionId">
            @foreach ($admissionYearLists as $key => $value)
                <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:badge variant="solid" color="primary" icon="user-check">Jumlah : {{ $totalStudent }}</flux:badge>
    </div>

    <!--ANCHOR - List of Students-->
    <div class="grid grid-cols-1 mt-6">
        @forelse ($this->registrantLists as $registrant)
            <x-cards.profile-card
                class="mb-3"
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
                        <flux:menu.item icon="eye">Detail</flux:menu.item>
                        <flux:menu.item icon="file-pen-line">Pindah Program/Cabang</flux:menu.item>
                        <flux:modal.trigger name="delete-student-modal({{ $registrant->id }})">
                            <flux:menu.item icon="trash">Hapus</flux:menu.item>
                        </flux:modal.trigger>
                    </x-slot:menuItem>
                </x-slot:actionMenu>

                <x-slot:label>
                    <flux:badge color="primary" icon="school" size="sm">Alfitrah 1 Jonggol</flux:badge>
                    <flux:badge color="primary" icon="graduation-cap" size="sm">SMP Tahfidz</flux:badge>
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
        @empty
            <x-animations.not-found/>
        @endforelse
    </div>

</div>
