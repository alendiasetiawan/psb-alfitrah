<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Database Siswa') }}</x-slot:title>
        <x-slot:activePage>{{ __('Manajemen Database Siswa') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--ANCHOR - COUNTER STUDENT STATISTIC-->
    <x-animations.fade-down showTiming="50">
        <div class="grid grid-cols-3 mt-4 gap-3">
            @foreach ($this->totalStudents as $total)
                <div class="col-span-1">
                    <x-cards.counter-card subCounterColor="soft-white">
                        <x-slot:heading>
                            {{ $total->branch_name }}
                        </x-slot:heading>
                        <x-slot:mainCounter>
                            {{ $total->total_student }}
                        </x-slot:mainCounter>
                        <x-slot:subCounter>
                            Santri
                        </x-slot:subCounter>
                        <x-slot:subIcon>
                            <flux:icon.school class="size-15 text-primary-400"/>
                        </x-slot:subIcon>
                    </x-cards.counter-card>
                </div>
            @endforeach
        </div>
    </x-animations.fade-down>

    <!--ANCHOR - OFFICIAL STUDENT TABLE-->
    <x-animations.fade-down showTiming="150">
        <div class="grid grid-cols-1 mt-4">
            <x-tables.basic-table :headers="['No', 'Nama Santri', 'NISN', 'Cabang', 'Program', 'Beasiswa', 'Opsi']">
                <x-slot:heading>
                    Tabel Siswa Resmi
                </x-slot:heading>

                <x-slot:action>
                    <!-- ANCHOR - Search and Filter -->
                    <div class="grid grid-cols-2 justify-between items-center mt-4 gap-2">
                        <div class="flex gap-2">
                            <div class="w-4/6">
                                <flux:input placeholder="Cari nama/username pendaftar"
                                    wire:model.live.debounce.500ms="searchStudent" icon="search" />
                            </div>

                            <div class="w-2/6">
                                <flux:select wire:model.live="selectedAdmissionId">
                                    @foreach ($admissionYearLists as $key => $value)
                                        <flux:select.option value="{{ $key }}">{{ $value }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                        </div>
                        
                        <!--DOWNLOAD EXCEL STUDENT DATA-->
                        <div class="flex justify-end">
                            <flux:dropdown>
                                <flux:button 
                                    icon:trailing="download" 
                                    variant="primary" 
                                    class="hover:cursor-pointer"
                                    loading="loading-export-excel">
                                        Excel Database Siswa
                                </flux:button>

                                <flux:menu>
                                    @foreach ($branchLists as $key => $branch)
                                        <flux:menu.item icon="school" wire:click="exportExcel({{ $key }})" >
                                            {{ $branch }}
                                        </flux:menu.item>
                                    @endforeach
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                        <!--#DOWNLOAD EXCEL STUDENT DATA-->
                    </div>
                </x-slot:action>

                @forelse ($this->officialStudentLists as $student)
                    <x-tables.row :striped="true" wire:key="{{ $student->id }}" :loop=$loop>
                        <x-tables.cell>
                            <flux:text>{{ $loop->iteration }}</flux:text>
                        </x-tables.cell>
                        <x-tables.cell>
                            <div class="flex gap-2 items-center">
                                <div>
                                    <flux:avatar
                                        src="{{ !empty($student->user_photo) ? asset('storage/' . $student->user_photo) : '' }}"
                                        circle icon="user" />
                                </div>
                                <div class="flex flex-col items-start">
                                    <flux:text>
                                        {{ $student->student_name }}
                                    </flux:text>
                                    <flux:text size="sm" variant="soft">
                                        {{ $student->gender }}
                                    </flux:text>
                                </div>
                            </div>
                        </x-tables.cell>
                        <x-tables.cell>
                            <flux:text>{{ $student->nisn }}</flux:text>
                        </x-tables.cell>
                        <x-tables.cell>
                            <flux:text>{{ $student->branch_name }}</flux:text>
                        </x-tables.cell>
                        <x-tables.cell>
                            <flux:text>{{ $student->program_name }}</flux:text>
                        </x-tables.cell>
                        <x-tables.cell>
                            <flux:text>
                                @if ($student->is_scholarship)
                                    <flux:badge color="red" size="sm">
                                        Beasiswa
                                    </flux:badge>
                                @else
                                    <flux:badge color="primary" size="sm">
                                        Reguler
                                    </flux:badge>
                                @endif
                            </flux:text>
                        </x-tables.cell>
                        <x-tables.cell>
                            <a x-on:click.stop>
                                <flux:dropdown offset="-5" gap="1">
                                    <flux:button variant="ghost" size="xs">
                                        <flux:icon.ellipsis-vertical variant="micro" class="text-white" />
                                    </flux:button>
                                    <flux:menu>
                                        <flux:modal.trigger name="add-edit-admission-modal" wire:click="#">
                                            <flux:menu.item icon="eye">Detail</flux:menu.item>
                                        </flux:modal.trigger>
                                    
                                        <flux:modal.trigger name="walkout-student-modal({{ $student->id }})">
                                            <flux:menu.item icon="footprints">Mengundurkan Diri</flux:menu.item>
                                        </flux:modal.trigger>

                                        <flux:modal.trigger name="delete-student-modal({{ $student->id }})">
                                            <flux:menu.item icon="trash">Hapus</flux:menu.item>
                                        </flux:modal.trigger>
                                    </flux:menu>
                                </flux:dropdown>
                            </a>  
                        </x-tables.cell>
                    </x-tables.row>

                    <!--ANCHOR - MODAL DELETE STUDENT-->
                    <x-modals.delete-modal modalName="delete-student-modal({{ $student->id }})" :isMobile="$isMobile"
                        wire:click="deleteStudent('{{ Crypt::encrypt($student->id) }}')">
                        <x-slot:heading size="xl">Konfirmasi Hapus Siswa</x-slot:heading>
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
                            Apakah anda yakin ingin menghapus data siswa a/n <strong>{{ $student->student_name }}</strong>?
                            <br /><br />
                            <div class="flex items-center text-amber-400 gap-2">
                                <flux:icon.triangle-alert />
                                <flux:text class="text-amber-400">Data yang sudah dihapus, tidak bisa dikembalikan!
                                </flux:text>
                            </div>
                        </x-slot:content>

                        @if (!session('error-id-delete'))
                            <x-slot:closeButton>Batal</x-slot:closeButton>
                            <x-slot:deleteButton>Hapus</x-slot:deleteButton>
                        @endif
                    </x-modals.delete-modal>
                    <!--#MODAL DELETE STUDENT-->

                    <!--ANCHOR - MODAL WALKOUT STUDENT-->
                    <flux:modal name="walkout-student-modal({{ $student->id }})" class="md:w-100 lg:w-120">
                        <div class="space-y-3">
                            <flux:heading size="xl">Konfirmasi Mengundurkan Diri</flux:heading>
                            
                            <!--Feedback when delete is failed-->
                            @if (session('error-set-walkout-student'))
                                <div class="mt-2">
                                    <x-notifications.basic-alert isCloseable="true">
                                        <x-slot:title>{{ session('error-set-walkout-student') }}</x-slot:title>
                                    </x-notifications.basic-alert>
                                </div>
                            @endif
                            <!--#Feedback when delete is failed-->

                            <flux:text variant="bold">
                                Apakah siswa a/n <strong class="text-primary-300">{{ $student->student_name }}</strong> ingin mengundurkan diri? Silahkan isi alasan nya di bawah ini:
                            </flux:text>

                            <flux:field>
                                <flux:label>Alasan Mengundurkan Diri</flux:label>
                                <flux:textarea wire:model="walkoutReason" placeholder="Tulis deskripsi alasan dengan jelas"/>
                                <flux:error name="walkoutReason" />
                            </flux:field>

                            <div class="flex justify-end-safe gap-2">
                                <flux:modal.close>
                                    <flux:button variant="filled">Batal</flux:button>
                                </flux:modal.close>
                                <flux:button variant="primary" wire:click="walkoutStudent('{{ Crypt::encrypt($student->id) }}')">Simpan</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                    <!--#MODAL WALKOUT STUDENT-->
                @empty
                    <x-tables.empty text="Tidak ada data yang ditemukan" :colspan="7" />
                @endforelse

                @if ($this->officialStudentLists->hasPages())
                    <x-slot:pagination>
                        {{ $this->officialStudentLists->links(data: ['scrollTo' => false]) }}
                    </x-slot:pagination>
                @endif
            </x-tables.basic-table>
        </div>
    </x-animations.fade-down>

</div>
