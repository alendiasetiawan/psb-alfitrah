<div>
    <flux:modal name="{{ $modalId }}" class="md:w-120 lg:w-150" @close="resetAllProperty">
        <form wire:submit='saveAdmission'
        x-data="
        formValidation({
            admissionName: ['required', 'minLength:9'],
            admissionBatchName: ['required', 'minLength:3'],
            admissionBatchEnd: ['required'],
            admissionBatchStart: ['required'],
        })"
        x-on:open-add-edit-admission-modal.window="
        isSubmitActive = true;
        isModalLoading = true;
        isEditingMode = true;
        $wire.setEditValue($event.detail.id).then(() => {
            isModalLoading = false
        });"
        x-on:reset-submit.window="
        isSubmitActive = false;
        "
        >
            <!--Loading Skeleton-->
            <template x-if="isModalLoading">
                <div class="space-y-4 animate-pulse">
                    <x-loading.title-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.button-skeleton/>
                </div>
            </template>
            <!--#Loading Skeleton-->

            <div class="space-y-4" x-show="!isModalLoading">
                <div>
                    <flux:heading size="xl" class="text-center">
                            {{ $isEditing ? "Edit Data Tahun Ajaran PSB" : "Tambah Tahun Ajaran PSB" }}
                    </flux:heading>
                    <flux:text class="mt-2 text-center mb-2">Silahkan lengkapi data di bawah ini</flux:text>

                    <!--Alert when save data failed-->
                    @if (session('save-failed'))
                        <x-notifications.basic-alert isCloseable="true">
                            <x-slot:title>{{ session('save-failed') }}</x-slot:title>
                        </x-notifications.basic-alert>
                    @endif
                    <!--#Alert when save data failed-->
                </div>

                @if (session('error-id-edit'))
                    <div class="mt-2">
                        <x-notifications.basic-alert>
                            <x-slot:title>{{ session('error-id-edit') }}</x-slot:title>
                        </x-notifications.basic-alert>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-3">
                        <!--Nama Tahun Ajaran-->
                        <div class="col-span-1">
                            <flux:input
                            label="Tahun Ajaran"
                            icon="graduation-cap"
                            wire:model="inputs.admissionName"
                            fieldName="admissionName"
                            :isValidate="true"/>
                        </div>
                        <!--#Nama Tahun Ajaran-->
                    </div>

                    <div class="grid md:grid-cols-1 gap-3">
                        <!--Status-->
                        <div class="col-span-1">
                            <flux:radio.group wire:model="inputs.admissionStatus" label="Status" invalid="{{ $errors->has('admissionStatus') }}">
                                <flux:radio value="Buka" label="Buka" />
                                <flux:radio value="Tutup" label="Tutup" />
                            </flux:radio.group>
                        </div>
                        <!--#Status-->
                    </div>

                    @if (!$isEditing)                        
                        <flux:separator text="Pengaturan Gelombang" />

                        <div class="grid md:grid-cols-1 gap-3">
                            <!--Batch Name-->
                            <div class="col-span-1">
                                <flux:input
                                label="Gelombang"
                                placeholder="Tulis nama gelombang"
                                icon="between-horizontal-end"
                                wire:model="inputs.admissionBatchName"
                                fieldName="admissionBatchName"
                                :isValidate="true"/>
                            </div>
                            <!--#Batch Name-->
                        </div>

                        <div class="grid md:grid-cols-2 gap-3">
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>Tanggal Mulai</flux:label>
                                    <x-inputs.date-picker 
                                    model="inputs.admissionBatchStart" 
                                    minDate="{{ date('Y-m-d') }}"
                                    fieldName="admissionBatchStart"
                                    isValidate="true"
                                    />
                                </flux:field>
                            </div>

                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>Tanggal Berakhir</flux:label>
                                    <x-inputs.date-picker model="inputs.admissionBatchEnd" 
                                    fieldName="admissionBatchEnd"
                                    isValidate="true"
                                    minDate="{{ date('Y-m-d') }}"/>
                                </flux:field>
                            </div>
                        </div>
                    @endif

                    <!--Action Button-->
                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="filled">Batal</flux:button>
                        </flux:modal.close>

                        <flux:button
                        type="submit"
                        variant="primary"
                        x-bind:disabled="!isSubmitActive"
                        :loading="false">
                            <x-items.loading-indicator wireTarget="saveAdmission">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>
                    </div>
                    <!--#Action Button-->
                @endif
            </div>
        </form>
    </flux:modal>
</div>