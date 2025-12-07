<div>
    <x-navigations.breadcrumb secondLink="{{ route('admin.master_data.student_database.index') }}">
        <x-slot:title>{{ __('Detail Data Siswa') }}</x-slot:title>
        <x-slot:secondPage>{{ __('Database Siswa') }}</x-slot:secondPage>
        <x-slot:activePage>{{ __('Informasi Data dan Berkas Siswa') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    
    <!--ANCHOR - HERO COVER STUDENT PROFILE-->
    <x-animations.fade-down showTiming="50">
        <div class="grid grid-cols-1 mt-4">
            <div class="relative overflow-hidden rounded-lg">
                {{-- MASKED GRADIENT BORDER (Tailwind v4) --}}
                <div class="
                    pointer-events-none absolute inset-0
                    p-[2px] rounded-lg
                    bg-gradient-to-br from-white/20 via-white/10 to-white/5
                    shadow-[inset_0_2px_2px_rgba(255,255,255,0.7)]
                    z-[1]
                    ">
                </div>

                {{-- LIQUID GEL BORDER (soft jelly shine) --}}
                <div class="
                    pointer-events-none absolute inset-0 z-[4]
                    p-[3px] rounded-lg
                    [@background:linear-gradient(
                        135deg,
                        rgba(255,255,255,0.55),
                        rgba(255,255,255,0.25),
                        rgba(255,255,255,0.15),
                        rgba(255,255,255,0.45)
                    )]
                    [mask:linear-gradient(#fff_0_0)]
                    blur-[2px] opacity-95
                "></div>

                {{-- GEL CORE BLUR (soft & thick) --}}
                <div class="
                    absolute inset-0 rounded-lg
                    backdrop-blur-[30px]
                    bg-white/12 dark:bg-white/6
                    shadow-[inset_0_0_25px_rgba(255,255,255,0.25)]
                    z-[0]
                "></div>

                <!-- Hero Background -->
                <div class="relative h-64 w-full">
                    <img src="{{ asset('images/background/class.webp') }}"
                        class="w-full h-full object-cover" />
                </div>

                <!-- Content -->
                <div class="relative p-8 flex items-center justify-between">
                    <!-- Left section -->
                    <div class="flex items-center gap-6">
                        <!-- Avatar -->
                        <div class="absolute -top-15">
                            <div class="w-40 h-40 rounded-2xl overflow-hidden shadow-xl border-4 border-white">
                                @if (!empty($studentQuery->photo))
                                    <img src="{{ asset($studentQuery->photo) }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="flex items-center justify-center bg-white/80">
                                        <flux:icon.user class="size-38 text-dark/80"/>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="ml-44">
                            <flux:heading size="3xl" class="font-semibold">
                                {{ $studentQuery->name }}
                            </flux:heading>

                            <div class="flex items-center gap-6 text-white/75 mt-2">
                                <div class="flex items-center gap-2">
                                    <flux:icon.school variant="mini"/>
                                    {{ $studentQuery->branch_name }}
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.book-marked variant="mini"/>
                                    {{ $studentQuery->program_name }}
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.graduation-cap variant="mini"/>
                                    {{ $studentQuery->academic_year }}
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.file-digit variant="mini"/>
                                    {{ $studentQuery->reg_number }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right section - Connected button -->
                    <flux:button 
                        variant="primary" 
                        color="green" 
                        icon="message-circle-more" 
                        href="https://wa.me/{{ config('services.whatsapp.phone') }}" 
                        target="_blank">
                            Chat Whatsapp
                    </flux:button>
                </div>
            </div>
        </div>
    </x-animations.fade-down>
    <!--#HERO COVER STUDENT PROFILE-->

    <!--ANCHOR - BIODATA STUDENT-->
    <x-animations.fade-down showTiming="150">
        <div class="grid grid-cols-1 mt-4">
            <x-cards.soft-glass-card rounded="rounded-lg">
                <flux:heading size="xl" class="font-semibold">Biodata Siswa</flux:heading>

                <div class="grid grid-cols-3 space-y-4 mt-4">
                    <!--Gender-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.mars class="text-white" variant="mini"/>
                                <flux:text size="lg">Jenis Kelamin</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ $studentQuery->gender }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Gender-->

                    <!--Birth Place-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.hospital class="text-white" variant="mini"/>
                                <flux:text size="lg">Tempat Lahir</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ $studentQuery->birth_place }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Birth Place-->

                    <!--Birth Date-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.calendar-days class="text-white" variant="mini"/>
                                <flux:text size="lg">Tanggal Lahir</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->birth_date) }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Birth Date-->

                    <!--NISN-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.shield-user class="text-white" variant="mini"/>
                                <flux:text size="lg">NISN</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ $studentQuery->nisn }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#NISN-->

                    <!--Old School Name-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.university class="text-white" variant="mini"/>
                                <flux:text size="lg">Sekolah Asal</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ $studentQuery->old_school_name }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Old School Name-->

                    <!--NPSN-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.shield-check class="text-white" variant="mini"/>
                                <flux:text size="lg">NPSN Sekolah Asal</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ $studentQuery->npsn ?? '-' }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#NPSN-->
                </div>

                <div class="grid grid-cols-2">
                    <!--Student Address-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.map-pin-house class="text-white" variant="mini"/>
                                <flux:text size="lg">Alamat Siswa</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ $studentQuery->address }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Student Address-->

                    <!--Old School Address-->
                    <div class="col-span-1">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <flux:icon.map-pinned class="text-white" variant="mini"/>
                                <flux:text size="lg">Alamat Sekolah Asal</flux:text>
                            </div>

                            <div class="flex">
                                <flux:text variant="soft">{{ $studentQuery->old_school_address }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Old School Address-->
                </div>
            </x-cards.soft-glass-card>
        </div>
    </x-animations.fade-down>
    <!--#BIODATA STUDENT-->

    <!--ANCHOR: BIODATA PARENT-->
    <x-animations.fade-down showTiming="250">
        <div class="grid grid-cols-1 mt-4">
            <x-cards.soft-glass-card rounded="rounded-lg">
                <flux:heading size="xl" class="font-semibold mb-4">Biodata Orang Tua/Wali</flux:heading>

                @if ($studentQuery->parent->is_parent)                    
                    <!--NOTE: Father Data-->
                    <flux:separator text="Data Ayah" />

                    <div class="grid grid-cols-3 space-y-4 mt-4">
                        <!--Father Name-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.user class="text-white" variant="mini"/>
                                    <flux:text size="lg">Nama Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->father_name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Name-->

                        <!--Father Birth Place-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.hospital class="text-white" variant="mini"/>
                                    <flux:text size="lg">Tempat Lahir Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->father_birth_place }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Birth Place-->

                        <!--Father Birth Date-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.calendar-days class="text-white" variant="mini"/>
                                    <flux:text size="lg">Tanggal Lahir Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->parent->father_birth_date) }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Birth Date-->

                                                <!--Father Education-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.book-open-check class="text-white" variant="mini"/>
                                    <flux:text size="lg">Pendidikan Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->educationFather->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Education-->

                        <!--Father Job-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.briefcase-business class="text-white" variant="mini"/>
                                    <flux:text size="lg">Pekerjaan Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->jobFather->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Job-->

                        <!--Father Penghasilan-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.hand-coins class="text-white" variant="mini"/>
                                    <flux:text size="lg">Penghasilan Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->sallaryFather->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Penghasilan-->  
                    </div>

                    <div class="grid grid-cols-2 space-y-4">
                        <!--Father Mobile Phone-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.smartphone class="text-white" variant="mini"/>
                                    <flux:text size="lg">HP Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">
                                        {{ $studentQuery->parent->father_country_code }}{{ $studentQuery->parent->father_mobile_phone }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Mobile Phone-->

                        <!--Father Address-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.map-pin-house class="text-white" variant="mini"/>
                                    <flux:text size="lg">Alamat Ayah</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">
                                        {{ $studentQuery->parent->father_address }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father Address-->
                    </div>
                    <!--#Father Data-->

                    <!--NOTE: Mother Data-->
                    <flux:separator text="Data Ibu"/>

                    <div class="grid grid-cols-3 space-y-4 mt-4">
                        <!--Mother Name-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.user class="text-white" variant="mini"/>
                                    <flux:text size="lg">Nama Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->mother_name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Name-->

                        <!--Mother Birth Place-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.hospital class="text-white" variant="mini"/>
                                    <flux:text size="lg">Tempat Lahir Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->mother_birth_place }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Birth Place-->

                        <!--Mother Birth Date-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.calendar-days class="text-white" variant="mini"/>
                                    <flux:text size="lg">Tanggal Lahir Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->parent->mother_birth_date ) }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Birth Date-->

                                                <!--Mother Education-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.book-open-check class="text-white" variant="mini"/>
                                    <flux:text size="lg">Pendidikan Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->educationMother->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Education-->

                        <!--Mother Job-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.briefcase-business class="text-white" variant="mini"/>
                                    <flux:text size="lg">Pekerjaan Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->jobMother->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Job-->

                        <!--Mother Penghasilan-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.hand-coins class="text-white" variant="mini"/>
                                    <flux:text size="lg">Penghasilan Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->sallaryMother->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Penghasilan-->   
                    </div>

                    <div class="grid grid-cols-2">
                        <!--Mother Mobile Phone-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.smartphone class="text-white" variant="mini"/>
                                    <flux:text size="lg">HP Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">
                                        {{ $studentQuery->parent->mother_country_code }}{{ $studentQuery->parent->mother_mobile_phone }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Mobile Phone-->

                        <!--Mother Address-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.map-pin-house class="text-white" variant="mini"/>
                                    <flux:text size="lg">Alamat Ibu</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">
                                        {{ $studentQuery->parent->mother_address }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother Address-->
                    </div>
                    <!--#Mother Data-->
                @else
                    <!--NOTE: Guardian Data-->
                    <flux:separator text="Data Wali"/>

                    <div class="grid grid-cols-3 space-y-4 mt-4">
                        <!--Guardian Name-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.user class="text-white" variant="mini"/>
                                    <flux:text size="lg">Nama Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->guardian_name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Name-->

                        <!--Guardian Birth Place-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.hospital class="text-white" variant="mini"/>
                                    <flux:text size="lg">Tempat Lahir Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->guardian_birth_place }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Birth Place-->

                        <!--Guardian Birth Date-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.calendar-days class="text-white" variant="mini"/>
                                    <flux:text size="lg">Tanggal Lahir Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentQuery->parent->guardian_birth_date ) }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Birth Date-->

                                                <!--Guardian Education-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.book-open-check class="text-white" variant="mini"/>
                                    <flux:text size="lg">Pendidikan Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->educationGuardian->name ?? '-' }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Education-->

                        <!--Guardian Job-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.briefcase-business class="text-white" variant="mini"/>
                                    <flux:text size="lg">Pekerjaan Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->jobGuardian->name ?? '-' }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Job-->

                        <!--Guardian Penghasilan-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.hand-coins class="text-white" variant="mini"/>
                                    <flux:text size="lg">Penghasilan Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">{{ $studentQuery->parent->sallaryGuardian->name ?? '-' }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Penghasilan--> 
                    </div>

                    <div class="grid grid-cols-2">
                        <!--Guardian Mobile Phone-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.smartphone class="text-white" variant="mini"/>
                                    <flux:text size="lg">HP Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">
                                        {{ $studentQuery->parent->guardian_country_code }}{{ $studentQuery->parent->guardian_mobile_phone }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Mobile Phone-->

                        <!--Guardian Address-->
                        <div class="col-span-1">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1">
                                    <flux:icon.map-pin-house class="text-white" variant="mini"/>
                                    <flux:text size="lg">Alamat Wali</flux:text>
                                </div>

                                <div class="flex">
                                    <flux:text variant="soft">
                                        {{ $studentQuery->parent->guardian_address }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian Address-->
                    </div>
                    <!--#Guardian Data-->
                @endif
            </x-cards.soft-glass-card>
        </div>
    </x-animations.fade-down>
    <!--#BIODATA PARENT-->

    <!--ANCHOR: STUDENT ATTACHMENT-->
    <x-animations.fade-down showTiming="350">
        <div class="grid grid-cols-1 mt-4">
            <x-cards.soft-glass-card rounded="rounded-lg">
                <flux:heading size="xl" class="font-semibold mb-4">Berkas Siswa</flux:heading>

                <div class="grid grid-cols-2 space-y-4">
                    <!--Student's Photo-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start gap-1">
                            <flux:text size="lg">Photo Siswa</flux:text>
                            <x-cards.photo-frame>
                                <img src="{{ asset($studentQuery->photo) }}" class="w-full h-full object-cover" />
                            </x-cards.photo-frame>
                        </div>
                    </div>
                    <!--#Student's Photo-->

                    <!--Student's Birth Card-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start gap-1">
                            <flux:text size="lg">Akte Kelahiran</flux:text>
                            <x-cards.photo-frame>
                                <img src="{{ asset($studentQuery->born_card) }}" class="w-full h-full object-cover" />
                            </x-cards.photo-frame>
                        </div>
                    </div>
                    <!--#Student's Birth Card-->

                    <!--Student's Parent Card-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start gap-1">
                            <flux:text size="lg">KTP Orang Tua</flux:text>
                            <x-cards.photo-frame>
                                <img src="{{ asset($studentQuery->parent_card) }}" class="w-full h-full object-cover" />
                            </x-cards.photo-frame>
                        </div>
                    </div>
                    <!--#Student's Parent Card-->

                    <!--Student's Family Card-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start gap-1">
                            <flux:text size="lg">Kartu Keluaga</flux:text>
                            <x-cards.photo-frame>
                                <img src="{{ asset($studentQuery->family_card) }}" class="w-full h-full object-cover" />
                            </x-cards.photo-frame>
                        </div>
                    </div>
                    <!--#Student's Family Card-->
                </div>

                <div class="flex jusfity-start">
                    <flux:button variant="filled" icon="arrow-left" href="{{ route('admin.master_data.student_database.index') }}" wire:navigate>
                        Kembali
                    </flux:button>
                </div>
            </x-cards.soft-glass-card>
        </div>
    </x-animations.fade-down>
    <!--#STUDENT ATTACHMENT-->
</div>
