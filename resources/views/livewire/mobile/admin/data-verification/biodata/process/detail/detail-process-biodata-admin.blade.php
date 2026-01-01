<div>
    <x-cards.roll-over-page>
        <x-slot:label>{{ $studentDetail->academic_year }}</x-slot:label>

        <x-slot:heading>{{ $studentDetail->name }}</x-slot:heading>

        <x-slot:subHeading>
            <div class="flex items-center justify-start gap-3">
                <div class="flex items-center gap-1">
                    <flux:icon.school variant="micro" class="text-white/75"/>
                    <flux:text variant="soft">{{ $studentDetail->branch_name }}</flux:text>
                </div>
                <div class="flex items-center gap-1">
                    <flux:icon.book-marked variant="micro" class="text-white/75"/>
                    <flux:text variant="soft">{{ $studentDetail->program_name }}</flux:text>
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
                            <flux:text variant="dark" class="font-semibold">{{ $studentDetail->gender }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Tempat Lahir</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentDetail->birth_place }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Tanggal Lahir</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->birth_date) }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">NISN</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentDetail->nisn }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Sekolah Asal</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentDetail->old_school_name }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">NPSN Sekolah Asal</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentDetail->old_school_npsn ?? '-' }}</flux:text>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 space-y-3">
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Alamat</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentDetail->address }}</flux:text>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Alamat Sekolah Asal</flux:text>
                            <flux:text variant="dark" class="font-semibold">{{ $studentDetail->old_school_address }}</flux:text>
                        </div>
                    </div>
                </div>
                <!--#BIODATA STUDENT-->

                <!--ANCHOR: BIODATA PARENT-->
                <flux:heading size="xl" class="mb-3 mt-5">Biodata Orang Tua</flux:heading>

                @if ($studentDetail->parent->is_parent == true)
                    <!--NOTE: Father Data-->
                    <div class="mb-3">
                        <flux:separator text="Data Ayah"/>
                    </div>
                    <div class="grid grid-cols-2 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Nama Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->father_name }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tempat Lahir Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->father_birth_place }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tanggal Lahir Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->parent->father_birth_date) }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pendidikan Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->educationFather->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pekerjaan Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->jobFather->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Penghasilan Ayah</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->sallaryFather->name ?? '-' }}</flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">HP Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentDetail->parent->father_country_code }}{{ $studentDetail->parent->father_mobile_phone }}
                                </flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Alamat Ayah</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentDetail->parent->father_address }}
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
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->mother_name }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tempat Lahir Ibu</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->mother_birth_place }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tanggal Lahir Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->parent->mother_birth_date) }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pendidikan Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->educationMother->name ?? '-'}}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pekerjaan Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->jobMother->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Penghasilan Ibu</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->sallaryMother->name ?? '-' }}</flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">HP Ibu</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentDetail->parent->mother_country_code }}{{ $studentDetail->parent->mother_mobile_phone }}
                                </flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Alamat Ibu</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentDetail->parent->mother_address }}
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
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->guardian_name }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tempat Lahir Wali</flux:text>
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->guardian_birth_place }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Tanggal Lahir Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->parent->guardian_birth_date) }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pendidikan Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->educationGuardian->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Pekerjaan Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->jobGuardian->name ?? '-' }}</flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Penghasilan Wali</flux:text>    
                                <flux:text variant="dark" class="font-semibold">{{ $studentDetail->parent->sallaryGuardian->name ?? '-' }}</flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 space-y-3">
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">HP Wali</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentDetail->parent->guardian_country_code }}{{ $studentDetail->parent->guardian_mobile_phone }}
                                </flux:text>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Alamat Wali</flux:text>
                                <flux:text variant="dark" class="font-semibold">
                                    {{ $studentDetail->parent->guardian_address }}
                                </flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Guardian Data-->
                @endif
                <!--#BIODATA PARENT-->
            </x-animations.fade-down>

            <x-animations.fade-down showTiming="250">
                <flux:heading size="xl" class="mb-3 mt-5">Status Verifikasi</flux:heading>

                <form 
                    wire:submit="updateBiodataStatus"
                    x-data="formValidation({
                        biodataStatus: ['required'],
                    })">
                    <div class="grid grid-cols-1 mt-4 space-y-4 mb-4">
                        <div class="col-span-1">
                            <div class="w-full">
                                <flux:select 
                                label="Status Biodata"
                                wire:model='inputs.biodataStatus'
                                x-on:input="form.biodataStatus = $event.target.value; validate('biodataStatus')"
                                placeholder="--Pilih Satu--">
                                    <flux:select.option value="Valid">Valid</flux:select.option>
                                    <flux:select.option value="Tidak Valid">Tidak Valid</flux:select.option>
                                </flux:select>          
                            </div>
                        </div>

                        <!--NOTE: Show invalid message if it is revision before-->
                        @if ($studentDetail->biodata == \App\Enums\VerificationStatusEnum::PROCESS && ($studentDetail->biodata_error_msg != null))
                            <div class="col-span-1">
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Alasan Invalid Sebelumnya</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->biodata_error_msg }}</flux:text>
                                </div>
                            </div>
                        @endif
                        <!--#Show invalid message if it is revision before-->

                        <template x-if="$wire.inputs.biodataStatus == 'Tidak Valid'">
                            <div class="col-span-1">
                                <flux:textarea
                                label="Alasan Tidak Valid"
                                row="3"
                                placeholder="Tulis dengan jelas alasan dan instruksi memperbaiki nya"
                                wire:model="inputs.invalidReason"
                                />

                                <template x-if="errors.inputs.invalidReason">
                                    <flux:error name="inputs.invalidReason">
                                        <x-slot:message>
                                            <span x-text="errors.inputs.invalidReason"></span>
                                        </x-slot:message>
                                    </flux:error>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!--NOTE: Alert when save is error or failed-->
                    @if (session('error-update-biodata'))
                        <div class="grid grid-cols-1 mt-4">
                            <div class="col-span-1">
                                <x-notifications.basic-alert isCloseable="true">
                                    <x-slot:title>{{ session('error-update-biodata') }}</x-slot:title>
                                </x-notifications.basic-alert>
                            </div>
                        </div>
                    @endif
                    <!--#Alert when save is error or failed-->

                    <div class="flex justify-center mt-4">
                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            x-bind:disabled="!isSubmitActive"
                            :loading="false"
                            class="w-full"
                            icon="check-check">
                            <x-items.loading-indicator wireTarget="updateBiodataStatus">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>
                    </div>
                </form>

                <div class="flex justify-center mt-1 mb-4">
                    <flux:button 
                    icon="undo-2"
                    variant="filled" 
                    class="w-full" 
                    href="{{ route('admin.data_verification.biodata.process') }}" 
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
