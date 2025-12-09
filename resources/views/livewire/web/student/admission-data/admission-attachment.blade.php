<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Berkas') }}</x-slot:title>
        <x-slot:activePage>{{ __('Pengisian Berkas Siswa') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <div class="flex justify-start mt-3" wire:ignore>
        <x-navigations.pill-tab hrefOne="{{ route('student.admission_data.biodata') }}"
            hrefTwo="{{ route('student.admission_data.admission_attachment') }}">
            <x-slot:tabOne>{{ __('Biodata') }}</x-slot:tabOne>
            <x-slot:tabTwo>{{ __('Berkas') }}</x-slot:tabTwo>
        </x-navigations.pill-tab>
    </div>

    <x-animations.fade-down showTiming="50">
        <div wire:ignore>
            @if ($detailAttachment->registration_payment != \App\Enums\VerificationStatusEnum::VALID)
            <!--Alert When Registration Payment Unpaid-->
            <div class="grid grid-cols-1 mt-4">
                <div class="col-span-1">
                    <x-notifications.basic-alert>
                        <x-slot:title>
                            Mohon maaf, anda tidak bisa mengisi berkas sebelum menyelesaikan pembayaran.
                            <a href="{{ route('student.payment.registration_payment') }}" wire:navigate>
                                <strong><u>Bayar Disini</u></strong>
                            </a>
                        </x-slot:title>
                    </x-notifications.basic-alert>
                </div>
            </div>
            <!--#Alert When Registration Payment Unpaid-->
            @endif

            @if ($detailAttachment->attachment == \App\Enums\VerificationStatusEnum::PROCESS)
            <!--Alert when Attachment is being verified-->
            <div class="grid grid-cols-1 mt-4">
                <div class="col-span-1">
                    <x-notifications.basic-alert variant="warning" icon="exclamation-circle">
                        <x-slot:title>
                            Kami sedang melakukan pengecakan berkas anda, mohon kesediaannya untuk menunggu. Terima Kasih
                        </x-slot:title>
                    </x-notifications.basic-alert>
                </div>
            </div>
            <!--#Alert when Attachment is being verified-->
            @endif

            @if($detailAttachment->attachment == \App\Enums\VerificationStatusEnum::INVALID)
            <!--Alert when Attachment is invalid-->
            <div class="grid grid-cols-1 mt-4">
                <div class="col-span-1">
                    <x-notifications.basic-alert variant="danger" icon="x-circle">
                        <x-slot:title>
                            Berkas Tidak Valid
                        </x-slot:title>
                        <x-slot:subTitle>
                            Alasan : {{ $detailAttachment->attachment_error_msg }}
                        </x-slot:subTitle>
                    </x-notifications.basic-alert>
                </div>
            </div>
            <!--#Alert when Attachment is invalid-->
            @endif

            @if ($detailAttachment->attachment == \App\Enums\VerificationStatusEnum::VALID)
            <div class="flex flex-col justify-start items-start mt-4">
                <flux:badge color="green" variant="solid" icon="circle-check-big">Berkas Valid</flux:badge>
            </div>
            @endif
        </div>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="50">
        <div class="grid grid-cols-1 mt-4">
            <div class="col-span-1">
                <!--Upload Instruction-->
                <x-cards.soft-glass-card>
                    <flux:heading size="xl" class="mb-3" variant="bold">Instruksi Upload Berkas</flux:heading>
                    <flux:text variant="soft">
                        Kepada ananda <strong>{{ $detailAttachment->student_name }}</strong>, untuk melanjutkan tahapan
                        penerimaan
                        siswa baru silahkan upload berkas-berkas di bawah ini: <br />
                        1. Photo Siswa <br />
                        2. Akte Kelahiran <br />
                        3. Kartu Keluarga <br />
                        4. KTP Orang Tua <br />
                    </flux:text>
                    <br />
                    <flux:heading variant="bold">
                        Ketentuan Berkas <br />
                    </flux:heading>
                    <flux:text variant="soft">
                        1. Photo Siswa menggunakan <strong>background berwarna biru</strong> dan pakaian rapih (dilarang
                        mengenakan
                        kaos) <br />
                        2. File dalam format image <strong>(.jpg, .jpeg, .png)</strong> <br />
                        3. Ukuran maksimal masing-masing file adalah <strong>3 MB</strong>
                    </flux:text>
                </x-cards.soft-glass-card>
                    <!--#Upload Instruction-->
            </div>
        </div>
    </x-animations.fade-down>

    <x-animations.fade-down showTiming="150">
        <div class="grid grid-cols-1 mt-4" x-init="let lastScrollTop = 0;
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
        });">
            <div class="col-span-1">
                <x-cards.soft-glass-card>
                    <flux:heading size="xl" class="mb-3" variant="bold">Lampiran Berkas</flux:heading>

                    <form wire:submit='saveAttachment'>
                        <!--Attachment Files-->
                        <div class="grid md:grid-cols-2 grid.cols-1 gap-2 space-y-3">
                            <!--Student Photo-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>
                                        Photo Siswa
                                    </flux:label>
                                    <div x-data="{ uploading: false, progress: 0 }"
                                        x-on:livewire-upload-start="uploading = true"
                                        x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                        x-on:livewire-upload-error="uploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                                        @if ($detailAttachment->studentAttachment?->photo_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text><span class="text-red-500">Silahkan upload ulang "Photo Siswa"</span>
                                        </flux:text>
                                        @endif

                                        @if ($detailAttachment->studentAttachment?->photo_status ==
                                        \App\Enums\VerificationStatusEnum::NOT_STARTED ||
                                        $detailAttachment->studentAttachment?->photo_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID ||
                                        is_null($detailAttachment->studentAttachment))
                                        <flux:input type="file" wire:model.live='studentPhoto'
                                            accept="image/png, image/jpg, image/jpeg" />
                                        @error('studentPhoto')
                                        <flux:error name="studentPhoto">
                                            <x-slot:message>
                                                {{ $message }}
                                            </x-slot:message>
                                        </flux:error>
                                        @enderror
                                        @endif

                                        <!--Alert for student to see previous image-->
                                        @if ($detailAttachment->studentAttachment?->photo_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text class="mt-2">
                                            Lihat file sebelumnya
                                            <flux:link
                                                href="{{ asset('storage/' . $detailAttachment->studentAttachment->photo) }}"
                                                data-fancybox="invalid-attachment"
                                                data-caption="Photo Siswa (File Sebelumnya)">
                                                disini
                                            </flux:link>
                                        </flux:text>
                                        @endif
                                        <!--#Alert for student to see previous image-->

                                        <template x-if="uploading" class="w-1/2 mt-2">
                                            <x-items.progress-bar progress="$progress" />
                                        </template>

                                        <!--Show temporary image preview-->
                                        @php
                                        $isTempUploadStudentPhoto = $studentPhoto instanceof
                                        \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
                                        @endphp
                                        @if (!$errors->has('studentPhoto') && $isTempUploadStudentPhoto)
                                        <flux:button size="sm" variant="subtle" class="mt-2"
                                            x-on:click="$wire.resetSelectedProperty('studentPhoto')">
                                            <flux:icon.trash variant="micro" class="text-red-500" />
                                            <span class="text-red-500">Hapus</span>
                                        </flux:button>

                                        <img src="{{ $studentPhoto->temporaryUrl() }}" width="250" height="auto"
                                            class="mt-2" />
                                        @endif
                                        <!--Show temporary image preview-->
                                    </div>

                                    <!--Gallery Display When File Has Been Uploaded-->
                                    @if (
                                    $detailAttachment->studentAttachment?->photo_status ==
                                    \App\Enums\VerificationStatusEnum::PROCESS ||
                                    $detailAttachment->studentAttachment?->photo_status ==
                                    \App\Enums\VerificationStatusEnum::VALID)
                                    <a href="{{ asset('storage/' . $detailAttachment->studentAttachment->photo) }}"
                                        data-fancybox="valid-attachment" data-caption="Photo Siswa">
                                        <img src="{{ asset('storage/' . $detailAttachment->studentAttachment->photo) }}"
                                            width="250" height="auto" />
                                    </a>
                                    @endif
                                    <!--#Gallery Display When File Has Been Uploaded-->
                                </flux:field>
                            </div>
                            <!--#Student Photo-->

                            <!--Born Card-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>
                                        Akte Kelahiran
                                    </flux:label>
                                    <div x-data="{ uploading: false, progress: 0 }"
                                        x-on:livewire-upload-start="uploading = true"
                                        x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                        x-on:livewire-upload-error="uploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                                        @if ($detailAttachment->studentAttachment?->born_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text><span class="text-red-500">Silahkan upload ulang "Akte Kelahiran"</span>
                                        </flux:text>
                                        @endif

                                        @if ($detailAttachment->studentAttachment?->born_card_status ==
                                        \App\Enums\VerificationStatusEnum::NOT_STARTED ||
                                        $detailAttachment->studentAttachment?->born_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID ||
                                        is_null($detailAttachment->studentAttachment))
                                        <flux:input type="file" wire:model.live='bornCard'
                                            accept="image/png, image/jpg, image/jpeg" />
                                        @error('bornCard')
                                        <flux:error name="bornCard">
                                            <x-slot:message>
                                                {{ $message }}
                                            </x-slot:message>
                                        </flux:error>
                                        @enderror
                                        @endif

                                        <!--Alert for student to see previous image-->
                                        @if ($detailAttachment->studentAttachment?->born_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text class="mt-2">
                                            Lihat file sebelumnya
                                            <flux:link
                                                href="{{ asset('storage/' . $detailAttachment->studentAttachment->born_card) }}"
                                                data-fancybox="invalid-attachment"
                                                data-caption="Akte Kelahiran (File Sebelumnya)">
                                                disini
                                            </flux:link>
                                        </flux:text>
                                        @endif
                                        <!--#Alert for student to see previous image-->

                                        <template x-if="uploading" class="w-1/2 mt-2">
                                            <x-items.progress-bar progress="$progress" />
                                        </template>

                                        <!--Show temporary uploaded image preview-->
                                        @php
                                        $isTempUploadBornCard = $bornCard instanceof
                                        \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
                                        @endphp
                                        @if (!$errors->has('bornCard') && $isTempUploadBornCard)
                                        <flux:button size="sm" variant="subtle" class="mt-2"
                                            x-on:click="$wire.resetSelectedProperty('bornCard')">
                                            <flux:icon.trash variant="micro" class="text-red-500" />
                                            <span class="text-red-500">Hapus</span>
                                        </flux:button>

                                        <img src="{{ $bornCard->temporaryUrl() }}" width="250" height="auto" class="mt-2" />
                                        @endif
                                        <!--Show temporary uploaded image preview-->
                                    </div>

                                    <!--Gallery Display When File Has Been Uploaded-->
                                    @if (
                                    $detailAttachment->studentAttachment?->born_card_status ==
                                    \App\Enums\VerificationStatusEnum::PROCESS ||
                                    $detailAttachment->studentAttachment?->born_card_status ==
                                    \App\Enums\VerificationStatusEnum::VALID)
                                    <a href="{{ asset('storage/' . $detailAttachment->studentAttachment->born_card) }}"
                                        data-fancybox="valid-attachment" data-caption="Akte Kelahiran">
                                        <img src="{{ asset('storage/' . $detailAttachment->studentAttachment->born_card) }}"
                                            width="250" height="auto" />
                                    </a>
                                    @endif
                                    <!--#Gallery Display When File Has Been Uploaded-->
                                </flux:field>
                            </div>
                            <!--#Born Card-->

                            <!--Family Card-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>
                                        Kartu Keluarga
                                    </flux:label>

                                    <div x-data="{ uploading: false, progress: 0 }"
                                        x-on:livewire-upload-start="uploading = true"
                                        x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                        x-on:livewire-upload-error="uploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                                        @if ($detailAttachment->studentAttachment?->family_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text><span class="text-red-500">Silahkan upload ulang "Kartu Keluarga"</span>
                                        </flux:text>
                                        @endif

                                        @if ($detailAttachment->studentAttachment?->family_card_status ==
                                        \App\Enums\VerificationStatusEnum::NOT_STARTED ||
                                        $detailAttachment->studentAttachment?->family_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID ||
                                        is_null($detailAttachment->studentAttachment))
                                        <flux:input type="file" wire:model.live='familyCard'
                                            accept="image/png, image/jpg, image/jpeg" />
                                        @error('familyCard')
                                        <flux:error name="familyCard">
                                            <x-slot:message>
                                                {{ $message }}
                                            </x-slot:message>
                                        </flux:error>
                                        @enderror
                                        @endif

                                        <!--Alert for student to see previous image-->
                                        @if ($detailAttachment->studentAttachment?->family_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text class="mt-2">
                                            Lihat file sebelumnya
                                            <flux:link
                                                href="{{ asset('storage/' . $detailAttachment->studentAttachment->family_card) }}"
                                                data-fancybox="invalid-attachment"
                                                data-caption="Kartu Keluarga (File Sebelumnya)">
                                                disini
                                            </flux:link>
                                        </flux:text>
                                        @endif
                                        <!--#Alert for student to see previous image-->

                                        <template x-if="uploading" class="w-1/2 mt-2">
                                            <x-items.progress-bar progress="$progress" />
                                        </template>

                                        <!--Show temporary uploaded image preview-->
                                        @php
                                        $isTempUploadFamilyCard = $familyCard instanceof
                                        \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
                                        @endphp
                                        @if (!$errors->has('familyCard') && $isTempUploadFamilyCard)
                                        <flux:button size="sm" variant="subtle" class="mt-2"
                                            x-on:click="$wire.resetSelectedProperty('familyCard')">
                                            <flux:icon.trash variant="micro" class="text-red-500" />
                                            <span class="text-red-500">Hapus</span>
                                        </flux:button>

                                        <img src="{{ $familyCard->temporaryUrl() }}" width="250" height="auto"
                                            class="mt-2" />
                                        @endif
                                        <!--Show temporary uploaded image preview-->
                                    </div>

                                    <!--Gallery Display When File Has Been Uploaded-->
                                    @if (
                                    $detailAttachment->studentAttachment?->family_card_status ==
                                    \App\Enums\VerificationStatusEnum::PROCESS ||
                                    $detailAttachment->studentAttachment?->family_card_status ==
                                    \App\Enums\VerificationStatusEnum::VALID)
                                    <a href="{{ asset('storage/' . $detailAttachment->studentAttachment->family_card) }}"
                                        data-fancybox="valid-attachment" data-caption="Kartu Keluarga">
                                        <img src="{{ asset('storage/' . $detailAttachment->studentAttachment->family_card) }}"
                                            width="250" height="auto" />
                                    </a>
                                    @endif
                                    <!--#Gallery Display When File Has Been Uploaded-->
                                </flux:field>
                            </div>
                            <!--#Family Card-->

                            <!--Parent Identity Card-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>
                                        KTP Orang Tua
                                    </flux:label>

                                    <div x-data="{ uploading: false, progress: 0 }"
                                        x-on:livewire-upload-start="uploading = true"
                                        x-on:livewire-upload-finish="uploading = false; progress = 0;"
                                        x-on:livewire-upload-error="uploading = false"
                                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                                        @if ($detailAttachment->studentAttachment?->parent_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text><span class="text-red-500">Silahkan upload ulang "KTP Orang Tua"</span>
                                        </flux:text>
                                        @endif

                                        @if ($detailAttachment->studentAttachment?->parent_card_status ==
                                        \App\Enums\VerificationStatusEnum::NOT_STARTED ||
                                        $detailAttachment->studentAttachment?->parent_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID ||
                                        is_null($detailAttachment->studentAttachment))
                                        <flux:input type="file" wire:model.live='parentCard'
                                            accept="image/png, image/jpg, image/jpeg" />
                                        @error('parentCard')
                                        <flux:error name="parentCard">
                                            <x-slot:message>
                                                {{ $message }}
                                            </x-slot:message>
                                        </flux:error>
                                        @enderror
                                        @endif

                                        <!--Alert for student to see previous image-->
                                        @if ($detailAttachment->studentAttachment?->parent_card_status ==
                                        \App\Enums\VerificationStatusEnum::INVALID)
                                        <flux:text class="mt-2">
                                            Lihat file sebelumnya
                                            <flux:link
                                                href="{{ asset('storage/' . $detailAttachment->studentAttachment->parent_card) }}"
                                                data-fancybox="invalid-attachment"
                                                data-caption="KTP Orang Tua (File Sebelumnya)">
                                                disini
                                            </flux:link>
                                        </flux:text>
                                        @endif
                                        <!--#Alert for student to see previous image-->

                                        <template x-if="uploading" class="w-1/2 mt-2">
                                            <x-items.progress-bar progress="$progress" />
                                        </template>

                                        <!--Show temporary uploaded image preview-->
                                        @php
                                        $isTempUploadParentCard = $parentCard instanceof
                                        \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
                                        @endphp
                                        @if (!$errors->has('parentCard') && $isTempUploadParentCard)
                                        <flux:button size="sm" variant="subtle" class="mt-2"
                                            x-on:click="$wire.resetSelectedProperty('parentCard')">
                                            <flux:icon.trash variant="micro" class="text-red-500" />
                                            <span class="text-red-500">Hapus</span>
                                        </flux:button>

                                        <img src="{{ $parentCard->temporaryUrl() }}" width="250" height="auto"
                                            class="mt-2" />
                                        @endif
                                        <!--Show temporary uploaded image preview-->
                                    </div>

                                    <!--Gallery Display When File Has Been Uploaded-->
                                    @if (
                                    $detailAttachment->studentAttachment?->parent_card_status ==
                                    \App\Enums\VerificationStatusEnum::PROCESS ||
                                    $detailAttachment->studentAttachment?->parent_card_status ==
                                    \App\Enums\VerificationStatusEnum::VALID)
                                    <a href="{{ asset('storage/' . $detailAttachment->studentAttachment->parent_card) }}"
                                        data-fancybox="valid-attachment" data-caption="KTP Orang Tua">
                                        <img src="{{ asset('storage/' . $detailAttachment->studentAttachment->parent_card) }}"
                                            width="250" height="auto" />
                                    </a>
                                    @endif
                                    <!--#Gallery Display When File Has Been Uploaded-->
                                </flux:field>
                            </div>
                            <!--#Parent Identity Card-->
                        </div>
                        <!--#Attachment Files-->

                        @if(session('save-failed'))
                        <!--Alert when save is failed-->
                        <div class="flex-1">
                            <x-notifications.basic-alert>
                                <x-slot:title>{{ session('save-failed') }}</x-slot:title>
                            </x-notifications.basic-alert>
                        </div>
                        <!--#Alert when save is failed-->
                        @endif

                        @if ($isCanEdit)
                        <!--Action Button-->
                        <div class="grid grid-cols-1 mt-4">
                            <div class="flex gap-2">
                                <flux:button icon="upload" type="submit" variant="primary"
                                    :disabled="$isSubmitActive && !$errors->any() ? false : true" :loading="false">
                                    <x-items.loading-indicator wireTarget="saveAttachment">
                                        <x-slot:buttonName>Upload</x-slot:buttonName>
                                        <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                                    </x-items.loading-indicator>
                                </flux:button>

                                <a href="{{ route('student.student_dashboard') }}" wire:navigate>
                                    <flux:button variant="filled" type="button">Batal</flux:button>
                                </a>
                            </div>
                        </div>
                        <!--#Action Button-->
                        @endif
                    </form>
                </x-cards.soft-glass-card>
            </div>
        </div>
    </x-animations.fade-down>
</div>