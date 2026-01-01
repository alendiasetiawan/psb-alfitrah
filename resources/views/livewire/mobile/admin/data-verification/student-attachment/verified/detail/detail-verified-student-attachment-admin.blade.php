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
                                </div>
                            </div>
                            <!--#Student's Family Card-->
                        </div>
                    </x-animations.fancybox>

                    <!--NOTE: Action Button-->
                    <div class="flex justify-start mt-4">
                        <flux:button icon="undo-2" variant="filled" class="w-full"
                            href="{{ route('admin.data_verification.student_attachment.verified') }}" wire:navigate>
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
