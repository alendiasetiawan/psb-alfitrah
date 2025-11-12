<div>
    <flux:modal name="{{ $modalId }}" class="md:w-150" @close="resetAllProperty">
        <form wire:submit='saveVariant'
        x-data="
        formValidation({
            variantTypeName: ['required', 'minLength:3'],
            variantOptions: ['required'],
        })"
        x-on:open-add-edit-variant-type-modal.window="
        isSubmitActive = true;
        isModalLoading = true;
        isEditingMode = true;
        $wire.setEditValue($event.detail.id).then(() => {
            isModalLoading = false
        });"
        x-on:reset-submit.window="
        isSubmitActive = false;
        ">
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
                            {{ $isEditing ? "Edit Data Varian" : "Tambah Varian" }}
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
                <!--Variant Name-->
                    <div class="grid grid-cols-1 gap-3">
                        <div class="col-span-1">
                            <flux:input
                            label="Nama Varian"
                            icon="rectangle-group"
                            wire:model="inputs.variantTypeName"
                            fieldName="variantTypeName"
                            :isValidate="true"/>
                        </div>
                    </div>
                <!--#Variant Name-->

                <!--Dynamic Variant Option-->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <flux:heading size="sm">Opsi Varian</flux:heading>
                        <flux:button
                            type="button"
                            variant="outline"
                            size="sm"
                            wire:click="addVariantOption"
                            x-data="{ count: {{ count($inputs['variantOptions']) }} }"
                            x-init="count = {{ count($inputs['variantOptions']) }}"
                            @click="count++">
                            <flux:icon.plus class="size-4" />
                            Tambah Opsi
                        </flux:button>
                    </div>

                    @if(count($inputs['variantOptions']) > 0)
                        <div class="space-y-2">
                            @foreach($inputs['variantOptions'] as $index => $option)
                                <div class="flex gap-2 items-center">
                                    <div class="flex-1">
                                        <flux:input
                                            wire:model="inputs.variantOptions.{{ $index }}.name"
                                            placeholder="Masukkan nama opsi varian"
                                            icon="tag"
                                            fieldName="variantOptions.{{ $index }}.name"
                                            :isValidate="true"/>
                                    </div>
                                    <flux:button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        wire:click="removeVariantOption({{ $index }})"
                                        class="text-red-600 hover:text-red-700 hover:bg-red-50">
                                        <flux:icon.trash class="size-4" />
                                    </flux:button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <flux:icon.tag class="size-12 mx-auto mb-2 opacity-50" />
                            <p>Belum ada opsi varian</p>
                            <p class="text-sm">Klik "Tambah Opsi" untuk menambahkan opsi varian</p>
                        </div>
                    @endif
                </div>
                <!--#Dynamic Variant Option-->

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
                        <x-items.loading-indicator wireTarget="saveVariant">
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
