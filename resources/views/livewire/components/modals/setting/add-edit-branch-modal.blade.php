@props([
    'isMobile' => false
])

<div>
    <flux:modal name="{{ $modalId }}" class="md:w-120 lg:w-150" @close="resetAllProperty" variant="{{ $isMobile ? 'flyout' : '' }}" position="{{ $isMobile ? 'bottom' : '' }}">
        <form wire:submit='saveBranch'
        x-data="
        formValidation({
            branchName: ['required', 'minLength:3'],
            mobilePhone: ['required', 'minLength:7', 'maxLength:12'],
            branchAddress: ['required', 'minLength:15', 'maxLength:500']
        })"
        x-on:open-add-edit-branch-modal.window="
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
                            {{ $isEditing ? "Edit Data Cabang" : "Tambah Cabang" }}
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
                    <div class="grid grid-cols-1">
                        <!--Upload Logo-->
                        @php
                            $isTempUpload = $branchPhoto instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
                            $previewSrc = $isTempUpload
                                ? $branchPhoto->temporaryUrl()
                                : (!empty($branchPhoto) ? asset('storage/' . $branchPhoto) : '');
                        @endphp
                        <div
                            class="col-span-1 flex flex-col items-center justify-center"
                            x-data="{ uploading: false, progress: 0 }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false; progress = 0;"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                        >
                            <flux:label class="py-2">Photo Cabang</flux:label>
                            <input
                                id="branchPhotoInput"
                                type="file"
                                class="hidden"
                                wire:model.live="branchPhoto"
                                accept="image/png, image/jpg, image/jpeg" 
                            />

                            <flux:field>
                                <label for="branchPhotoInput" class="cursor-pointer">
                                    @if (!$errors->has('branchPhoto') && isset($branchPhoto))
                                        <div class="flex justify-center items-center">
                                            <img src="{{ $previewSrc }}" width="30%" height="auto">
                                        </div>
                                    @else
                                        <flux:avatar
                                            icon="arrow-up-tray"
                                            src="{{ $previewSrc }}"
                                            size="xl"/>
                                    @endif
                                </label>
                                <div x-show="uploading" class="w-full">
                                    <x-items.progress-bar progress="$progress" />
                                </div>
                                @error('branchPhoto')
                                    <flux:error name="branchPhoto"/>
                                @enderror
                            </flux:field>
                        </div>
                        <!--#Upload Logo-->
                    </div>

                    <div class="grid md:grid-cols-2 gap-3">
                        <!--Branch Name-->
                        <div class="col-span-1">
                            <flux:input
                            label="Nama Cabang"
                            icon="school"
                            wire:model="inputs.branchName"
                            fieldName="branchName"
                            :isValidate="true"/>
                        </div>
                        <!--#Branch Name-->

                        <!--Mobile Phone-->
                        <div class="col-span-1">
                            <flux:input
                            type="number"
                            label="Nomor HP"
                            icon="smartphone"
                            placeholder="0894823xxxx"  
                            wire:model="inputs.mobilePhone"
                            fieldName="mobilePhone"
                            :isValidate="true"/>
                        </div>
                        <!--#Mobile Phone-->
                    </div>

                    <div class="grid md:grid-cols-1 gap-3">
                        <!--Branch Address-->
                        <div class="col-span-1">
                            <flux:textarea
                            label="Alamat Cabang"
                            placeholder="Tulis alamat dengan lengkap"
                            wire:model="inputs.branchAddress"
                            fieldName="branchAddress"
                            rows="2"
                            :isValidate="true"
                            />
                        </div>
                        <!--#Branch Address-->
                    </div>

                    <div class="grid md:grid-cols-1 gap-3">
                        <!--Google Maps-->
                        <div class="col-span-1">
                            <flux:input
                            label="Link Google Maps"
                            icon="map-pinned"
                            wire:model="inputs.mapLink"
                            badge="opsional"
                            />
                        </div>
                        <!--#Google Maps-->
                    </div>

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
                            <x-items.loading-indicator wireTarget="saveBranch">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>
                    </div>
                @endif
            </div>
        </form>
    </flux:modal>
</div>