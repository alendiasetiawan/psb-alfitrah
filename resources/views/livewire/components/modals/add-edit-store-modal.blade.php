<div>
    <flux:modal name="{{ $modalId }}" class="md:w-120" @close="resetAllProperty">
        <form wire:submit='saveStore'
        x-data="
        formValidation({
            storeName: ['required', 'minLength:3'],
            selectedOwnerId: ['required'],
        })"
        x-on:open-add-edit-store-modal.window="
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
                            {{ $isEditing ? "Edit Data Toko" : "Tambah Toko" }}
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
                    <div class="grid grid-cols-1">
                        <!--Upload Logo-->
                        @php
                            $isTempUpload = $storeLogo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
                            $previewSrc = $isTempUpload
                                ? $storeLogo->temporaryUrl()
                                : (!empty($storeLogo) ? asset('storage/' . $storeLogo) : '');
                        @endphp
                        <div
                            class="col-span-1 flex flex-col items-center justify-center"
                            x-data="{ uploading: false, progress: 0 }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false; progress = 0;"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <input
                                id="storeLogoInput"
                                type="file"
                                class="hidden"
                                wire:model.live="storeLogo"
                                accept="image/png, image/jpg, image/jpeg"
                            >

                            <flux:field>
                                <label for="storeLogoInput" class="cursor-pointer">
                                    @if (!$errors->has('storeLogo') && isset($storeLogo))
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
                                @error('storeLogo')
                                    <flux:error name="storeLogo"/>
                                @enderror
                            </flux:field>
                        </div>
                        <!--#Upload Logo-->
                    </div>

                    <div class="grid md:grid-cols-2 gap-3">
                        <!--Select Owner-->
                        <div class="col-span-1">
                            <x-inputs.searchable-dropdown
                            label="Pilih Owner"
                            placeholder="Cari nama owner"
                            icon="user"
                            fieldName="searchOwnerName"
                            selectedFieldName="selectedOwnerId"
                            initFunction="initResult()">
                                @forelse($results as $result)
                                    <x-inputs.dropdown-list
                                    selectedName="{{ $result->name }}"
                                    selectedId="{{ $result->id }}"
                                    fieldName="searchOwnerName"
                                    selectedFieldName="selectedOwnerId">
                                        {{ $result->name }}
                                    </x-inputs.dropdown-list>
                                @empty
                                    <li class="px-3 py-2 text-gray-500">Data Tidak Ditemukan!</li>
                                @endforelse
                            </x-inputs.searchable-dropdown>
                        </div>
                        <!--#Select Owner-->

                        <!--Store Name-->
                        <div class="col-span-1">
                            <flux:input
                            label="Nama Toko"
                            icon="store"
                            wire:model="inputs.storeName"
                            fieldName="storeName"
                            :isValidate="true"/>
                        </div>
                        <!--#Store Name-->
                    </div>

                    <div class="grid md:grid-cols-1 gap-3">
                        <!--Store Address-->
                        <div class="col-span-1">
                            <flux:textarea
                            label="Alamat"
                            badge="Opsional"
                            wire:model="inputs.storeAddress"
                            rows="2"
                            />
                        </div>
                        <!--#Store Address-->
                    </div>

                    <div class="grid md:grid-cols-1 gap-3">
                        <!--Store Description-->
                        <div class="col-span-1">
                            <flux:textarea
                            label="Deskripsi"
                            badge="Opsional"
                            wire:model="inputs.storeDescription"
                            rows="2"
                            />
                        </div>
                        <!--#Store Description-->
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
                            <x-items.loading-indicator wireTarget="saveStore">
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
