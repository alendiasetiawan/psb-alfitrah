<div>
    <x-navigations.breadcrumb>
        <x-slot:title>Detail Biodata Santri</x-slot:title>
        <x-slot:activePage>Informasi Detail Biodata Santri</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="grid grid-cols-1 mt-4">
        <div class="col-span-1">
            <!--ANCHOR: STUDENT DATA-->
            <x-cards.soft-glass-card rounded="rounded-lg">
                <flux:heading size="xl">Data Santri</flux:heading>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 space-y-4 mt-4">
                    <!--ANCHOR: Branch-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.briefcase class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Cabang</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->branch_name }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Branch-->

                    <!--ANCHOR: Program-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.book-open class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Program</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->program_name }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Program-->

                    <!--ANCHOR: Academic Year-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.graduation-cap class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Tahun Ajaran</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->academic_year }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Academic Year-->

                    <!--ANCHOR: Registration Number-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.file-digit class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Nomor Registrasi</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->reg_number }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Registration Number-->

                    <!--ANCHOR: Name-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.user class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Nama Santri</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->name }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Name-->

                    <!--ANCHOR: Gender-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.mars class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Jenis Kelamin</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->gender }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Gender-->

                    <!--ANCHOR: Birth Place-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.hospital class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Tempat Lahir</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->birth_place }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Birth Place-->

                    <!--ANCHOR: Birth Date-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.calendar-days class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Tanggal Lahir</flux:text>
                                <flux:text variant="solid">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->birth_date) }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Birth Date-->

                    <!--ANCHOR: NISN-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.user-lock class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">NISN 
                                        <strong class="text-primary-300">(Cek validasi 
                                            <a href="https://nisn.data.kemdikbud.go.id/" target="_blank"><u>disini</u></a>)
                                        </strong>
                                </flux:text>
                                <flux:text variant="solid">{{ $studentDetail->nisn }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#NISN-->

                    <!--ANCHOR: Mobile Phone-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.phone class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Nomor HP Utama</flux:text>
                                <flux:text variant="solid">+{{ $studentDetail->country_code }}{{ $studentDetail->mobile_phone }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Mobile Phone-->

                    <!--ANCHOR: Old School Name-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.school class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Nama Sekolah Asal</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->old_school_name }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Old School Name-->

                    <!--ANCHOR: Old School Number-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.user-lock class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">NPSN</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->npsn ?? "-" }}</flux:text>
                            </div>
                        </div>
                    </div>
                    <!--#Old School Number-->
                </div>

                <div class="grid md:grid-cols-2 space-y-4">
                    <!--ANCHOR: School Address-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.map class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Alamat Sekolah Asal</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->old_school_address }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#School Address-->

                    <!--ANCHOR: Address-->
                    <div class="col-span-1">
                        <div class="flex items-center gap-2">
                            <flux:icon.home class="text-white" variant="mini"/>

                            <div class="flex flex-col items-start">
                                <flux:text variant="soft" size="sm">Alamat Santri</flux:text>
                                <flux:text variant="solid">{{ $studentDetail->address }}</flux:text>

                            </div>
                        </div>
                    </div>
                    <!--#Address-->
                </div>
            </x-cards.soft-glass-card>
            <!--#STUDENT DATA-->

            <!--ANCHOR: PARENT'S DATA-->
            <x-cards.soft-glass-card rounded="rounded-lg" class="mt-4">
                <flux:heading size="xl" class="mb-4">Data Orang Tua</flux:heading>

                @if ($studentDetail->parent->is_parent == TRUE)
                    <!---NOTE: Father Data-->
                    <flux:separator text="Data Ayah" />
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 space-y-4 mt-4">
                        <!--ANCHOR: Father's Name-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.user class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Nama Lengkap</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->father_name }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Father's Name-->

                        <!--ANCHOR: Father's Birth Place-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.hospital class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Tempat Lahir</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->father_birth_place }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father's Birth Place-->

                        <!--ANCHOR: Father's Brith Date-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.calendar-days class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Tanggal Lahir</flux:text>
                                    <flux:text variant="solid">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->parent->father_birth_date ) }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father's Brith Date-->

                        <!--ANCHOR: Father's Last Education-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.book-marked class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Pendidikan Terakhir</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->educationFather->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father's Last Education-->

                        <!--ANCHOR: Father's Job-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.briefcase class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Pekerjaan</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->jobFather->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father's Job-->

                        <!--ANCHOR: Father's Sallary-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.banknotes class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Penghasilan</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->sallaryFather->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Father's Sallary-->
                    </div>
                    
                    <div class="grid md:grid-cols-2 space-y-4">
                        <!--ANCHOR: Father's Phone-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.phone class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Nomor HP</flux:text>
                                    <flux:text variant="solid">+{{ $studentDetail->parent->father_country_code }}{{ $studentDetail->parent->father_mobile_phone }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Father's Phone-->

                        <!--ANCHOR: Father's Address-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.home class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Alamat</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->father_address }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Father's Address-->
                    </div>

                    <!---NOTE: Mother Data-->
                    <flux:separator text="Data Ibu" />
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 space-y-4 mt-4">
                        <!--ANCHOR: Mother's Name-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.user class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Nama Lengkap</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->mother_name }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Mother's Name-->

                        <!--ANCHOR: Mother's Birth Place-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.hospital class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Tempat Lahir</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->mother_birth_place }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother's Birth Place-->

                        <!--ANCHOR: Mother's Brith Date-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.calendar-days class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Tanggal Lahir</flux:text>
                                    <flux:text variant="solid">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->parent->mother_birth_date ) }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother's Brith Date-->

                        <!--ANCHOR: Mother's Last Education-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.book-marked class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Pendidikan Terakhir</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->educationMother->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother's Last Education-->

                        <!--ANCHOR: Mother's Job-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.briefcase class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Pekerjaan</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->jobMother->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother's Job-->

                        <!--ANCHOR: Mother's Sallary-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.banknotes class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Penghasilan</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->sallaryMother->name }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Mother's Sallary-->
                    </div>
                    
                    <div class="grid md:grid-cols-2 space-y-4">
                        <!--ANCHOR: Mother's Phone-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.phone class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Nomor HP</flux:text>
                                    <flux:text variant="solid">+{{ $studentDetail->parent->mother_country_code }}{{ $studentDetail->parent->mother_mobile_phone }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Mother's Phone-->

                        <!--ANCHOR: Mother's Address-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.home class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Alamat</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->mother_address }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Mother's Address-->
                    </div>
                @else
                    <!---NOTE: Guardian Data-->
                    <flux:separator text="Data Wali" />
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 space-y-4 mt-4">
                        <!--ANCHOR: Guardian's Name-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">   
                                <flux:icon.user class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Nama Lengkap</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->guardian_name }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Name-->

                        <!--ANCHOR: Guardian's Birth Place-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.hospital class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Tempat Lahir</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->guardian_birth_place }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Birth Place-->

                        <!--ANCHOR: Guardian's Brith Date-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.calendar-days class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Tanggal Lahir</flux:text>
                                    <flux:text variant="solid">{{ \App\Helpers\DateFormatHelper::longIndoDate($studentDetail->parent->guardian_birth_date ) }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Brith Date-->

                        <!--ANCHOR: Guardian's Last Education-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.book-marked class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Pendidikan Terakhir</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->educationGuardian->name ?? '-' }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Last Education-->

                        <!--ANCHOR: Guardian's Job-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.briefcase class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Pekerjaan</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->jobGuardian->name ?? '-' }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Job-->

                        <!--ANCHOR: Guardian's Sallary-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.banknotes class="text-white" variant="mini"/>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Penghasilan</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->sallaryGuardian->name ?? '-' }}</flux:text>
                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Sallary-->
                    </div>
                    
                    <div class="grid md:grid-cols-2 space-y-4">
                        <!--ANCHOR: Guardian's Phone-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.phone class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Nomor HP</flux:text>
                                    <flux:text variant="solid">+{{ $studentDetail->parent->guardian_country_code }}{{ $studentDetail->parent->guardian_mobile_phone }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Phone-->

                        <!--ANCHOR: Guardian's Address-->
                        <div class="col-span-1">
                            <div class="flex items-center gap-2">
                                <flux:icon.home class="text-white" variant="mini"/>

                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Alamat</flux:text>
                                    <flux:text variant="solid">{{ $studentDetail->parent->guardian_address }}</flux:text>

                                </div>
                            </div>
                        </div>
                        <!--#Guardian's Address-->
                    </div>
                @endif
            </x-cards.soft-glass-card>
            <!--#PARENT'S DATA-->

            <!--ANCHOR: ACTION-->
            <x-cards.soft-glass-card rounded="rounded-lg" class="mt-4">
                <flux:heading size="xl">Hasil Verifikasi</flux:heading>

                <form 
                    wire:submit="updateBiodataStatus"
                    x-data="formValidation({
                        biodataStatus: ['required'],
                    })">
                    <div class="grid md:grid-cols-2 mt-4 mb-4">
                        <div class="col-span-1">
                            <div class="w-3/6">
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

                    <div class="flex justify-start gap-2 mt-2">
                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            x-bind:disabled="!isSubmitActive"
                            :loading="false"
                            icon="check-check">
                            <x-items.loading-indicator wireTarget="updateBiodataStatus">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>

                        <flux:button icon="undo-2" variant="filled" href="{{ route('admin.data_verification.biodata.process') }}" wire:navigate>
                            Kembali
                        </flux:button>
                    </div>
                </form>
            </x-cards.soft-glass-card>
            <!--#ACTION-->
        </div>
    </div>
</div>
