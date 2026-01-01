<div>
    <x-cards.roll-over-page>
        <x-slot:label>{{ $attachmentDetail->academic_year }}</x-slot:label>

        <x-slot:heading>{{ $attachmentDetail->student_name }}</x-slot:heading>

        <x-slot:subHeading>
            <div class="flex items-center justify-start gap-3">
                <div class="flex items-center gap-1">
                    <flux:icon.school variant="micro" class="text-white/75" />
                    <flux:text variant="soft">{{ $attachmentDetail->branch_name }}</flux:text>
                </div>
                <div class="flex items-center gap-1">
                    <flux:icon.book-marked variant="micro" class="text-white/75" />
                    <flux:text variant="soft">{{ $attachmentDetail->program_name }}</flux:text>
                </div>
            </div>
        </x-slot:subHeading>

        <x-cards.soft-glass-card rounded="rounded-t-2xl">
            {{-- Divider --}}
            <div class="flex justify-center w-full pb-4 cursor-pointer rounded">
                <div class="rounded-full bg-gray-300/50 h-1.5 w-16"></div>
            </div>
            {{-- #Divider --}}

            <x-animations.fade-down showTiming="50">
                <form wire:submit="updateAttachmentStatus" 
                x-data="formValidation({
                    photoStatus: ['required'],
                    bornCardStatus: ['required'],
                    familyCardStatus: ['required'],
                    parentCardStatus: ['required'],
                })" 
                x-init="form.photoStatus = $wire.inputs.photoStatus;
                form.bornCardStatus = $wire.inputs.bornCardStatus;
                form.familyCardStatus = $wire.inputs.familyCardStatus;
                form.parentCardStatus = $wire.inputs.parentCardStatus;">
                    <!--ANCHOR: STUDENT ATTACHMENT-->
                    <flux:heading size="xl" class="mb-3 mt-5">Berkas Santri</flux:heading>

                    <x-animations.fancybox>
                        <div class="grid grid-cols-1 space-y-6 mt-4">
                            <!--Student's Photo-->
                            <div class="col-span-1">
                                <div class="flex flex-col items-start gap-1">
                                    <flux:text size="lg">Photo Siswa</flux:text>
                                    <x-cards.photo-frame fancyBoxName="student-attachment" fancyBoxCaption="Photo Siswa"
                                        href="{{ asset($attachmentDetail->studentAttachment->photo) }}">
                                        <img src="{{ asset($attachmentDetail->studentAttachment->photo) }}"
                                            class="w-full h-full object-cover" />
                                    </x-cards.photo-frame>
                                    <div class="w-full">
                                        <flux:select class="w-full" label="Status Photo" wire:model='inputs.photoStatus'
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
                                    <x-cards.photo-frame fancyBoxName="student-attachment"
                                        fancyBoxCaption="Akte Kelahiran"
                                        href="{{ asset($attachmentDetail->studentAttachment->born_card) }}">
                                        <img src="{{ asset($attachmentDetail->studentAttachment->born_card) }}"
                                            class="w-full h-full object-cover" />
                                    </x-cards.photo-frame>
                                    <div class="w-full">
                                        <flux:select class="w-full" label="Status Akte Kelahiran"
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
                                    <x-cards.photo-frame fancyBoxName="student-attachment"
                                        fancyBoxCaption="KTP Orang Tua"
                                        href="{{ asset($attachmentDetail->studentAttachment->parent_card) }}">
                                        <img src="{{ asset($attachmentDetail->studentAttachment->parent_card) }}"
                                            class="w-full h-full object-cover" />
                                    </x-cards.photo-frame>
                                    <div class="w-full">
                                        <flux:select class="w-full" label="Status KTP Orang Tua"
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
                                    <x-cards.photo-frame fancyBoxName="student-attachment"
                                        fancyBoxCaption="Kartu Keluaga"
                                        href="{{ asset($attachmentDetail->studentAttachment->family_card) }}">
                                        <img src="{{ asset($attachmentDetail->studentAttachment->family_card) }}"
                                            class="w-full h-full object-cover" />
                                    </x-cards.photo-frame>
                                    <div class="w-full">
                                        <flux:select class="w-full" label="Status Kartu Keluarga"
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
                    </x-animations.fancybox>

                    <!--NOTE: Show invalid message if it is revision before-->
                    @if (
                        $attachmentDetail->attachment == \App\Enums\VerificationStatusEnum::PROCESS &&
                            $attachmentDetail->attachment_error_msg != null)
                        <div class="grid grid-cols-1 mt-4">
                            <div class="col-span-1">
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">Alasan Invalid Sebelumnya</flux:text>
                                    <flux:text variant="solid">{{ $attachmentDetail->attachment_error_msg }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!--#Show invalid message if it is revision before-->

                    <!--NOTE: Invalid Message Box-->
                    <template
                        x-if="$wire.inputs.photoStatus == 'Tidak Valid' || $wire.inputs.bornCardStatus == 'Tidak Valid' || $wire.inputs.parentCardStatus == 'Tidak Valid' || $wire.inputs.familyCardStatus == 'Tidak Valid'">
                        <div class="grid grid-cols-1 mt-4">
                            <div class="col-span-1">
                                <flux:textarea label="Alasan Tidak Valid" row="3"
                                    placeholder="Tulis dengan jelas alasan dan instruksi memperbaiki nya"
                                    wire:model="inputs.invalidReason" />

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
                    <div class="flex flex-col gap-2 justify-start mt-4">
                        <flux:button type="submit" variant="primary" x-bind:disabled="!isSubmitActive"
                            :loading="false" icon="check-check" class="w-full">
                            <x-items.loading-indicator wireTarget="updateAttachmentStatus">
                                <x-slot:buttonName>Simpan</x-slot:buttonName>
                                <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                            </x-items.loading-indicator>
                        </flux:button>

                        <flux:button icon="undo-2" variant="filled" class="w-full"
                            href="{{ route('admin.data_verification.student_attachment.process') }}" wire:navigate>
                            Kembali
                        </flux:button>
                    </div>
                    <!--#Action Button-->
                    <!--#STUDENT ATTACHMENT-->
                </form>
            </x-animations.fade-down>
        </x-cards.soft-glass-card>
    </x-cards.roll-over-page>

    @push('scripts')
        <script type="text/javascript">
            function preventBack() {
                window.history.forward();
            }

            setTimeout("preventBack()", 0);

            window.onunload = function () { null };
        </script>
    @endpush
</div>
