<div>
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

                <form>
                    <!--Attachment Files-->
                    <div class="grid md:grid-cols-2 grid.cols-1 gap-2 space-y-3">
                        <!--Student Photo-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>
                                    Photo Siswa 
                                </flux:label>
                                <div
                                    x-data="{ uploading: false, progress: 0 }"
                                    x-on:livewire-upload-start="uploading = true"
                                    x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                    x-on:livewire-upload-error="uploading = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                                    {{-- <flux:text><span class="text-red-500">Silahkan upload ulang "Photo Siswa"</span></flux:text> --}}
                                    <flux:input type="file" wire:model.live='studentPhoto' accept="image/png, image/jpg, image/jpeg"/>
                                    @error('studentPhoto')
                                        <flux:error name="studentPhoto">
                                            <x-slot:message>
                                                {{ $message }}
                                            </x-slot:message>
                                        </flux:error>
                                    @enderror
                                    {{-- <flux:text class="mt-2">Lihat file sebelumnya <flux:link>disini</flux:link></flux:text> --}}

                                    <template x-if="uploading" class="w-1/2 mt-2">
                                        <x-items.progress-bar progress="$progress" />
                                    </template>
                                    @if (!$errors->has('studentPhoto') && isset($studentPhoto))
                                        <flux:button size="sm" variant="subtle" class="mt-2" x-on:click="$wire.resetSelectedProperty('studentPhoto')">
                                            <flux:icon.trash variant="micro" class="text-red-500"/>
                                            <span class="text-red-500">Hapus</span>
                                        </flux:button>
                                        <img
                                            src="{{ $studentPhoto->temporaryUrl() }}"
                                            width="250"
                                            height="auto"
                                            class="mt-2"
                                        />
                                    @endif
                                </div>

                                <!--Display When File Has Been Uploaded and Status Valid-->
                                {{-- <a
                                    href="{{ asset('storage/'. $studentPhoto) }}"
                                    data-fancybox="gallery"
                                    data-caption="Photo Siswa"
                                >
                                    <img
                                    src="{{ asset('storage/'. $studentPhoto) }}"
                                    width="250"
                                    height="auto"
                                    />
                                </a> --}}
                                <!--#Display When File Has Been Uploaded and Status Valid-->
                            </flux:field>
                        </div>
                        <!--#Student Photo-->

                        <!--Born Card-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>
                                    Akte Kelahiran 
                                </flux:label>
                                {{-- <flux:text><span class="text-red-500">Silahkan upload ulang "Akte Kelahiran"</span></flux:text> --}}
                                <div
                                    x-data="{ uploading: false, progress: 0 }"
                                    x-on:livewire-upload-start="uploading = true"
                                    x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                    x-on:livewire-upload-error="uploading = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress"
                                >
                                    <flux:input type="file" wire:model.live='bornCard' accept="image/png, image/jpg, image/jpeg"/>
                                    @error('bornCard')
                                        <flux:error name="bornCard">
                                            <x-slot:message>
                                                {{ $message }}
                                            </x-slot:message>
                                        </flux:error>
                                    @enderror
                                    {{-- <flux:text class="mt-2">Lihat file sebelumnya <flux:link>disini</flux:link></flux:text> --}}

                                    <template x-if="uploading" class="w-1/2 mt-2">
                                        <x-items.progress-bar progress="$progress" />
                                    </template>
                                    @if (!$errors->has('bornCard') && isset($bornCard))
                                        <flux:button size="sm" variant="subtle" class="mt-2" x-on:click="$wire.resetSelectedProperty('bornCard')">
                                            <flux:icon.trash variant="micro" class="text-red-500"/>
                                            <span class="text-red-500">Hapus</span>
                                        </flux:button>
                                        <img
                                            src="{{ $bornCard->temporaryUrl() }}"
                                            width="250"
                                            height="auto"
                                            class="mt-2"
                                        />
                                    @endif
                                </div>
                                <!--Display When File Has Been Uploaded-->
                                {{-- <a
                                    href="{{ asset('storage/'. $bornCard) }}"
                                    data-fancybox="gallery"
                                    data-caption="Photo Siswa"
                                >
                                    <img
                                    src="{{ asset('storage/'. $bornCard) }}"
                                    width="250"
                                    height="auto"
                                    />
                                </a> --}}
                                <!--#Display When File Has Been Uploaded-->
                            </flux:field>
                        </div>
                        <!--#Born Card-->

                        <!--Family Card-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>Kartu Keluarga</flux:label>
                                {{-- <flux:description>Photo salah</flux:description> --}}
                                <flux:input type="file" wire:model.live='familyCard' accept="image/png, image/jpg, image/jpeg"/>
                                @error('familyCard')
                                    <flux:error name="familyCard">
                                        <x-slot:message>
                                            {{ $message }}
                                        </x-slot:message>
                                    </flux:error>
                                @enderror
                                {{-- <a
                                    href="{{ asset('storage/images/test-photo.jpeg') }}"
                                    data-fancybox="gallery"
                                    data-caption="Kartu Keluarga"
                                >
                                    <img
                                    src="{{ asset('storage/images/test-photo.jpeg') }}"
                                    width="250"
                                    height="auto"
                                    />
                                </a> --}}
                            </flux:field>
                        </div>
                        <!--#Family Card-->

                        <!--Parent Identity Card-->
                        <div class="col-span-1">
                            <flux:field>
                                <flux:label>KTP Orang Tua</flux:label>
                                {{-- <flux:description>Photo salah</flux:description> --}}
                                <flux:input type="file" wire:model.live='parentCard' accept="image/png, image/jpg, image/jpeg"/>
                                @error('parentCard')
                                    <flux:error name="parentCard">
                                        <x-slot:message>
                                            {{ $message }}
                                        </x-slot:message>
                                    </flux:error>
                                @enderror
                                {{-- <a
                                    href="{{ asset('storage/images/test-photo.jpeg') }}"
                                    data-fancybox="gallery"
                                    data-caption="KTP Orang Tua"
                                >
                                    <img
                                    src="{{ asset('storage/images/test-photo.jpeg') }}"
                                    width="250"
                                    height="auto"
                                    />
                                </a> --}}
                            </flux:field>
                        </div>
                        <!--#Parent Identity Card-->
                    </div>
                    <!--#Attachment Files-->

                    <!--Action Button-->
                    <div class="grid grid-cols-1 mt-4">
                        <div class="flex gap-2">
                            {{-- @if (!$errors->any()) --}}
                                <flux:button
                                icon="upload"
                                type="submit"
                                variant="primary"
                                :disabled="$isSubmitActive && !$errors->any() ? false : true"
                                :loading="false">
                                    <x-items.loading-indicator wireTarget="saveBiodata">
                                        <x-slot:buttonName>Upload</x-slot:buttonName>
                                        <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                                    </x-items.loading-indicator>
                                </flux:button>

                                <a href="{{ route('student.student_dashboard') }}" wire:navigate>
                                    <flux:button variant="filled" type="button">Batal</flux:button>
                                </a>
                            {{-- @endif --}}
                        </div>
                    </div>
                    <!--#Action Button-->
                </form>
            </x-cards.basic-card>
        </div>
    </div>
</div>
