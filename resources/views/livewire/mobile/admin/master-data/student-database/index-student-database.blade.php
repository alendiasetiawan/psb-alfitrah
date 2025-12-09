<div class="mb-18">
    <!--ANCHOR - COUNTER STUDENT STATISTIC-->
    <x-animations.fade-down showTiming="50">
        <div wire:replace>
            <x-swipers.creative-swiper>
                @foreach ($this->totalStudents as $total)
                    <x-cards.counter-card subCounterColor="soft-white" class="swiper-slide" style="width: 85vw;">
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
                            <flux:icon.school class="size-15 text-primary-400" />
                        </x-slot:subIcon>
                    </x-cards.counter-card>
                @endforeach
            </x-swipers.creative-swiper>
        </div>
    </x-animations.fade-down>

    <!--ANCHOR - SEARCH AND FILTERS-->
    <x-animations.sticky scrollHeight="160">
        <x-animations.fade-down showTiming="100">
            <div class="grid grid-cols-1 mt-4">
                <div class="flex gap-2 items-center">
                    <div class="w-11/12">
                        <flux:input placeholder="Cari nama santri" wire:model.live.debounce.500ms="searchStudent"
                            icon="search" />
                    </div>

                    <div class="w-1/12">
                        <flux:modal.trigger name="filter-student-modal">
                            <flux:icon.sliders-horizontal class="hover:cursor-pointer text-primary-400" />
                        </flux:modal.trigger>
                    </div>
                </div>
            </div>
        </x-animations.fade-down>
    </x-animations.sticky>
    <!--#SEARCH AND FILTERS-->

    <!--ANCHOR - LIST OF STUDENTS-->
    <x-animations.fade-down showTiming="150">
        <div class="grid grid-cols-1 gap-3 mt-4">
            <!--ANCHOR - Loading Skeleton-->
            <div class="col-span-1" wire:loading wire:target="searchStudent">
                <div class="animate-pulse">
                    <x-loading.profile-card-skeleton />
                </div>
            </div>
            <!--#Loading Skeleton-->

            @forelse ($this->officialStudentLists as $student)
                <div class="col-span-1" wire:loading.remove wire:target="searchStudent">
                    <x-cards.profile-card
                        avatarInitial="{{ \App\Helpers\FormatStringHelper::initials($student->student_name) }}"
                        avatarImage="{{ !empty($student->user_photo) ? asset('storage/' . $student->user_photo) : '' }}"
                        wire:key="registrant-{{ $student->id }}">
                        <x-slot:title>{{ $student->student_name }}</x-slot:title>

                        <x-slot:subTitle>
                            {{ $student->nisn }}
                            |
                            {{ $student->is_scholarship ? 'Beasiswa' : 'Reguler' }}
                        </x-slot:subTitle>

                        <x-slot:actionMenu>
                            <x-slot:menuItem>
                                <flux:menu.item icon="eye" wire:click="openDetailStudentDatabasePage('{{ Crypt::encrypt($student->id) }}')">Detail</flux:menu.item>

                                <flux:modal.trigger name="walkout-student-modal({{ $student->id }})">
                                    <flux:menu.item icon="footprints">Mengundurkan Diri</flux:menu.item>
                                </flux:modal.trigger>

                                <flux:modal.trigger name="delete-student-modal({{ $student->id }})">
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
                    <x-modals.delete-modal modalName="delete-student-modal({{ $student->id }})" :isMobile="$isMobile"
                        wire:click="deleteStudent('{{ Crypt::encrypt($student->id) }}')">
                        <x-slot:heading>Konfirmasi Hapus Siswa</x-slot:heading>
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
                            Apakah anda yakin ingin menghapus data siswa a/n
                            <strong>{{ $student->student_name }}</strong>?
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
                    <flux:modal name="walkout-student-modal({{ $student->id }})" class="md:w-100 lg:w-120"
                        variant="flyout" position="bottom">
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
                                Apakah siswa a/n <strong class="text-primary-300">{{ $student->student_name }}</strong>
                                ingin mengundurkan diri? Silahkan isi alasan nya di bawah ini:
                            </flux:text>

                            <flux:field>
                                <flux:label>Alasan Mengundurkan Diri</flux:label>
                                <flux:textarea wire:model="walkoutReason"
                                    placeholder="Tulis deskripsi alasan dengan jelas" />
                                <flux:error name="walkoutReason" />
                            </flux:field>

                            <div class="flex justify-end-safe gap-2">
                                <flux:modal.close>
                                    <flux:button variant="filled">Batal</flux:button>
                                </flux:modal.close>
                                <flux:button variant="primary"
                                    wire:click="walkoutStudent('{{ Crypt::encrypt($student->id) }}')">Simpan
                                </flux:button>
                            </div>
                        </div>
                    </flux:modal>
                    <!--#MODAL WALKOUT STUDENT-->
                </div>
            @empty
                <x-animations.not-found />
            @endforelse

            <!--ANCHOR - Load More Button-->
            @if ($this->officialStudentLists->hasMorePages())
                <livewire:components.buttons.load-more loadItem="20" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>

    <!--ANCHOR - FLYOUT MODAL FILTER STUDENT-->
    <flux:modal name="filter-student-modal" variant="flyout" class="w-11/12" closable="true">
        <flux:heading size="xl">Filter Data</flux:heading>
        <div class="space-y-4 mt-5">
            <!--Filter Academic Year-->
            <div class="grid grid-cols-1">
                <flux:select wire:model="filterAdmissionId" label="Pilih Tahun Ajaran">
                    @foreach ($admissionYearLists as $key => $value)
                        <flux:select.option value="{{ $key }}">{{ $value }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <!--#Filter Academic Year-->

            <!--Button Download Excel-->
            <div class="grid grid-cols-1">
                <flux:dropdown>
                    <flux:button 
                        icon:trailing="download" 
                        class="hover:cursor-pointer"
                        size="sm"
                        loading="loading-export-excel">
                            Excel Data Induk
                    </flux:button>

                    <flux:menu>
                        @foreach ($branchLists as $key => $branch)
                            <flux:menu.item icon="school" wire:click="exportExcel({{ $key }})">
                                {{ $branch }}
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>    
            </div>
            <!--#Button Download Excel-->

            <!--Button Action-->
            <div class="fixed bottom-0 left-0 right-0 p-6">
                <flux:modal.close>
                    <flux:button variant="primary" class="w-full" wire:click="setSelectedAdmissionId">Terapkan</flux:button>
                </flux:modal.close>
            </div>
            <!--#Button Action-->
        </div>
    </flux:modal>
    <!--#MODAL FILTER STUDENT-->

</div>
