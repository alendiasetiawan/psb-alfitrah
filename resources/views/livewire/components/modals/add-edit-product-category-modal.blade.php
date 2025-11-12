<div>
    <flux:modal name="{{ $modalId }}" class="md:w-120" @close="resetAllProperty">
        <form wire:submit='saveCategory'
        x-data="
        formValidation({
            categoryName: ['required', 'minLength:3'],
        })"
        x-on:open-add-edit-category-modal.window="
        isSubmitActive = true;
        isModalLoading = true;
        isEditingMode = true;
        $wire.setEditValue($event.detail.id).then(() => {
            isModalLoading = false
        });"
        x-on:reset-submit.window="
        isSubmitActive = false;
        ">
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
                            {{ $isEditing ? "Edit Data Kategori" : "Tambah Kategori" }}
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
                    <div class="grid grid-cols-1 gap-3">
                        <!--Category Name-->
                        <div class="col-span-1">
                            <flux:input
                            label="Nama Kategori"
                            icon="tag"
                            wire:model="inputs.categoryName"
                            fieldName="categoryName"
                            :isValidate="true"/>
                        </div>
                        <!--#Category Name-->
                    </div>

                    <div class="grid md:grid-cols-1 gap-3">
                        <!--Category Description-->
                        <div class="col-span-1">
                            <flux:textarea
                            label="Deskripsi"
                            badge="Opsional"
                            wire:model="inputs.categoryDescription"
                            rows="2"
                            />
                        </div>
                        <!--#Category Description-->
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
