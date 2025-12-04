<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Database Pendaftar') }}</x-slot:title>
        <x-slot:activePage>{{ __('Manajemen Database Pendaftar') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--ANCHOR - Registrant Table -->
    <div class="grid grid-cols-1 mt-4">
        <x-tables.basic-table :headers="['No', 'Username',  'Nama Santri', 'Cabang', 'Program', 'Tanggal Daftar', 'Opsi']">
            <x-slot:heading>
                Tabel Akun Siswa
            </x-slot:heading>

            <x-slot:action>
                <!-- Search and Filter -->
                <div class="grid grid-cols-2 justify-between items-center mt-4 gap-2">
                    <div class="flex gap-2">
                        <div class="w-4/6">
                            <flux:input placeholder="Cari nama/username pendaftar" wire:model.live.debounce.500ms="searchStudent" icon="search" />
                        </div>

                        <div class="w-2/6">
                            <flux:select wire:model.live="selectedAdmissionId">
                                @foreach ($admissionYearLists as $key => $value)
                                    <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <flux:badge variant="solid" color="primary" icon="user-check">Jumlah : {{ $totalStudent }}</flux:badge>
                    </div>
                </div>
            </x-slot:action>

            @forelse ($this->registrantLists as $registrant)
                <x-tables.row :striped="true" wire:key="{{ $registrant->id }}" :loop=$loop>
                    <x-tables.cell>
                        <flux:text>{{ $loop->iteration }}</flux:text>
                    </x-tables.cell>
                    <x-tables.cell>
                        <flux:text>{{ $registrant->username }}</flux:text>
                    </x-tables.cell>
                    <x-tables.cell>
                        <flux:text>
                            {{ $registrant->student_name }}
                        </flux:text>

                        @if ($registrant->payment_status == \App\Enums\VerificationStatusEnum::VALID)
                            <flux:badge color="green" size="sm" icon="banknotes">
                                Sudah Bayar
                            </flux:badge>
                        @else
                            <flux:badge color="red" size="sm" icon="x-circle">
                                Belum Bayar
                            </flux:badge>
                        @endif
                    </x-tables.cell>
                    <x-tables.cell>
                        <flux:text>{{ $registrant->branch_name }}</flux:text>
                    </x-tables.cell>
                    <x-tables.cell>
                        <flux:text>{{ $registrant->program_name }}</flux:text>
                    </x-tables.cell>
                    <x-tables.cell>
                        <flux:text>{{ \App\Helpers\DateFormatHelper::indoDateTime($registrant->registration_date) }}</flux:text>
                    </x-tables.cell>
                    <x-tables.cell>
                        <a x-on:click.stop>
                            <flux:dropdown offset="-5" gap="1">
                                <flux:button variant="ghost" size="xs">
                                    <flux:icon.ellipsis-vertical variant="micro" class="text-white" />
                                </flux:button>
                                <flux:menu>
                                    <flux:modal.trigger name="add-edit-admission-modal" 
                                    wire:click="#">
                                        <flux:menu.item icon="eye">Detail</flux:menu.item>
                                    </flux:modal.trigger>

                                    <flux:modal.trigger name="delete-student-modal({{ $registrant->id }})">
                                        <flux:menu.item icon="trash">Hapus</flux:menu.item>
                                    </flux:modal.trigger>
                                </flux:menu>
                            </flux:dropdown>
                        </a>    
                    </x-tables.cell>
                </x-tables.row>

                <!--ANCHOR - MODAL DELETE STUDENT-->
                <x-modals.delete-modal modalName="delete-student-modal({{ $registrant->id }})" :isMobile="$isMobile" wire:click="deleteStudent('{{ Crypt::encrypt($registrant->id) }}')">
                    <x-slot:heading size="xl">Konfirmasi Hapus Pendaftar</x-slot:heading>
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
                <x-tables.empty text="Tidak ada data yang ditemukan" :colspan="7" />
            @endforelse

            @if ($this->registrantLists->hasPages())
                <x-slot:pagination>
                    {{ $this->registrantLists->links(data: ['scrollTo' => false]) }}
                </x-slot:pagination>
            @endif
        </x-tables.basic-table>
    </div>
</div>