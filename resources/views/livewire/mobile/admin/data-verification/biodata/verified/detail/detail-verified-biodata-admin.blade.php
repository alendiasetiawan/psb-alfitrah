<div>
    <x-cards.roll-over-page>
        <x-slot:label>{{ $studentQuery->academic_year }}</x-slot:label>

        <x-slot:heading>{{ $studentQuery->name }}</x-slot:heading>

        <x-slot:subHeading>
            <div class="flex items-center justify-start gap-3">
                <div class="flex items-center gap-1">
                    <flux:icon.school variant="micro" class="text-white/75"/>
                    <flux:text variant="soft">{{ $studentQuery->branch_name }}</flux:text>
                </div>
                <div class="flex items-center gap-1">
                    <flux:icon.book-marked variant="micro" class="text-white/75"/>
                    <flux:text variant="soft">{{ $studentQuery->program_name }}</flux:text>
                </div>
            </div>
        </x-slot:subHeading>

        <x-cards.soft-glass-card rounded="rounded-t-2xl">
            {{-- Divider --}}
            <div class="flex justify-center w-full pb-4 cursor-pointer rounded">
                <div class="rounded-full bg-gray-300/50 h-1.5 w-16"></div>
            </div>
            {{-- #Divider --}}

            <x-animations.fade-down showTiming="50">
                <!--ANCHOR: BIODATA STUDENT-->
                <flux:heading size="xl" class="mb-3">Biodata Santri</flux:heading>

                <div class="grid grid-cols-2 space-y-3">
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Jenis Kelamin</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentQuery->gender }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Tempat Lahir</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentQuery->birth_place }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Tanggal Lahir</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->birth_date) }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">NISN</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentQuery->nisn }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Sekolah Asal</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentQuery->old_school_name }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">NPSN Sekolah Asal</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentQuery->old_school_npsn ?? '-' }}</flux:text>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 space-y-3">
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Alamat</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentQuery->address }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Alamat Sekolah Asal</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentQuery->old_school_address }}</flux:text>
                        </div>
                    </div>
                </div>
                <!--#BIODATA STUDENT-->

                <!--ANCHOR: BIODATA PARENT-->
                <flux:heading size="xl" class="mb-3 mt-5">Biodata Orang Tua</flux:heading>

                @if ($studentQuery->parent->is_parent == TRUE)
                    <!--NOTE: Father Data-->
                    <div class="mb-3">
                        <flux:separator text="Data Ayah"/>
                    </div>
                    <div class="grid grid-cols-2 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Nama Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->father_name }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tempat Lahir Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->father_birth_place }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tanggal Lahir Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->parent->father_birth_date) }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pendidikan Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->educationFather->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pekerjaan Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->jobFather->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Penghasilan Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->sallaryFather->name ?? '-' }}</flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">HP Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentQuery->parent->father_country_code }}{{ $studentQuery->parent->father_mobile_phone }}
                                </flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Alamat Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentQuery->parent->father_address }}
                                </flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Father Data-->

                    <!--NOTE: Mother Data-->
                    <div class="mb-3 mt-3">
                        <flux:separator text="Data Ibu"/>
                    </div>
                    <div class="grid grid-cols-2 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Nama Ibu</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->mother_name }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tempat Lahir Ibu</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->mother_birth_place }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tanggal Lahir Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->parent->mother_birth_date) }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pendidikan Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->educationMother->name ??  '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pekerjaan Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->jobMother->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Penghasilan Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->sallaryMother->name ?? '-' }}</flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">HP Ibu</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentQuery->parent->mother_country_code }}{{ $studentQuery->parent->mother_mobile_phone }}
                                </flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Alamat Ibu</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentQuery->parent->mother_address }}
                                </flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Mother Data-->
                @else
                    <!--NOTE: Guardian Data-->
                    <div class="mb-3 mt-3">
                        <flux:separator text="Data Wali"/>
                    </div>
                    <div class="grid grid-cols-2 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Nama Wali</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->guardian_name }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tempat Lahir Wali</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->guardian_birth_place }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tanggal Lahir Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->parent->guardian_birth_date) }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pendidikan Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->educationGuardian->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pekerjaan Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->jobGuardian->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Penghasilan Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentQuery->parent->sallaryGuardian->name ?? '-' }}</flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">HP Wali</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentQuery->parent->guardian_country_code }}{{ $studentQuery->parent->guardian_mobile_phone }}
                                </flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Alamat Wali</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentQuery->parent->guardian_address }}
                                </flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Guardian Data-->
                @endif
                <!--#BIODATA PARENT-->
            </x-animations.fade-down>

            <x-animations.fade-down showTiming="250">
                <div class="flex justify-center mt-4 mb-4">
                    <flux:button 
                    icon="undo-2"
                    variant="filled" 
                    class="w-full" 
                    href="{{ route('admin.data_verification.biodata.verified') }}" 
                    wire:navigate>Kembali</flux:button>
                </div>
            </x-animations.fade-down>
        </x-cards.soft-glass-card>
    </x-cards.roll-over-page>

    @push('scripts')
        <script type="text/javascript">
            function preventBack() {
                window.history.forward();
            }

            setTimeout("preventBack()", 0);

            window.onunload = function () { null };
        </script>
    @endpush
</div>
