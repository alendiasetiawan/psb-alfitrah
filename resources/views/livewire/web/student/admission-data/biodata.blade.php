<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Biodata') }}</x-slot:title>
        <x-slot:activePage>{{ __('Pengisian Biodata Siswa') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="flex justify-start mt-3" wire:ignore>
        <x-navigations.pill-tab hrefOne="{{ route('student.admission_data.biodata') }}"
            hrefTwo="{{ route('student.admission_data.admission_attachment') }}">
            <x-slot:tabOne>{{ __('Biodata') }}</x-slot:tabOne>
            <x-slot:tabTwo>{{ __('Berkas') }}</x-slot:tabTwo>
        </x-navigations.pill-tab>
    </div>

    <!--Alert if registration payment not valid-->
    @if ($detailStudent->registration_payment != \App\Enums\VerificationStatusEnum::VALID)
    <div class="grid grid-cols-1 mt-4">
        <div class="col-span-1">
            <x-notifications.basic-alert>
                <x-slot:title>
                    Mohon maaf, anda tidak bisa mengisi biodata sebelum menyelesaikan pembayaran.
                    <a href="{{ route('student.payment.registration_payment') }}" wire:navigate>
                        <strong><u>Bayar Disini</u></strong>
                    </a>
                </x-slot:title>
            </x-notifications.basic-alert>
        </div>
    </div>
    @endif
    <!--Alert if registration payment not valid-->

    <!--Alert when biodata is being verified-->
    @if ($detailStudent->biodata == \App\Enums\VerificationStatusEnum::PROCESS)
    <div class="grid grid-cols-1 mt-4">
        <div class="col-span-1">
            <x-notifications.basic-alert variant="warning" icon="exclamation-circle">
                <x-slot:title>
                    Kami sedang melakukan pengecakan biodata anda, mohon kesediaannya untuk menunggu. Terima Kasih
                </x-slot:title>
            </x-notifications.basic-alert>
        </div>
    </div>
    @endif
    <!--#Alert when biodata is being verified-->

    <!--Alert when biodata is invalid-->
    @if ($detailStudent->biodata == \App\Enums\VerificationStatusEnum::INVALID)
    <div class="grid grid-cols-1 mt-4">
        <div class="col-span-1">
            <x-notifications.basic-alert variant="danger" icon="x-circle">
                <x-slot:title>
                    Biodata Tidak Valid
                </x-slot:title>
                <x-slot:subTitle>
                    Alasan : {{ $detailStudent->biodata_error_msg }}
                </x-slot:subTitle>
            </x-notifications.basic-alert>
        </div>
    </div>
    @endif
    <!--#Alert when biodata is invalid-->

    <div class="grid lg:grid-cols-6 mt-4 gap-4">
        <!--Overview Data-->
        <div class="lg:col-span-2">
            <x-animations.fade-down showTiming="50">
                <x-cards.soft-glass-card>
                    @if ($detailStudent->biodata == \App\Enums\VerificationStatusEnum::VALID)
                    <div class="flex flex-col justify-center items-center">
                        <flux:badge color="green" variant="solid" icon="circle-check-big">Biodata Valid</flux:badge>
                    </div>
                    @endif

                    <div class="flex flex-col justify-center items-center py-4">
                        <flux:avatar size="xxl" class="mb-2" 
                        icon="user"
                        src="{{ !empty($detailStudent->parent->user_photo) ? asset('storage/' . $detailStudent->parent->user_photo) : '' }}"
                        />
                        <flux:heading size="xxl">{{ $detailStudent->name }}</flux:heading>
                        <flux:text variant="soft">{{ $detailStudent->reg_number }}</flux:text>
                    </div>

                    <div class="flex flex-col justify-start items-start mt-3 gap-2">
                        <div>
                            <flux:text variant="bold" size="lg">Username</flux:text>
                            <flux:text variant="soft">{{ $detailStudent->parent->username }}</flux:text>
                        </div>
                        <div>
                            <flux:text variant="bold" size="lg">Cabang</flux:text>
                            <flux:text variant="soft">{{ $detailStudent->branch_name }}</flux:text>
                        </div>
                        <div>
                            <flux:text variant="bold" size="lg">Program</flux:text>
                            <flux:text variant="soft">{{ $detailStudent->program_name }}</flux:text>
                        </div>
                        <div>
                            <flux:text variant="bold" size="lg">Tahun Ajaran</flux:text>
                            <flux:text variant="soft">{{ $detailStudent->academic_year }}</flux:text>
                        </div>
                    </div>
                </x-cards.soft-glass-card>
            </x-animations.fade-down>
        </div>
        <!--#Overview Data-->

        <!--Persontal Data-->
        <div class="lg:col-span-4">
            <x-animations.fade-down showTiming="50">
                <x-cards.soft-glass-card>
                    <flux:heading size="xl" class="mb-4" variant="bold">Data Pribadi</flux:heading>

                    <form wire:submit='saveBiodata' x-data="
                        formValidation({
                            studentName: ['required', 'minLength:3'],
                            gender: ['required'],
                            birthPlace: ['required', 'minLength:5'],
                            birthDate: ['required'],
                            nisn: ['required', 'minLength:10', 'maxLength:10'],
                            address: ['required', 'minLength:20', 'maxLength:500'],
                            oldSchoolName: ['required', 'minLength:5'],
                            oldSchoolAddress: ['required', 'minLength:20', 'maxLength:500'],
                            selectedProvinceId: ['required'],
                            selectedRegencyId: ['required'],
                            selectedDistrictId: ['required'],
                            selectedVillageId: ['required'],
                        })" 
                        x-init="$watch('$wire.form.inputs.address', value => {
                            if (sameAddressAsStudent) $wire.form.inputs.fatherAddress = value,
                            $wire.form.inputs.motherAddress = value,
                            $wire.form.inputs.guardianAddress = value
                        });
                        form.studentName = $wire.form.inputs.studentName;
                        form.gender = $wire.form.inputs.gender;
                        form.birthPlace = $wire.form.inputs.birthPlace;
                        form.birthDate = $wire.form.inputs.birthDate;
                        form.nisn = $wire.form.inputs.nisn;
                        form.address = $wire.form.inputs.address;
                        form.oldSchoolName = $wire.form.inputs.oldSchoolName;
                        form.oldSchoolAddress = $wire.form.inputs.oldSchoolAddress;
                        form.selectedProvinceId = $wire.form.inputs.selectedProvinceId;
                        form.selectedRegencyId = $wire.form.inputs.selectedRegencyId;
                        form.selectedDistrictId = $wire.form.inputs.selectedDistrictId;
                        form.selectedVillageId = $wire.form.inputs.selectedVillageId;
                        " x-on:editing-mode.window="
                        isSubmitActive = true;">
                        <div class="space-y-4 mb-3">
                            <!--Student Data-->
                            <flux:separator text="Data Siswa" />

                            <div class="grid md:grid-cols-2 gap-3">
                                <!--Student Name-->
                                <div class="col-span-1">
                                    <flux:input label="Nama Siswa" placeholder="Tulis nama lengkap" icon="user"
                                        wire:model='form.inputs.studentName' fieldName="studentName" isValidate="true"
                                        isFormObject="true" :disabled="!$isCanEdit ? true : false" />
                                </div>
                                <!--#Student Name-->

                                <!--Gender-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label>Jenis Kelamin</flux:label>
                                        <flux:select wire:model='form.inputs.gender' placeholder="Pilih Jenis Kelamin"
                                            x-on:input="form.gender = $wire.form.inputs.gender; validate('gender')"
                                            :disabled="!$isCanEdit ? true : false">
                                            <flux:select.option value="Laki-Laki">Laki-Laki</flux:select.option>
                                            <flux:select.option value="Perempuan">Perempuan</flux:select.option>
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <!--#Gender-->
                            </div>

                            <div class="grid md:grid-cols-2 gap-3">
                                <!--Birth Place-->
                                <div class="col-span-1">
                                    <flux:input label="Tempat Lahir" icon="hospital" placeholder="Kota kelahiran"
                                        wire:model='form.inputs.birthPlace' fieldName="birthPlace" isValidate="true"
                                        isFormObject="true" :disabled="!$isCanEdit ? true : false" />
                                </div>
                                <!--#Birth Place-->

                                <!--Birth Date-->
                                <div class="col-span-1">
                                    <flux:input label="Tanggal Lahir" type="date" wire:model='form.inputs.birthDate'
                                        fieldName="birthDate" isValidate="true" isFormObject="true"
                                        :disabled="!$isCanEdit ? true : false"
                                        badge="{{ \App\Helpers\DateFormatHelper::shortIndoDate($form->inputs['birthDate']) }}" />
                                </div>
                                <!--#Birth Date-->
                            </div>

                            <div class="grid md:grid-cols-2 gap-3">
                                <!--Mobile Phone-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label>Nomor Whatsapp</flux:label>
                                        <flux:input.group>
                                            <flux:input.group.prefix>+62</flux:input.group.prefix>
                                            <flux:input type="number" wire:model="form.inputs.mobilePhone" disabled />
                                        </flux:input.group>
                                        <template x-if="errors.mobilePhone">
                                            <flux:error name="mobilePhone">
                                                <x-slot:message>
                                                    <span x-text="errors.mobilePhone"></span>
                                                </x-slot:message>
                                            </flux:error>
                                        </template>
                                    </flux:field>
                                </div>
                                <!--#Mobile Phone-->

                                <!--NISN-->
                                <div class="col-span-1">
                                    <flux:input label="NISN" icon="id-card" wire:model='form.inputs.nisn'
                                        fieldName="nisn" isValidate="true" isFormObject="true"
                                        :disabled="!$isCanEdit ? true : false" />
                                </div>
                                <!--#NISN-->
                            </div>

                            <div class="grid grid-cols-1">
                                <!--Address-->
                                <div class="col-span-1">
                                    <flux:textarea label="Alamat Siswa"
                                        placeholder="Tulis alamat tinggal saat ini dengan lengkap" rows="3"
                                        wire:model='form.inputs.address' fieldName="address" isFormObject="true"
                                        isValidate="true" :disabled="!$isCanEdit ? true : false" />
                                </div>
                                <!--#Address-->
                            </div>

                            <div class="grid lg:grid-cols-2 gap-3">
                                <!--Old School Name-->
                                <div class="col-span-1">
                                    <flux:input label="Asal Sekolah" placeholder="Tulis nama sekolah" icon="school"
                                        wire:model='form.inputs.oldSchoolName' fieldName="oldSchoolName"
                                        isValidate="true" isFormObject="true" :disabled="!$isCanEdit ? true : false" />
                                </div>
                                <!--#Old School Name-->

                                <!--NPSN-->
                                <div class="col-span-1">
                                    <flux:input label="NPSN Asal Sekolah" badge="Opsional"
                                        placeholder="Nomor Pokok Sekolah Nasional" icon="shield"
                                        wire:model='form.inputs.oldSchoolNpsn' :disabled="!$isCanEdit ? true : false" />
                                </div>
                                <!--#NPSN-->
                            </div>

                            <div class="grid grid-cols-1">
                                <!--Address-->
                                <div class="col-span-1">
                                    <flux:textarea label="Alamat Asal Sekolah" placeholder="Tulis alamat lengkap"
                                        rows="3" wire:model='form.inputs.oldSchoolAddress' fieldName="oldSchoolAddress"
                                        isValidate="true" isFormObject="true" :disabled="!$isCanEdit ? true : false" />
                                </div>
                                <!--#Address-->
                            </div>

                            <div class="grid md:grid-cols-2 gap-3">
                                <!--Select Province-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label>Provinsi</flux:label>
                                        <flux:select wire:model.live='form.inputs.selectedProvinceId'
                                            placeholder="Pilih Provinsi"
                                            x-on:change="form.selectedProvinceId = $wire.form.inputs.selectedProvinceId; validate('selectedProvinceId')"
                                            :disabled="!$isCanEdit ? true : false">
                                            @foreach ($provinceLists as $province)
                                            <flux:select.option value="{{ $province['id'] }}">{{ $province['name'] }}
                                            </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <!--#Select Province-->

                                <!--Select Regency-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label class="gap-2">
                                            Kabupaten/Kota
                                            <flux:icon.loading variant="micro" wire:loading
                                                wire:target="form.inputs.selectedProvinceId" />
                                        </flux:label>
                                        <flux:select wire:model.live='form.inputs.selectedRegencyId'
                                            placeholder="Pilih Kabupaten"
                                            x-on:change="form.selectedRegencyId = $wire.form.inputs.selectedRegencyId; validate('selectedRegencyId')"
                                            :disabled="!$isCanEdit ? true : false">
                                            @foreach ($regencyLists as $regency)
                                            <flux:select.option value="{{ $regency['id'] }}">{{ $regency['name'] }}
                                            </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <!--#Select Regency-->

                                <!--Select District-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label class="gap-2">
                                            Kecamatan
                                            <flux:icon.loading variant="micro" wire:loading
                                                wire:target="form.inputs.selectedRegencyId" />
                                        </flux:label>
                                        <flux:select wire:model.live='form.inputs.selectedDistrictId'
                                            placeholder="Pilih Kecamatan"
                                            x-on:change="form.selectedDistrictId = $wire.form.inputs.selectedDistrictId; validate('selectedDistrictId')"
                                            :disabled="!$isCanEdit ? true : false">
                                            @foreach ($districtLists as $district)
                                            <flux:select.option value="{{ $district['id'] }}">{{ $district['name'] }}
                                            </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <!--#Select District-->

                                <!--Select Village-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label class="gap-2">
                                            Desa/Kelurahan
                                            <flux:icon.loading variant="micro" wire:loading
                                                wire:target="form.inputs.selectedDistrictId" />
                                        </flux:label>
                                        <flux:select wire:model='form.inputs.selectedVillageId' placeholder="Pilih Desa"
                                            x-on:change="form.selectedVillageId = $wire.form.inputs.selectedVillageId; validate('selectedVillageId')"
                                            :disabled="!$isCanEdit ? true : false">
                                            @foreach ($villageLists as $village)
                                            <flux:select.option value="{{ $village['id'] }}">{{ $village['name'] }}
                                            </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <!--#Select Village-->
                            </div>
                            <!--#Student Data-->

                            <!--Parent Data-->
                            <flux:separator text="Data Orang Tua/Wali" />

                            <!--Select Parent or Guardian-->
                            <flux:radio.group wire:model="form.inputs.isParent"
                                label="Apakah siswa tinggal bersama orang tua?">
                                <flux:radio value="1" label="Iya"
                                    x-bind:disabled="{{ !$isCanEdit ? 'true' : 'false' }}" />
                                <flux:radio value="0" label="Tidak"
                                    x-bind:disabled="{{ !$isCanEdit ? 'true' : 'false' }}" />
                            </flux:radio.group>
                            <template x-if="errors.isParent">
                                <flux:error name="isParent">
                                    <x-slot:message>
                                        <span x-text="errors.isParent"></span>
                                    </x-slot:message>
                                </flux:error>
                            </template>
                            <!--#Select Parent or Guardian-->

                            <!--Father Data-->
                            <template x-if="$wire.form.inputs.isParent == 1">
                                <flux:separator text="Data Ayah" />
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 1">
                                <div class="grid lg:grid-cols-2 gap-3">
                                    <!--Father Name-->
                                    <div class="col-span-1">
                                        <flux:input label="Nama Ayah" placeholder="Tulis nama lengkap" icon="user"
                                            wire:model='form.inputs.fatherName' required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')"
                                            :disabled="!$isCanEdit ? true : false" />
                                    </div>
                                    <!--#Father Name-->

                                    <!--Father Mobile Phone-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Nomor HP Ayah</flux:label>
                                            <flux:input.group>
                                                <flux:input.group.prefix>+62</flux:input.group.prefix>
                                                <flux:input type="number" wire:model="form.inputs.fatherMobilePhone"
                                                    placeholder="85775627364"
                                                    onInput="this.value = this.value.replace(/^0+/, '').replace(/[^0-9]/g, '')"
                                                    :disabled="!$isCanEdit ? true : false" />
                                            </flux:input.group>
                                            <template x-if="errors.fatherMobilePhone">
                                                <flux:error name="fatherMobilePhone">
                                                    <x-slot:message>
                                                        <span x-text="errors.fatherMobilePhone"></span>
                                                    </x-slot:message>
                                                </flux:error>
                                            </template>
                                        </flux:field>
                                    </div>
                                    <!--#Father Mobile Phone-->

                                    <!--Father Birth Place-->
                                    <div class="col-span-1">
                                        <flux:input label="Tempat Lahir Ayah" icon="hospital"
                                            placeholder="Kota kelahiran" wire:model='form.inputs.fatherBirthPlace'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')" />
                                    </div>
                                    <!--#Father Birth Place-->

                                    <!--Father Birth Date-->
                                    <div class="col-span-1">
                                        <flux:input label="Tanggal Lahir Ayah" type="date"
                                            wire:model='form.inputs.fatherBirthDate'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')"
                                            badge="{{ \App\Helpers\DateFormatHelper::shortIndoDate($form->inputs['fatherBirthDate']) }}" />
                                    </div>
                                    <!--#Father Birth Date-->
                                </div>
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 1">
                                <div class="grid grid-cols-1 gap-3">
                                    <!--Father Address-->
                                    <div class="col-span-1">
                                        <flux:textarea label="Alamat Ayah"
                                            placeholder="Tulis alamat tinggal saat ini dengan lengkap" rows="3"
                                            wire:model='form.inputs.fatherAddress'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')" class="mb-1" />
                                        <flux:field variant="inline">
                                            <flux:checkbox x-model="sameAddressAsStudent"
                                                x-on:change="sameAddressAsStudent ? $wire.form.inputs.fatherAddress = $wire.form.inputs.address : $wire.form.inputs.fatherAddress = ''"
                                                :disabled="!$isCanEdit ? true : false" />
                                            <flux:label>Sama dengan alamat siswa</flux:label>
                                        </flux:field>
                                    </div>
                                    <!--#Father Address-->
                                </div>
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 1">
                                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-3">
                                    <!--Father Last Education-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Pendidikan Terakhir Ayah</flux:label>
                                            <flux:select wire:model='form.inputs.fatherSelectedLastEducationId'
                                                placeholder="Pilih Jenjang Pendidikan" required
                                                oninvalid="this.setCustomValidity('Pilih pendidikan terakhir')"
                                                oninput="this.setCustomValidity('')"
                                                :disabled="!$isCanEdit ? true : false">
                                                @foreach ($lastEducationLists as $education)
                                                <flux:select.option value="{{ $education['id'] }}">{{ $education['name']
                                                    }}
                                                </flux:select.option>
                                                @endforeach
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                    <!--#Father Last Education-->


                                    <!--Father's Job-->
                                    <div class="col-span-1">
                                        <x-inputs.searchable-dropdown label="Pekerjaan Ayah" icon="briefcase"
                                            placeholder="Cari nama pekerjaan" fieldName="searchFatherJobName"
                                            selectedFieldName="fatherSelectedJobId" initFunction="initJobSearch()"
                                            isFormObject="true" required
                                            oninvalid="this.setCustomValidity('Pilih pekerjaan')"
                                            oninput="this.setCustomValidity('')" :disabled="!$isCanEdit ? true : false">
                                            @forelse($jobLists as $job)
                                            <x-inputs.dropdown-list selectedName="{{ $job['name'] }}"
                                                selectedId="{{ $job['id'] }}" fieldName="searchFatherJobName"
                                                selectedFieldName="fatherSelectedJobId" isFormObject="true">
                                                {{ $job['name'] }}
                                            </x-inputs.dropdown-list>
                                            @empty
                                            <li class="px-3 py-2 text-gray-500">Data Tidak Ditemukan!</li>
                                            @endforelse
                                        </x-inputs.searchable-dropdown>
                                    </div>
                                    <!--#Father's Job-->

                                    <!--Father's Sallary-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Penghasilan Ayah</flux:label>
                                            <flux:select wire:model='form.inputs.fatherSelectedSallaryId'
                                                placeholder="Pilih Penghasilan" required
                                                oninvalid="this.setCustomValidity('Pilih penghasilan')"
                                                oninput="this.setCustomValidity('')"
                                                :disabled="!$isCanEdit ? true : false">
                                                @foreach ($sallaryLists as $sallary)
                                                <flux:select.option value="{{ $sallary['id'] }}">{{ $sallary['name'] }}
                                                </flux:select.option>
                                                @endforeach
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                    <!--#Father's Sallary-->
                                </div>
                            </template>
                            <!--#Father Data-->

                            <!--Mother Data-->
                            <template x-if="$wire.form.inputs.isParent == 1">
                                <flux:separator text="Data Ibu" />
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 1">
                                <div class="grid lg:grid-cols-2 gap-3">
                                    <!--Mother Name-->
                                    <div class="col-span-1">
                                        <flux:input label="Nama Ibu" placeholder="Tulis nama lengkap" icon="user"
                                            wire:model='form.inputs.motherName' required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')"
                                            :disabled="!$isCanEdit ? true : false" />
                                    </div>
                                    <!--#Mother Name-->

                                    <!--Mother Mobile Phone-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Nomor HP Ibu</flux:label>
                                            <flux:input.group>
                                                <flux:input.group.prefix>+62</flux:input.group.prefix>
                                                <flux:input type="number" wire:model="form.inputs.motherMobilePhone"
                                                    placeholder="85775627364"
                                                    onInput="this.value = this.value.replace(/^0+/, '').replace(/[^0-9]/g, '')"
                                                    :disabled="!$isCanEdit ? true : false" />
                                            </flux:input.group>
                                            <template x-if="errors.motherMobilePhone">
                                                <flux:error name="motherMobilePhone">
                                                    <x-slot:message>
                                                        <span x-text="errors.motherMobilePhone"></span>
                                                    </x-slot:message>
                                                </flux:error>
                                            </template>
                                        </flux:field>
                                    </div>
                                    <!--#Mother Mobile Phone-->

                                    <!--Mother Birth Place-->
                                    <div class="col-span-1">
                                        <flux:input label="Tempat Lahir Ibu" icon="hospital"
                                            placeholder="Kota kelahiran" wire:model='form.inputs.motherBirthPlace'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')" />
                                    </div>
                                    <!--#Mother Birth Place-->

                                    <!--Mother Birth Date-->
                                    <div class="col-span-1">
                                        <flux:input label="Tanggal Lahir Ibu" type="date"
                                            wire:model='form.inputs.motherBirthDate'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')"
                                            badge="{{ \App\Helpers\DateFormatHelper::shortIndoDate($form->inputs['motherBirthDate']) }}" />
                                    </div>
                                    <!--#Mother Birth Date-->
                                </div>
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 1">
                                <div class="grid grid-cols-1 gap-3">
                                    <!--Mother Address-->
                                    <div class="col-span-1">
                                        <flux:textarea label="Alamat Ibu"
                                            placeholder="Tulis alamat tinggal saat ini dengan lengkap" rows="3"
                                            wire:model='form.inputs.motherAddress'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')" class="mb-1" />
                                        <flux:field variant="inline">
                                            <flux:checkbox x-model="sameAddressAsStudent"
                                                x-on:change="sameAddressAsStudent ? $wire.form.inputs.motherAddress = $wire.form.inputs.address : $wire.form.inputs.motherAddress = ''"
                                                :disabled="!$isCanEdit ? true : false" />
                                            <flux:label>Sama dengan alamat siswa</flux:label>
                                        </flux:field>
                                    </div>
                                    <!--#Mother Address-->
                                </div>
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 1">
                                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-3">
                                    <!--Mother Last Education-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Pendidikan Terakhir Ibu</flux:label>
                                            <flux:select wire:model='form.inputs.motherSelectedLastEducationId'
                                                placeholder="Pilih Jenjang Pendidikan" required
                                                oninvalid="this.setCustomValidity('Pilih pendidikan terakhir')"
                                                oninput="this.setCustomValidity('')"
                                                :disabled="!$isCanEdit ? true : false">
                                                @foreach ($lastEducationLists as $education)
                                                <flux:select.option value="{{ $education['id'] }}">{{ $education['name']
                                                    }}
                                                </flux:select.option>
                                                @endforeach
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                    <!--#Mother Last Education-->

                                    <!--Mother's Job-->
                                    <div class="col-span-1">
                                        <x-inputs.searchable-dropdown label="Pekerjaan Ibu" icon="briefcase"
                                            placeholder="Cari nama pekerjaan" fieldName="searchMotherJobName"
                                            selectedFieldName="motherSelectedJobId" initFunction="initJobSearch()"
                                            isFormObject="true" required
                                            oninvalid="this.setCustomValidity('Pilih pekerjaan')"
                                            oninput="this.setCustomValidity('')" :disabled="!$isCanEdit ? true : false">
                                            @forelse($jobLists as $job)
                                            <x-inputs.dropdown-list selectedName="{{ $job['name'] }}"
                                                selectedId="{{ $job['id'] }}" fieldName="searchMotherJobName"
                                                selectedFieldName="motherSelectedJobId" isFormObject="true">
                                                {{ $job['name'] }}
                                            </x-inputs.dropdown-list>
                                            @empty
                                            <li class="px-3 py-2 text-gray-500">Data Tidak Ditemukan!</li>
                                            @endforelse
                                        </x-inputs.searchable-dropdown>
                                    </div>
                                    <!--#Mother's Job-->

                                    <!--Mother's Sallary-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Penghasilan Ibu</flux:label>
                                            <flux:select wire:model='form.inputs.motherSelectedSallaryId'
                                                placeholder="Pilih Penghasilan" required
                                                oninvalid="this.setCustomValidity('Wajib diisi!')"
                                                oninput="this.setCustomValidity('')"
                                                :disabled="!$isCanEdit ? true : false">
                                                @foreach ($sallaryLists as $sallary)
                                                <flux:select.option value="{{ $sallary['id'] }}">{{ $sallary['name'] }}
                                                </flux:select.option>
                                                @endforeach
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                    <!--#Mother's Sallary-->
                                </div>
                            </template>
                            <!--#Mother Data-->

                            <!--Guardian Data-->
                            <template x-if="$wire.form.inputs.isParent == 0">
                                <flux:separator text="Data Wali" />
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 0">
                                <div class="grid lg:grid-cols-2 gap-3">
                                    <!--Guardian Name-->
                                    <div class="col-span-1">
                                        <flux:input label="Nama Wali" placeholder="Tulis nama lengkap" icon="user"
                                            wire:model='form.inputs.guardianName' required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')"
                                            :disabled="!$isCanEdit ? true : false" />
                                    </div>
                                    <!--#Guardian Name-->

                                    <!--Guardian Mobile Phone-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Nomor HP Wali</flux:label>
                                            <flux:input.group>
                                                <flux:input.group.prefix>+62</flux:input.group.prefix>
                                                <flux:input type="number" wire:model="form.inputs.guardianMobilePhone"
                                                    placeholder="85775627364"
                                                    onInput="this.value = this.value.replace(/^0+/, '').replace(/[^0-9]/g, '')"
                                                    :disabled="!$isCanEdit ? true : false" />
                                            </flux:input.group>
                                        </flux:field>
                                    </div>
                                    <!--#Guardian Mobile Phone-->

                                    <!--Guardian Birth Place-->
                                    <div class="col-span-1">
                                        <flux:input label="Tempat Lahir Wali" icon="hospital"
                                            placeholder="Kota kelahiran" wire:model='form.inputs.guardianBirthPlace'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')" />
                                    </div>
                                    <!--#Guardian Birth Place-->

                                    <!--Guardian Birth Date-->
                                    <div class="col-span-1">
                                        <flux:input label="Tanggal Lahir Wali" type="date"
                                            wire:model='form.inputs.guardianBirthDate'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')"
                                            badge="{{ \App\Helpers\DateFormatHelper::shortIndoDate($form->inputs['guardianBirthDate']) }}" />
                                    </div>
                                    <!--#Guardian Birth Date-->
                                </div>
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 0">
                                <div class="grid grid-cols-1 gap-3">
                                    <!--Guardian Address-->
                                    <div class="col-span-1">
                                        <flux:textarea label="Alamat Wali"
                                            placeholder="Tulis alamat tinggal saat ini dengan lengkap" rows="3"
                                            wire:model='form.inputs.guardianAddress'
                                            :disabled="!$isCanEdit ? true : false" required
                                            oninvalid="this.setCustomValidity('Wajib diisi')"
                                            oninput="this.setCustomValidity('')" class="mb-1" />
                                        <flux:field variant="inline">
                                            <flux:checkbox x-model="sameAddressAsStudent"
                                                x-on:change="sameAddressAsStudent ? $wire.form.inputs.guardianAddress = $wire.form.inputs.address : $wire.form.inputs.guardianAddress = ''"
                                                :disabled="!$isCanEdit ? true : false" />
                                            <flux:label>Sama dengan alamat siswa</flux:label>
                                        </flux:field>
                                    </div>
                                    <!--#Guardian Address-->
                                </div>
                            </template>

                            <template x-if="$wire.form.inputs.isParent == 0">
                                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-3">
                                    <!--Guardian Last Education-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Pendidikan Terakhir Wali</flux:label>
                                            <flux:select wire:model='form.inputs.guardianSelectedLastEducationId'
                                                placeholder="Pilih Jenjang Pendidikan" required
                                                oninvalid="this.setCustomValidity('Pilih pendidikan terakhir')"
                                                oninput="this.setCustomValidity('')"
                                                :disabled="!$isCanEdit ? true : false">
                                                @foreach ($lastEducationLists as $education)
                                                <flux:select.option value="{{ $education['id'] }}">{{ $education['name']
                                                    }}
                                                </flux:select.option>
                                                @endforeach
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                    <!--#Guardian Last Education-->

                                    <!--Guardian's Job-->
                                    <div class="col-span-1">
                                        <x-inputs.searchable-dropdown label="Pekerjaan Wali" icon="briefcase"
                                            placeholder="Cari nama pekerjaan" fieldName="searchGuardianJobName"
                                            selectedFieldName="guardianSelectedJobId" initFunction="initJobSearch()"
                                            isFormObject="true" required
                                            oninvalid="this.setCustomValidity('Pilih pekerjaan')"
                                            oninput="this.setCustomValidity('')" :disabled="!$isCanEdit ? true : false">
                                            @forelse($jobLists as $job)
                                            <x-inputs.dropdown-list selectedName="{{ $job['name'] }}"
                                                selectedId="{{ $job['id'] }}" fieldName="searchGuardianJobName"
                                                selectedFieldName="guardianSelectedJobId" isFormObject="true">
                                                {{ $job['name'] }}
                                            </x-inputs.dropdown-list>
                                            @empty
                                            <li class="px-3 py-2 text-gray-500">Data Tidak Ditemukan!</li>
                                            @endforelse
                                        </x-inputs.searchable-dropdown>
                                    </div>
                                    <!--#Guardian's Job-->

                                    <!--Guardian's Sallary-->
                                    <div class="col-span-1">
                                        <flux:field>
                                            <flux:label>Penghasilan Wali</flux:label>
                                            <flux:select wire:model='form.inputs.guardianSelectedSallaryId'
                                                placeholder="Pilih Penghasilan" required
                                                oninvalid="this.setCustomValidity('Pilih penghasilan')"
                                                oninput="this.setCustomValidity('')"
                                                :disabled="!$isCanEdit ? true : false">
                                                @foreach ($sallaryLists as $sallary)
                                                <flux:select.option value="{{ $sallary['id'] }}">{{ $sallary['name'] }}
                                                </flux:select.option>
                                                @endforeach
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                    <!--#Guardian's Sallary-->
                                </div>
                            </template>
                            <!--#Guardian Data-->
                            <!--#Parent Data-->

                            <!--Alert When Save Data Failed-->
                            @if (session('save-failed'))
                            <div class="grid grid-cols-1">
                                <div class="cols-span-1">
                                    <x-notifications.basic-alert>
                                        <x-slot:title>{{ session('save-failed') }}</x-slot:title>
                                    </x-notifications.basic-alert>
                                </div>
                            </div>
                            @endif
                            <!--#Alert When Save Data Failed-->

                            <!--Action Button-->
                            @if ($isCanEdit)
                            <div class="flex gap-2">
                                <flux:spacer />
                                <flux:modal.close>
                                    <flux:button variant="filled" type="button" href="{{ route('student.student_dashboard') }}" wire:navigate>Batal</flux:button>
                                </flux:modal.close>

                                <flux:button type="submit" variant="primary" x-bind:disabled="!isSubmitActive"
                                    :loading="false">
                                    <x-items.loading-indicator wireTarget="saveBiodata">
                                        <x-slot:buttonName>Simpan</x-slot:buttonName>
                                        <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                                    </x-items.loading-indicator>
                                </flux:button>
                            </div>
                            @endif
                            <!--#Action Button-->
                        </div>
                    </form>
                </x-cards.soft-glass-card>
            </x-animations.fade-down>
        </div>
        <!--#Persontal Data-->
    </div>
</div>