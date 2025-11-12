<div>
    <flux:modal name="{{ $modalId }}" class="md:w-150" @close="resetAllProperty">
        <form wire:submit='saveReseller'
        x-data="
        formValidation({
            resellerName: ['required', 'minLength:3'],
            mobilePhone: ['required', 'minLength:7', 'maxLength:12'],
            email: ['required'],
            password: ['required', 'minLength:6']
        })"
        x-on:open-add-edit-reseller-modal.window="
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
            <!--Loading indicator when data being fetched-->
            <div x-show="isModalLoading">
                <div class="space-y-4 animate-pulse">
                    <x-loading.title-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.button-skeleton/>
                </div>
            </div>
            <!--#Loading indicator when data being fetched-->

            <!--Main modal content-->
            <div class="space-y-4" x-show="!isModalLoading">
                <div>
                    <flux:heading size="xl" class="text-center">
                            {{ $isEditing ? "Edit Data Reseller" : "Tambah Reseller" }}
                    </flux:heading>
                    <flux:text class="mt-2 text-center">Silahkan lengkapi data di bawah ini</flux:text>

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
                    <!--Photo-->
                    <div class="grid grid-cols-1">
                        @php
                            $isTempUpload = $userPhoto instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
                            $previewSrc = $isTempUpload
                                ? $userPhoto->temporaryUrl()
                                : (!empty($userPhoto) ? asset('storage/' . $userPhoto) : '');
                        @endphp
                        <div
                            class="col-span-1 flex flex-col items-center justify-center"
                            x-data="{ uploading: false, progress: 0 }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false; progress = 0;"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <input
                                id="userPhotoInput"
                                type="file"
                                class="hidden"
                                wire:model.live="userPhoto"
                                accept="image/png, image/jpg, image/jpeg"
                            >

                            <flux:field>
                                <label for="userPhotoInput" class="cursor-pointer">
                                    @if (!$errors->has('userPhoto') && isset($userPhoto))
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
                                @error('userPhoto')
                                    <flux:error name="userPhoto"/>
                                @enderror
                            </flux:field>
                        </div>
                    </div>
                    <!--#Photo-->

                    <!--Reseller Name & Phone-->
                    <div class="grid md:grid-cols-2 gap-3">
                        <div class="col-span-1">
                            <flux:input
                            label="Nama Reseller"
                            icon="user"
                            wire:model="inputs.resellerName"
                            fieldName="resellerName"
                            :isValidate="true"/>
                        </div>

                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>Nomor HP</flux:label>
                                <flux:input.group>
                                    <flux:input.group.prefix>+62</flux:input.group.prefix>
                                    <flux:input
                                    type="number"
                                    wire:model="inputs.mobilePhone"
                                    fieldName="mobilePhone"
                                    placeholder="85775627364"
                                    :isValidateGroup="true"
                                    oninput="this.value = this.value.replace(/^0+/, '').replace(/[^0-9]/g, '')"/>
                                </flux:input.group>
                                <template x-if="errors.mobilePhone">
                                    <flux:error name="mobilePhone">
                                        <x-slot:message>
                                            <span x-text="errors.mobilePhone"></span>
                                        </x-slot:message>
                                    </flux:error>
                                </template>
                                @error('inputs.mobilePhone')
                                    <flux:error name="inputs.mobilePhone"/>
                                @enderror
                            </flux:field>
                        </div>
                    </div>
                    <!--#Reseller Name & Phone-->

                    <!--Username & Password-->
                    <div class="grid md:grid-cols-2 gap-3">
                        <div class="col-span-1">
                            <flux:input
                            label="Username"
                            icon="user-circle"
                            wire:model="inputs.email"
                            fieldName="email"
                            :isValidate="true"/>
                        </div>


                        @if (!$isEditing)
                            <div class="col-span-1">
                                <flux:input
                                label="Password"
                                placeholder="Buat password anda"
                                type="password"
                                icon="lock-closed"
                                viewable
                                wire:model="inputs.password"
                                fieldName="password"
                                :isValidate="true"/>
                            </div>
                        @endif
                    </div>
                    <!--#Username & Password-->

                    <!--Button Action-->
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
                            <x-items.loading-indicator wireTarget="saveReseller">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>
                    </div>
                    <!--#Button Action-->
                @endif
            </div>
            <!--#Main modal content-->
        </form>
    </flux:modal>
</div>
