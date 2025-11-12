<div>
    <flux:modal name="{{ $modalId }}" class="md:w-120 lg:w-150" @close="resetAllProperty">
        <form wire:submit='saveAdmissionQuota'
        x-data="
        formValidation({
            quotaAmount: ['required', 'min:1'],
        })"
        x-on:open-edit-admission-quota-modal.window="
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
                            Kuota Penerimaan Santri PSB {{ $academicYear }}
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
                    <div class="grid md:grid-cols-2 gap-3">
                        <!--Branch Name-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>Cabang</flux:label>
                                <flux:text variant="subtle">{{ $branchName }}</flux:text>
                            </flux:field>
                        </div>
                        <!--#Branch Name-->

                        <!--Branch Name-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>Program</flux:label>
                                <flux:text variant="subtle">{{ $educationProgramName }}</flux:text>
                            </flux:field>
                        </div>
                        <!--#Branch Name-->
                    </div>

                    <div class="grid md:grid-cols-2 gap-3">
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>Kuota Penerimaan</flux:label>
                                <flux:input
                                icon="user-star"
                                type="number"
                                placeholder="Tulis angka"
                                wire:model="inputs.quotaAmount"
                                fieldName="quotaAmount"
                                :isValidate="true"
                                />
                            </flux:field>
                        </div>

                        <!--Status-->
                        <div class="col-span-1">
                            <flux:radio.group 
                            class="flex-col"
                            wire:model="inputs.quotaStatus" 
                            label="Status" 
                            invalid="{{ $errors->has('quotaStatus') }}">
                                <flux:radio value="Buka" label="Buka" />
                                <flux:radio value="Tutup" label="Tutup" />
                            </flux:radio.group>
                        </div>
                        <!--#Status-->
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
                            <x-items.loading-indicator wireTarget="saveAdmissionQuota">
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