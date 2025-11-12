<div>
    @if (session('error-id'))
    <div class="grid grid-cols-1 mt-4">
        <div class="col-span-1">
            <x-notifications.basic-alert>
                <x-slot:title>{{ session('error-id') }}</x-slot:title>
            </x-notifications.basic-alert>
        </div>
    </div>
    @else
        @if ($isAdmissionOpen)
            @if (session('registration-success'))
                <!--OTP Verification-->
                <div class="fixed inset-0 flex items-center justify-center">
                    <x-cards.basic-card class="md:w-3/6 lg:w-2/6 p-5">
                        <!--Alert OTP error-->
                        @if (session('otp-failed'))
                        <x-notifications.basic-alert isCloseable="true">
                            <x-slot:title>{{ session('otp-failed') }}</x-slot:title>
                        </x-notifications.basic-alert>
                        @endif
                        <!--#Alert OTP error-->

                        <!--Alert OTP Success-->
                        @if (session('otp-success'))
                        <x-notifications.basic-alert isCloseable="true" variant="success">
                            <x-slot:title>{{ session('otp-success') }}</x-slot:title>
                        </x-notifications.basic-alert>
                        @endif
                        <!--#Alert OTP Success-->

                        <form
                        wire:submit='otpVerification'
                        x-data="{
                            ...formValidation({
                                otp: ['required']
                            }),
                            ...countDownTimer({
                                countdown: 30
                            })
                        }
                        "
                        x-init="startTimer()"
                        >
                            <div class="flex flex-col justify-center items-center mb-4 text-center space-y-4">
                                <flux:icon.check-badge class="size-20 text-green-500" />
                                <flux:heading size="xxl">
                                    Pendaftaran Berhasil
                                </flux:heading>

                                <flux:text variant="ghost">
                                    Kami telah mengirimkan kode OTP ke nomor whatsapp anda, silahkan masukan kode tersebut pada kolom di bawah ini:
                                </flux:text>

                                <flux:input
                                type="number"
                                icon="lock-closed"
                                placeholder="Kode OTP"
                                wire:model="inputs.otp"
                                fieldName="otp"
                                :isValidate="true"
                                />

                                <flux:button type="submit" variant="primary" x-bind:disabled="!isSubmitActive" :loading="false"
                                    class="w-full">
                                    <x-items.loading-indicator wireTarget="otpVerification">
                                        <x-slot:buttonName>Verifikasi</x-slot:buttonName>
                                        <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                                    </x-items.loading-indicator>
                                </flux:button>

                                <flux:button type="button" 
                                variant="ghost" 
                                class="w-full"
                                x-on:click="resetTimer()"
                                wire:click='resendOtp'
                                x-bind:disabled="countdown > 0 ? true : false">
                                    <span x-show="countdown > 0" class="text-gray-500">
                                        Kirim Ulang OTP (<span x-text="countdown"></span>)
                                    </span>
                                    <span x-show="countdown === 0" class="text-blue-500">
                                        Kirim Ulang OTP
                                    </span>
                                </flux:button>
                            </div>
                        </form>

                    </x-cards.basic-card>
                </div>
                <!--#OTP Verification-->
            @else
                <!--Registration Form-->
                <div class="flex justify-center">
                    <x-cards.basic-card class="md:w-3/6 lg:w-2/6 p-4">
                        <div class="flex flex-col justify-center items-center mb-4 text-center">
                            <flux:heading size="xxl">
                                Daftar Akun
                            </flux:heading>
                            <flux:text variant="subtle">
                                Lengkapi data di bawah ini untuk membuat akun di <strong class="text-primary">Aplikasi PSB Online Al
                                    Fitrah Islamic School</strong>
                            </flux:text>
                        </div>

                        <form 
                            wire:submit='saveStudentRegistration' 
                            x-data="
                            formValidation({
                                studentName: ['required', 'minLength:3'],
                                selectedBranchId: ['required'],
                                selectedEducationProgramId: ['required'],
                                mobilePhone: ['required', 'minLength:7', 'maxLength:12'],
                                password: ['required', 'minLength:6'],
                            })" 
                            x-effect="
                            form.selectedBranchId = $wire.inputs.selectedBranchId;
                            validate('selectedBranchId')
                            " 
                            x-on:open-add-edit-admission-modal.window="
                            isSubmitActive = true;
                            isModalLoading = true;
                            isEditingMode = true;
                            $wire.setEditValue($event.detail.id).then(() => {
                                isModalLoading = false
                            });" 
                            x-on:reset-submit.window="
                            isSubmitActive = false;"    
                        >
                            <flux:separator text="Data Siswa" />
                            <div class="grid grid-cols-1 mt-4 mb-4 gap-4">
                                <!--Student Name-->
                                <div class="col-span-1">
                                    <flux:input label="Nama Siswa" placeholder="Tulis nama lengkap" icon="user"
                                        wire:model="inputs.studentName" fieldName="studentName" :isValidate="true" />
                                </div>
                                <!--#Student Name-->

                                <!--Gender-->
                                <div class="col-span-1">
                                    <flux:radio.group wire:model="inputs.gender" label="Jenis Kelamin" required
                                        oninvalid="this.setCustomValidity('Silahkan pilih jenis kelamin')"
                                        oninput="this.setCustomValidity('')">
                                        <flux:radio value="Laki-Laki" label="Laki-Laki" />
                                        <flux:radio value="Perempuan" label="Perempuan" />
                                    </flux:radio.group>
                                </div>
                                <!--#Gender-->

                                <!--Branch-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label>Cabang</flux:label>
                                        <flux:select placeholder="Pilih satu cabang" wire:model.live='inputs.selectedBranchId'
                                            x-on:input="form.selectedBranchId = $event.target.value; validate('selectedBranchId')">
                                            @foreach ($branchLists as $key => $value)
                                            <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </flux:field>
                                </div>
                                <!--#Branch-->

                                <!--Education Program-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label class="gap-2">
                                            Program
                                            <flux:icon.loading variant="micro" wire:loading wire:target="inputs.selectedBranchId" />
                                        </flux:label>
                                        <flux:select placeholder="Pilih satu program" wire:model.live='inputs.selectedEducationProgramId'
                                            x-on:input="form.selectedEducationProgramId = $event.target.value; validate('selectedEducationProgramId')">
                                            @foreach ($educationProgramLists as $program)
                                            <flux:select.option value="{{ $program['id'] }}">{{ $program['name'] }}
                                            </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    </flux:field>

                                    @if (!$isQuotaAvailable)
                                        <flux:text variant="strong" class="mt-2" color="rose">
                                            Maaf, pendaftaran untuk program 
                                            <strong>
                                                {{ $selectedProgramName }}
                                            </strong> di 
                                            <strong>
                                                {{ $selectedBranchName }}
                                            </strong>
                                            sudah tutup/penuh. Silahkan pilih program yang lain
                                        </flux:text>
                                    @endif
                                </div>
                                <!--#Education Program-->
                            </div>

                            <flux:separator text="Data Login" />
                            <div class="grid grid-cols-1 mt-4 gap-4">
                                <!--Mobile Phone-->
                                <div class="col-span-1">
                                    <flux:field>
                                        <flux:label>Nomor Whatsapp</flux:label>
                                        <flux:input.group>
                                            <flux:input.group.prefix>+62</flux:input.group.prefix>
                                            <flux:input type="number" wire:model.blur="inputs.mobilePhone" fieldName="mobilePhone"
                                                placeholder="85775627364" :isValidateGroup="true"
                                                oninput="this.value = this.value.replace(/^0+/, '').replace(/[^0-9]/g, '')" />
                                        </flux:input.group>
                                        <template x-if="errors.mobilePhone">
                                            <flux:error name="mobilePhone">
                                                <x-slot:message>
                                                    <span x-text="errors.mobilePhone"></span>
                                                </x-slot:message>
                                            </flux:error>
                                        </template>
                                        @error('inputs.mobilePhone')
                                            <flux:error name="inputs.mobilePhone" /> 
                                            <div class="flex flex-row items-center">
                                                <span>Silahkan login disini</span>
                                                <a href="{{ route('login') }}" wire:navigate>
                                                    <flux:icon.log-in size="micro" class="ml-1 text-blue-500"/>
                                                </a>
                                            </div>
                                        @enderror
                                    </flux:field>
                                </div>
                                <!--#Mobile Phone-->

                                <div class="col-span-1">
                                    <flux:input viewable type="password" label="Password" placeholder="Buat password untuk login"
                                        icon="key-square" wire:model='inputs.password' fieldName="password" :isValidate="true" />
                                </div>
                            </div>

                            <!--Error Section-->
                            <div class="grid grid-cols-1 mt-4">
                                <div class="col-span-1">
                                    <!--Alert when registration failed-->
                                    @if (session('save-failed'))
                                    <x-notifications.basic-alert isCloseable="true">
                                        <x-slot:title>{{ session('save-failed') }}</x-slot:title>
                                    </x-notifications.basic-alert>
                                    @endif
                                    <!--Alert when registration failed-->
                                </div>
                            </div>
                            <!--#Error Section-->

                            <!--Action Button-->
                            <div class="grid grid-cols-1 mt-4 gap-2">
                                <div class="col-span-1">
                                    <flux:button type="submit" variant="primary" x-bind:disabled="!isSubmitActive || !$wire.isQuotaAvailable" :loading="false" class="w-full">
                                        <x-items.loading-indicator wireTarget="saveStudentRegistration">
                                            <x-slot:buttonName>Simpan</x-slot:buttonName>
                                            <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                                        </x-items.loading-indicator>
                                    </flux:button>
                                </div>

                                <div class="col-span-1">
                                    <a href="{{ route('branch_quota') }}">
                                        <flux:button variant="filled" class="w-full">
                                            Batal
                                        </flux:button>
                                    </a>
                                </div>
                            </div>
                            <!--#Action Button-->
                        </form>
                    </x-cards.basic-card>
                </div>
                <!--#Registration Form-->
            @endif
        @else
            <!--Alert When Admission Closed-->
            <div class="fixed inset-0 flex items-center justify-center">
                <x-cards.basic-card class="md:w-3/6 lg:w-2/6 p-5">
                    <div class="flex flex-col justify-center items-center mb-4 text-center space-y-4">
                        <flux:icon.door-closed-locked class="size-20 text-red-500" />
                        <flux:heading size="xxl">
                            Pendaftaran Tutup
                        </flux:heading>

                        <flux:text variant="ghost">
                            Mohon maaf, saat ini pendaftaran sudah tutup. Silahkan kembali lagi nanti, terima kasih ^^
                        </flux:text>
                    </div>
                </x-cards.basic-card>
            </div>
            <!--#Alert When Admission Closed
        @endif
    @endif
</div>