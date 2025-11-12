<div>
    <flux:modal name="{{ $modalId }}" class="md:w-120 lg:w-150" @close="resetAllProperty">
        <form wire:submit='saveEducationProgram'
        x-data="
        formValidation({
            educationProgramName: ['required', 'minLength:2'],
        })"
        x-on:open-add-edit-education-program-modal.window="
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
                            {{ $isEditing ? "Edit Data Program Pendidikan" : "Tambah Program Pendidikan" }}
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
                        <!--Select Branch-->
                        <div class="col-span-1">
                            @if ($isEditing)
                                <flux:field>
                                    <flux:label>Cabang</flux:label>
                                    <flux:text>{{ $branchName }}</flux:text>
                                </flux:field>
                            @else
                            <flux:field>
                                <flux:label>Cabang</flux:label>
                                <flux:select 
                                wire:model='inputs.selectedBranchId'
                                placeholder="Pilih satu cabang">
                                    @foreach ($branchLists as $key => $value)
                                        <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                            @endif
                        </div>
                        <!--#Select Branch-->

                        <!--Education Program Name-->
                        <div class="col-span-1">
                            <flux:input
                            label="Program Pendidikan"
                            icon="book-open"
                            placeholder="Tulis nama program"  
                            wire:model="inputs.educationProgramName"
                            fieldName="educationProgramName"
                            :isValidate="true"/>
                        </div>
                        <!--#Education Program Name-->
                    </div>

                    <div class="grid md:grid-cols-1 gap-3">
                        <!--Program Description-->
                        <div class="col-span-1">
                            <flux:textarea
                            label="Deskripsi Program"
                            badge="opsional"
                            wire:model="inputs.description"
                            rows="2"
                            />
                        </div>
                        <!--#Program Description-->
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
                            <x-items.loading-indicator wireTarget="saveEducationProgram">
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