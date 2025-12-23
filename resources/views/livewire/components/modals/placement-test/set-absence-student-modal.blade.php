<div>
    <flux:modal 
    variant="{{ $isMobile ? 'flyout' : '' }}"
    position="{{ $isMobile ? 'bottom' : '' }}"
    name="{{ $modalId }}" 
    class="md:w-120 lg:w-150 w-full" 
    @close="resetAllProperty">
        <form wire:submit='saveAbsenceStudent'
        x-data="
        formValidation({
            presenceTime: ['required'],
        })"
        x-on:filled-absence-student-modal.window="
        isSubmitActive = true;
        isModalLoading = true;
        $wire.setEditValue($event.detail.id).then(() => {
            isModalLoading = false
        });"
        x-on:blank-absence-student-modal.window="
        isSubmitActive = false;
        isModalLoading = true;
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
                            Set Absensi Siswa
                    </flux:heading>
                    <flux:text class="mt-2 text-center mb-2">Update status kehadiran tes santri di bawah ini</flux:text>

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
                    <div class="grid md:grid-cols-2 space-y-4 gap-3">
                        <!--Student Name-->
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Nama Siswa</flux:text>
                                <flux:text variant="bold">{{ $studentQuery->student_name ?? '' }}</flux:text>
                            </div>
                        </div>
                        <!--#Student Name-->

                        <!--Branch Name-->
                        <div class="col-span-1">
                            <div class="flex flex-col items-start">
                                <flux:text variant="soft">Cabang</flux:text>
                                <flux:text variant="bold">{{ $studentQuery->branch_name ?? '' }}</flux:text>
                            </div>
                        </div>
                        <!--#Branch Name-->


                        <!--Absence-->
                        <div class="col-span-1">
                            <flux:radio.group wire:model="inputs.presence" label="Absensi" required
                                oninvalid="this.setCustomValidity('Silahkan pilih kehadiran')"
                                oninput="this.setCustomValidity('')">
                                <div class="flex items-center gap-2">
                                    <flux:radio value="Hadir" label="Hadir" />
                                    <flux:radio value="Tidak Hadir" label="Tidak Hadir" />
                                </div>
                            </flux:radio.group>
                        </div>
                        <!--#Absence-->

                        <!--Time-->
                        <div class="col-span-1">
                            <flux:input
                                wire:model="inputs.presenceTime"
                                label="Waktu Kehadiran"
                                type="datetime-local"
                                isValidate="true"
                                fieldName="presenceTime"/>
                        </div>
                        <!--#Time-->

                    </div>

                    <!--Action Button-->
                    <div class="flex gap-2">
                        <flux:spacer/>
                        <flux:button
                        type="submit"
                        variant="primary"
                        x-bind:disabled="!isSubmitActive"
                        :loading="false">
                            <x-items.loading-indicator wireTarget="saveAbsenceStudent">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>

                        <flux:modal.close>
                            <flux:button variant="filled">Batal</flux:button>
                        </flux:modal.close>
                    </div>
                    <!--#Action Button-->
                @endif
            </div>
        </form>
    </flux:modal>
</div>