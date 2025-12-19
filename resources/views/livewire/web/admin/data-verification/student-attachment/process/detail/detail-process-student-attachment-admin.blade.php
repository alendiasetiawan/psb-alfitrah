<div>
    <x-navigations.breadcrumb secondLink="{{ route('admin.data_verification.student_attachment.process') }}">
        <x-slot:title>{{ __('Detail Berks Siswa') }}</x-slot:title>
        <x-slot:secondPage>{{ __('Berkas Proses') }}</x-slot:secondPage>
        <x-slot:activePage>{{ __('Informasi Berkas Siswa') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    @if (session('error-show-student'))
        <div class="grid grid-cols-1 mt-4">
            <div class="col-span-1">
                <x-notifications.basic-alert isCloseable="true">
                    <x-slot:title>{{ session('error-show-student') }}</x-slot:title>
                </x-notifications.basic-alert>
            </div>
        </div>
    @else
        <!--ANCHOR - HERO COVER STUDENT PROFILE-->
        <x-animations.fade-down showTiming="50">
            <div class="grid grid-cols-1 mt-4">
                <div class="relative overflow-hidden rounded-lg">
                    {{-- MASKED GRADIENT BORDER (Tailwind v4) --}}
                    <div
                        class="
                        pointer-events-none absolute inset-0
                        p-[2px] rounded-lg
                        bg-gradient-to-br from-white/20 via-white/10 to-white/5
                        shadow-[inset_0_2px_2px_rgba(255,255,255,0.7)]
                        z-[1]
                        ">
                    </div>

                    {{-- LIQUID GEL BORDER (soft jelly shine) --}}
                    <div
                        class="
                        pointer-events-none absolute inset-0 z-[4]
                        p-[3px] rounded-lg
                        [@background:linear-gradient(
                            135deg,
                            rgba(255,255,255,0.55),
                            rgba(255,255,255,0.25),
                            rgba(255,255,255,0.15),
                            rgba(255,255,255,0.45)
                        )]
                        [mask:linear-gradient(#fff_0_0)]
                        blur-[2px] opacity-95
                    ">
                    </div>

                    {{-- GEL CORE BLUR (soft & thick) --}}
                    <div
                        class="
                        absolute inset-0 rounded-lg
                        backdrop-blur-[30px]
                        bg-white/12 dark:bg-white/6
                        shadow-[inset_0_0_25px_rgba(255,255,255,0.25)]
                        z-[0]
                    ">
                    </div>

                    <!-- Hero Background -->
                    <div class="relative h-64 w-full">
                        <img src="{{ asset('images/background/class.webp') }}" class="w-full h-full object-cover" />
                    </div>

                    <!-- Content -->
                    <div class="relative p-8 flex items-center justify-between">
                        <!-- Left section -->
                        <div class="flex items-center gap-6">
                            <!-- Avatar -->
                            <div class="absolute -top-15">
                                <div class="w-40 h-40 rounded-2xl overflow-hidden shadow-xl border-4 border-white">
                                    @if (!empty($attachmentDetail->studentAttachment->photo))
                                        <img src="{{ asset($attachmentDetail->studentAttachment->photo) }}"
                                            class="w-full h-full object-cover" />
                                    @else
                                        <div class="flex items-center justify-center bg-white/80">
                                            <flux:icon.user class="size-38 text-dark/80" />
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="ml-44">
                                <flux:heading size="3xl" class="font-semibold truncate max-w-[600px]">
                                    {{ $attachmentDetail->student_name }}
                                </flux:heading>

                                <div class="flex items-center gap-6 text-white/75 mt-2">
                                    <div class="flex items-center gap-2">
                                        <flux:icon.school variant="mini" />
                                        {{ $attachmentDetail->branch_name }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <flux:icon.book-marked variant="mini" />
                                        {{ $attachmentDetail->program_name }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <flux:icon.graduation-cap variant="mini" />
                                        {{ $attachmentDetail->academic_year }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <flux:icon.file-digit variant="mini" />
                                        {{ $attachmentDetail->reg_number }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-animations.fade-down>
        <!--#HERO COVER STUDENT PROFILE-->

        <!--ANCHOR - ATTACHMENT STUDENT-->
        <x-animations.fade-down showTiming="150">
            <div class="grid grid-cols-1 mt-4">
                <x-animations.fancybox>
                    <x-cards.soft-glass-card rounded="rounded-lg">
                        <flux:heading size="xl" class="font-semibold">Berkas Siswa</flux:heading>

                        <form 
                        x-data="formValidation({
                            photoStatus: ['required'],
                            bornCardStatus: ['required'],
                            familyCardStatus: ['required'],
                            parentCardStatus: ['required'],
                        })"
                        x-init="
                        form.photoStatus = $wire.inputs.photoStatus;
                        form.bornCardStatus = $wire.inputs.bornCardStatus;
                        form.familyCardStatus = $wire.inputs.familyCardStatus;
                        form.parentCardStatus = $wire.inputs.parentCardStatus;
                        "
                        wire:submit="updateAttachmentStatus">
                            <div class="grid grid-cols-2 space-y-6 mt-4">
                                <!--Student's Photo-->
                                <div class="col-span-1">
                                    <div class="flex flex-col items-start gap-1">
                                        <flux:text size="lg">Photo Siswa</flux:text>
                                        <x-cards.photo-frame fancyBoxName="student-attachment" fancyBoxCaption="Photo Siswa"
                                            href="{{ asset($attachmentDetail->studentAttachment->photo) }}">
                                            <img src="{{ asset($attachmentDetail->studentAttachment->photo) }}"
                                                class="w-full h-full object-cover" />
                                        </x-cards.photo-frame>
                                        <div class="w-4/6">
                                            <flux:select 
                                                class="w-full"
                                                label="Status Photo"
                                                wire:model='inputs.photoStatus'
                                                x-on:input="form.photoStatus = $event.target.value; validate('photoStatus')"
                                                placeholder="--Pilih Satu--">
                                                <flux:select.option value="Valid">Valid</flux:select.option>
                                                <flux:select.option value="Tidak Valid">Tidak Valid</flux:select.option>
                                            </flux:select>
                                        </div>
                                    </div>
                                </div>
                                <!--#Student's Photo-->

                                <!--Student's Birth Card-->
                                <div class="col-span-1">
                                    <div class="flex flex-col items-start gap-1">
                                        <flux:text size="lg">Akte Kelahiran</flux:text>
                                        <x-cards.photo-frame fancyBoxName="student-attachment" fancyBoxCaption="Akte Kelahiran"
                                            href="{{ asset($attachmentDetail->studentAttachment->born_card) }}">
                                            <img src="{{ asset($attachmentDetail->studentAttachment->born_card) }}"
                                                class="w-full h-full object-cover" />
                                        </x-cards.photo-frame>
                                        <div class="w-4/6">
                                            <flux:select 
                                                class="w-full"
                                                label="Status Akte Kelahiran"
                                                wire:model='inputs.bornCardStatus'
                                                x-on:input="form.bornCardStatus = $event.target.value; validate('bornCardStatus')"
                                                placeholder="--Pilih Satu--">
                                                <flux:select.option value="Valid">Valid</flux:select.option>
                                                <flux:select.option value="Tidak Valid">Tidak Valid</flux:select.option>
                                            </flux:select>
                                        </div>
                                    </div>
                                </div>
                                <!--#Student's Birth Card-->

                                <!--Student's Parent Card-->
                                <div class="col-span-1">
                                    <div class="flex flex-col items-start gap-1">
                                        <flux:text size="lg">KTP Orang Tua</flux:text>
                                        <x-cards.photo-frame fancyBoxName="student-attachment" fancyBoxCaption="KTP Orang Tua"
                                            href="{{ asset($attachmentDetail->studentAttachment->parent_card) }}">
                                            <img src="{{ asset($attachmentDetail->studentAttachment->parent_card) }}"
                                                class="w-full h-full object-cover" />
                                        </x-cards.photo-frame>
                                        <div class="w-4/6">
                                            <flux:select 
                                                class="w-full"
                                                label="Status KTP Orang Tua"
                                                wire:model='inputs.parentCardStatus'
                                                x-on:input="form.parentCardStatus = $event.target.value; validate('parentCardStatus')"
                                                placeholder="--Pilih Satu--">
                                                <flux:select.option value="Valid">Valid</flux:select.option>
                                                <flux:select.option value="Tidak Valid">Tidak Valid</flux:select.option>
                                            </flux:select>
                                        </div>
                                    </div>
                                </div>
                                <!--#Student's Parent Card-->

                                <!--Student's Family Card-->
                                <div class="col-span-1">
                                    <div class="flex flex-col items-start gap-1">
                                        <flux:text size="lg">Kartu Keluaga</flux:text>
                                        <x-cards.photo-frame fancyBoxName="student-attachment" fancyBoxCaption="Kartu Keluaga"
                                            href="{{ asset($attachmentDetail->studentAttachment->family_card) }}">
                                            <img src="{{ asset($attachmentDetail->studentAttachment->family_card) }}"
                                                class="w-full h-full object-cover" />
                                        </x-cards.photo-frame>
                                        <div class="w-4/6">
                                            <flux:select 
                                                class="w-full"
                                                label="Status Kartu Keluarga"
                                                wire:model='inputs.familyCardStatus'
                                                x-on:input="form.familyCardStatus = $event.target.value; validate('familyCardStatus')"
                                                placeholder="--Pilih Satu--">
                                                <flux:select.option value="Valid">Valid</flux:select.option>
                                                <flux:select.option value="Tidak Valid">Tidak Valid</flux:select.option>
                                            </flux:select>
                                        </div>
                                    </div>
                                </div>
                                <!--#Student's Family Card-->
                            </div>

                            <!--NOTE: Show invalid message if it is revision before-->
                            @if ($attachmentDetail->attachment == \App\Enums\VerificationStatusEnum::PROCESS && ($attachmentDetail->attachment_error_msg != null))
                            <div class="grid grid-cols-1 mt-4">    
                                <div class="col-span-1">
                                    <div class="flex flex-col items-start">
                                        <flux:text variant="soft" size="sm">Alasan Invalid Sebelumnya</flux:text>
                                        <flux:text variant="solid">{{ $attachmentDetail->attachment_error_msg }}</flux:text>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!--#Show invalid message if it is revision before-->

                            <!--NOTE: Invalid Message Box-->
                            <template x-if="$wire.inputs.photoStatus == 'Tidak Valid' || $wire.inputs.bornCardStatus == 'Tidak Valid' || $wire.inputs.parentCardStatus == 'Tidak Valid' || $wire.inputs.familyCardStatus == 'Tidak Valid'">
                                <div class="grid grid-cols-1 mt-4">
                                    <div class="col-span-1">
                                        <flux:textarea
                                        label="Alasan Tidak Valid"
                                        row="3"
                                        placeholder="Tulis dengan jelas alasan dan instruksi memperbaiki nya"
                                        wire:model="inputs.invalidReason"
                                        />

                                        <template x-if="errors.inputs.invalidReason">
                                            <flux:error name="inputs.invalidReason">
                                                <x-slot:message>
                                                    <span x-text="errors.inputs.invalidReason"></span>
                                                </x-slot:message>
                                            </flux:error>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <!--#Invalid Message Box-->

                            <!--NOTE: Alert when save is error or failed-->
                            @if (session('error-update-attachment'))
                                <div class="grid grid-cols-1 mt-4">
                                    <div class="col-span-1">
                                        <x-notifications.basic-alert isCloseable="true">
                                            <x-slot:title>{{ session('error-update-attachment') }}</x-slot:title>
                                        </x-notifications.basic-alert>
                                    </div>
                                </div>
                            @endif
                            <!--#Alert when save is error or failed-->

                            <!--NOTE: Action Button-->
                            <div class="flex gap-2 justify-start mt-4">
                                <flux:button 
                                    type="submit" 
                                    variant="primary" 
                                    x-bind:disabled="!isSubmitActive"
                                    :loading="false"
                                    icon="check-check">
                                    <x-items.loading-indicator wireTarget="updateAttachmentStatus">
                                        <x-slot:buttonName>Simpan</x-slot:buttonName>
                                        <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                                    </x-items.loading-indicator>
                                </flux:button>

                                <flux:button 
                                    icon="undo-2" 
                                    variant="filled"
                                    href="{{ route('admin.data_verification.student_attachment.process') }}" 
                                    wire:navigate>
                                    Kembali
                                </flux:button>
                            </div>
                            <!--#Action Button-->
                        </form>
                    </x-cards.soft-glass-card>
                </x-animations.fancybox>
            </div>
        </x-animations.fade-down>
        <!--#ATTACHMENT STUDENT-->
    @endif

</div>
