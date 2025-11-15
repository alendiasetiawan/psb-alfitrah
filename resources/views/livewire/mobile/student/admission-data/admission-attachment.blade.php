<div class="mb-12">
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Berkas') }}</x-slot:title>
        <x-slot:activePage>{{ __('Pengisian Berkas Siswa') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="flex justify-start mt-3" wire:ignore>
        <x-navigations.pill-tab
        hrefOne="{{ route('student.admission_data.biodata') }}"
        hrefTwo="{{ route('student.admission_data.admission_attachment') }}" >
            <x-slot:tabOne>{{ __('Biodata') }}</x-slot:tabOne>
            <x-slot:tabTwo>{{ __('Berkas') }}</x-slot:tabTwo>
        </x-navigations.pill-tab>
    </div>

    <div class="grid grid-cols-1 mt-4">
        <div class="col-span-1">
            <!--Upload Instruction-->
            <x-cards.basic-card>
                <flux:heading size="xl" class="mb-3">Instruksi Upload Berkas</flux:heading>
                <flux:text variant="ghost">
                    Kepada calon siswa, silahkan upload berkas-berkas sebagai berikut: <br/>
                    1. Photo Siswa <br/>
                    2. Akte Kelahiran <br/>
                    3. Kartu Keluarga <br/>
                    4. KTP Orang Tua <br/>
                </flux:text>
                <br/>
                <flux:heading>
                    Ketentuan Berkas <br/>
                </flux:heading>
                <flux:text>
                    1. Photo Siswa menggunakan <strong>background berwarna biru</strong> dan pakaian rapih (dilarang mengenakan kaos) <br/>
                    2. File dalam format image <strong>(.jpg, .jpeg, .png)</strong> <br/>
                    3. Ukuran maksimal masing-masing file adalah <strong>5 MB</strong>
                </flux:text>
            </x-cards.basic-card>
            <!--#Upload Instruction-->
        </div>
    </div>

    <div class="grid grid-cols-1 mt-4"
    x-init="
            let lastScrollTop = 0;
            $nextTick(() => {
                Fancybox.bind('[data-fancybox]', {
                    on: {
                        'Carousel.init': () => {
                            lastScrollTop = window.scrollY;
                        },
                        'close': () => {
                            window.scrollTo({
                                top: lastScrollTop,
                                behavior: 'instant'
                            });
                        }
                    }
                });
            });
        "
    >
        <div class="col-span-1">
            <x-cards.basic-card>
                <flux:heading size="xl" class="mb-3">Lampiran Berkas</flux:heading>

                <!--Attachment Files-->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 space-y-3">
                    <!--Student Photo-->
                    <div class="col-span-1">
                        <flux:field>
                            <flux:label>Photo Siswa</flux:label>
                            {{-- <flux:description>Photo salah</flux:description> --}}
                            <flux:input type="file" wire:model.live='inputs.studentPhoto' accept="image/png, image/jpg, image/jpeg"/>
                            <a
                                href="{{ asset('storage/images/test-photo.jpeg') }}"
                                data-fancybox="gallery"
                                data-caption="Photo Siswa"
                            >
                                <img
                                src="{{ asset('storage/images/test-photo.jpeg') }}"
                                width="250"
                                height="auto"
                                />
                            </a>
                        </flux:field>
                    </div>
                    <!--#Student Photo-->

                    <!--Born Card-->
                    <div class="col-span-1">
                        <flux:field>
                            <flux:label>Akte Kelahiran</flux:label>
                            {{-- <flux:description>Photo salah</flux:description> --}}
                            <flux:input type="file" wire:model.live='inputs.bornCard' accept="image/png, image/jpg, image/jpeg"/>
                            <a
                                href="{{ asset('storage/images/test-photo.jpeg') }}"
                                data-fancybox="gallery"
                                data-caption="Akte Kelahiran"
                            >
                                <img
                                src="{{ asset('storage/images/test-photo.jpeg') }}"
                                width="250"
                                height="auto"
                                />
                            </a>
                        </flux:field>
                    </div>
                    <!--#Born Card-->

                    <!--Family Card-->
                    <div class="col-span-1">
                        <flux:field>
                            <flux:label>Kartu Keluarga</flux:label>
                            {{-- <flux:description>Photo salah</flux:description> --}}
                            <flux:input type="file" wire:model.live='inputs.familyCard' accept="image/png, image/jpg, image/jpeg"/>
                            <a
                                href="{{ asset('storage/images/test-photo.jpeg') }}"
                                data-fancybox="gallery"
                                data-caption="Kartu Keluarga"
                            >
                                <img
                                src="{{ asset('storage/images/test-photo.jpeg') }}"
                                width="250"
                                height="auto"
                                />
                            </a>
                        </flux:field>
                    </div>
                    <!--#Family Card-->

                    <!--Parent Identity Card-->
                    <div class="col-span-1">
                        <flux:field>
                            <flux:label>KTP Orang Tua</flux:label>
                            {{-- <flux:description>Photo salah</flux:description> --}}
                            <flux:input type="file" wire:model.live='inputs.parentCard' accept="image/png, image/jpg, image/jpeg"/>
                            <a
                                href="{{ asset('storage/images/test-photo.jpeg') }}"
                                data-fancybox="gallery"
                                data-caption="KTP Orang Tua"
                            >
                                <img
                                src="{{ asset('storage/images/test-photo.jpeg') }}"
                                width="250"
                                height="auto"
                                />
                            </a>
                        </flux:field>
                    </div>
                    <!--#Parent Identity Card-->
                </div>
                <!--#Attachment Files-->

                <!--Action Button-->
                <div class="grid grid-cols-1 mt-4">
                    <div class="flex gap-2">
                        <flux:button
                        icon="upload"
                        type="submit"
                        variant="primary"
                        x-bind:disabled="!isSubmitActive"
                        :loading="false">
                            <x-items.loading-indicator wireTarget="saveBiodata">
                                <x-slot:buttonName>Upload</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>

                        <flux:modal.close>
                            <flux:button variant="filled" type="reset">Batal</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
                <!--#Action Button-->
            </x-cards.basic-card>
        </div>
    </div>
</div>
