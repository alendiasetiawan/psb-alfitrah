<div>
    <flux:modal name="{{ $modalId }}" class="md:w-120 lg:w-150 w-full" @close="resetAllProperty">
        <form wire:submit='saveAdmissionBatch'
        x-data="
        formValidation({
            admissionBatchName: ['required', 'minLength:3'],
            admissionBatchStart: ['required'],
            admissionBatchEnd: ['required'],
        })"
        x-on:open-add-edit-admission-batch-modal.window="
        isSubmitActive = true;
        isModalLoading = true;
        isEditingMode = true;
        $wire.setEditValue($event.detail.id).then(() => {
            isModalLoading = false
        });"
        x-on:reset-submit.window="
        isSubmitActive = false;
        "
        wire:ignore
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
                            <span x-show="isEditingMode">Edit Data Gelombang PSB</span>
                            <span x-show="!isEditingMode">Tambah Gelombang PSB</span>
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
                                <x-inputs.date-picker model="inputs.admissionBatchStart" 
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
                                isValidate="true"/>
                            </flux:field>
                        </div>
                    </div>
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
                            <x-items.loading-indicator wireTarget="saveAdmissionBatch">
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