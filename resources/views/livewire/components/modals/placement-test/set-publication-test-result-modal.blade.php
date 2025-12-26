<div>
    <flux:modal name="{{ $modalId }}"  variant="{{ $isMobile ? 'flyout' : '' }}" position="{{ $isMobile ? 'bottom' : '' }}">
        <form 
            wire:submit="savePublication"
            x-data="{
               isModalLoading: true
            }"
            x-on:open-publication-modal.window="
               isModalLoading = true;
               $wire.setEditValue($event.detail.id).then(() => {
                   isModalLoading = false
               });"
        >
            <!--Loading Skeleton-->
            <template x-if="isModalLoading">
                <div class="space-y-4 animate-pulse">
                    <x-loading.title-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.button-skeleton/>
                </div>
            </template>
            <!--#Loading Skeleton-->

            <div class="space-y-4" x-show="!isModalLoading">
                <flux:heading size="xl" class="text-center">Publikasi Hasil Tes</flux:heading>

                <div class="grid md:grid-cols-2 mt-4 gap-3">
                    <!--Student Name-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Nama Siswa</flux:text>
                            <flux:text>{{ $studentName }}</flux:text>
                        </div>
                    </div>
                    <!--#Student Name-->

                    <!--Publication Status-->
                    <div class="col-span-1">
                        <flux:radio.group wire:model="inputs.publicationStatus" label="Status Publikasi" required
                            oninvalid="this.setCustomValidity('Silahkan pilih status')"
                            oninput="this.setCustomValidity('')">
                            <div class="flex items-center gap-2">
                                <flux:radio value="Hold" label="Hold" />
                                <flux:radio value="Release" label="Release" />
                            </div>
                        </flux:radio.group>
                    </div>
                    <!--#Publication Status-->

                    <!--Checkbox Send Notification-->
                    <div class="col-span-1">
                        <flux:field variant="inline" class="hover:cursor-pointer">
                            <flux:checkbox wire:model="inputs.isNotificationSent" />
                            <flux:label>Kirim Notifikasi Whatsapp</flux:label>
                        </flux:field>
                    </div>
                    <!--#Checkbox Send Notification-->
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary" :loading="false">
                        <x-items.loading-indicator wireTarget="savePublication">
                            <x-slot:buttonName>Simpan</x-slot:buttonName>
                            <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                        </x-items.loading-indicator>
                    </flux:button>

                    <flux:modal.close>
                        <flux:button variant="filled">Batal</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
