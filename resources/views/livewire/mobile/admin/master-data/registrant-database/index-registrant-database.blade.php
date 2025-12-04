<div>
    <div class="grid grid-cols-1">
        <flux:input placeholder="Cari nama pendaftar" wire:model.live.debounce.500ms="searchStudent" icon="search" />
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
                avatarImage="{{ !empty($registrant->studentAttachment->student_photo) ? asset('storage/' . $registrant->studentAttachment->student_photo) : '' }}"
                wire:key="registrant-{{ $registrant->id }}">
                <x-slot:title>{{ $registrant->student_name }}</x-slot:title>

                <x-slot:subTitle>
                    {{ $registrant->parent->username }}
                    |
                    {{ \App\Helpers\DateFormatHelper::indoDateTime($registrant->registration_date) }}
                </x-slot:subTitle>

                <x-slot:actionMenu>
                    <x-slot:menuItem>
                        <flux:menu.item icon="eye">Detail</flux:menu.item>
                        <flux:menu.item icon="file-pen-line">Pindah Program/Cabang</flux:menu.item>
                        <flux:menu.item icon="trash">Hapus</flux:menu.item>
                    </x-slot:menuItem>
                </x-slot:actionMenu>

                <x-slot:label>
                    <flux:badge color="primary" icon="school" size="sm">Alfitrah 1 Jonggol</flux:badge>
                    <flux:badge color="primary" icon="graduation-cap" size="sm">SMP Tahfidz</flux:badge>
                </x-slot:label>
            </x-cards.profile-card>
        @empty
            <x-animations.not-found/>
        @endforelse
    </div>

</div>
