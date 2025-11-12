<div>
    <x-navigations.breadcrumb firstLink="{{ route('owner.warehouse.product.list_product') }}">
        <x-slot:title>{{ __('Tambah Produk') }}</x-slot:title>
        <x-slot:firstPage>{{ __('Produk') }}</x-slot:firstPage>
        <x-slot:activePage>{{ __('Form Tambah Produk Baru') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="flex justify-center md:mt-4">
        <div class="bg-white rounded-xl shadow-md p-4 w-full md:w-4/5">
            <div class="mb-3">
                <flux:heading size="xxl">Form Tambah Produk</flux:heading>
            </div>
            <form
            x-data="{
            isVariant: [],
            isSubmitActive: false,
                ...formValidation({
                    selectedCategoryId: ['required'],
                    selectedStoreId: ['required'],
                    productName: ['required', 'minLength:3'],
                    basePrice: ['required'],
                    resellerPrice: ['required'],
                    stock: ['required'],
                })
            }"
            x-on:open-add-product-modal.window="
            isSubmitActive = true;
            isModalLoading = true;
            isEditingMode = true;
            $wire.setEditValue($event.detail.id).then(() => {
                isModalLoading = false
            });"
            x-on:reset-submit.window="
            isSubmitActive = false;">
                <div class="space-y-4">
                    <flux:separator text="Detail Produk" />
                    <!--Category and Store ID-->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <flux:select label="Kategori"
                            placeholder="Pilih Kategori"
                            wire:model="inputs.selectedCategoryId"
                            x-on:input="form.selectedCategoryId = $event.target.value; validate('selectedCategoryId')">
                                @foreach ($categoryLists as $id => $name)
                                    <flux:select.option value="{{ $id }}">{{ $name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div class="col-span-1">
                            <flux:select label="Toko"
                            placeholder="Pilih Toko"
                            wire:model="inputs.selectedStoreId"
                            x-on:input="form.selectedStoreId = $event.target.value; validate('selectedStoreId')">
                                @foreach ($storeLists as $id => $name)
                                    <flux:select.option value="{{ $id }}">{{ $name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                    </div>
                    <!--#Category and Store ID-->

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!--Product Name-->
                        <div class="col-span-1">
                            <flux:input
                            label="Nama Produk"
                            icon="package-search"
                            wire:model="inputs.productName"
                            fieldName="productName"
                            :isValidate="true"
                            />
                        </div>
                        <!--#Product Name-->

                        <!--Base Price-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>Harga Awal</flux:label>
                                <flux:input.group>
                                    <flux:input.group.prefix>Rp</flux:input.group.prefix>
                                    <flux:input
                                    placeholder="0"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    wire:model='inputs.basePrice'
                                    x-mask:dynamic="$money($input, ',')"
                                    fieldName="basePrice"
                                    :isValidate="true"
                                    />
                                </flux:input.group>
                                <template x-if="errors.basePrice">
                                    <flux:error name="basePrice">
                                        <x-slot:message>
                                            <span x-text="errors.basePrice"></span>
                                        </x-slot:message>
                                    </flux:error>
                                </template>
                                @error('inputs.basePrice')
                                    <flux:error name="inputs.basePrice"/>
                                @enderror
                            </flux:field>
                        </div>
                        <!--#Base Price-->

                        <!--Reseller Price-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>Harga Reseller</flux:label>
                                <flux:input.group>
                                    <flux:input.group.prefix>Rp</flux:input.group.prefix>
                                    <flux:input
                                    placeholder="0"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    wire:model='inputs.resellerPrice'
                                    x-mask:dynamic="$money($input, ',')"
                                    fieldName="resellerPrice"
                                    :isValidate="true"
                                    />
                                </flux:input.group>
                                <template x-if="errors.resellerPrice">
                                    <flux:error name="resellerPrice">
                                        <x-slot:message>
                                            <span x-text="errors.resellerPrice"></span>
                                        </x-slot:message>
                                    </flux:error>
                                </template>
                                @error('inputs.resellerPrice')
                                    <flux:error name="inputs.resellerPrice"/>
                                @enderror
                            </flux:field>
                        </div>
                        <!--#Reseller Price-->

                        <!--Stock-->
                        <div class="col-span-1">
                            <flux:input
                            fieldName="stock"
                            :isValidate="true"
                            wire:model="inputs.stock"
                            icon="archive-box"
                            label="Stok Awal"
                            placeholder="0"
                            type="number"
                            inputmode="numeric"
                            pattern="[0-9]*"/>
                        </div>
                        <!--#Stock-->

                        <!--Description-->
                        <div class="col-span-2">
                            <flux:textarea
                            wire:model="inputs.description"
                            label="Deskripsi Produk"
                            placeholder="Tulis keterangan dan spesifikasi produk"
                            badge="Opsional"
                            wire:model="inputs.storeAddress"
                            rows="3"
                            />
                        </div>
                        <!--#Description-->
                    </div>

                    <!--Variant Type-->
                    <flux:fieldset>
                        <flux:legend>Pilihan Varian</flux:legend>
                        @if ($variantTypeLists->count() > 0)
                            <flux:description>Silahkan pilih satu atau lebih varian di bawah ini:</flux:description>

                            <div class="flex gap-4 *:gap-x-2">
                                <flux:checkbox value="english" label="English" x-model="isVariant" />
                                <flux:checkbox value="spanish" label="Spanish" x-model="isVariant"/>
                                <flux:checkbox value="french" label="French" x-model="isVariant"/>
                                <flux:checkbox value="german" label="German" x-model="isVariant"/>
                            </div>
                        @else
                            <flux:description>
                                Belum ada jenis variant tersedia, silahkan
                                <flux:modal.trigger name="add-edit-variant-type-modal">
                                    <a href="#">
                                        <u><strong>Tambah Varian +</strong></u>
                                    </a>
                                </flux:modal.trigger>
                            </flux:description>
                        @endif
                    </flux:fieldset>
                    <span x-text="isVariant"></span>
                    <!--#Variant Type-->

                    <div x-show="isVariant.length > 0">
                        <flux:separator text="Opsi Varian" />
                    </div>

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
                            <x-items.loading-indicator wireTarget="saveOwner">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>
                    </div>
                    <!--#Button Action-->
                </div>
            </form>

            <!--Modal Add Variant-->
            <livewire:components.modals.add-edit-variant-type-modal modalId="add-edit-variant-type-modal" />
            <!--#Modal Add Variant-->
        </div>
    </div>
</div>
