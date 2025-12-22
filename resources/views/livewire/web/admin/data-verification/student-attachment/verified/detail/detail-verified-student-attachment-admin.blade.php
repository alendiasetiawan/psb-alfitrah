<div>
    <x-navigations.breadcrumb secondLink="{{ route('admin.data_verification.student_attachment.verified') }}">
        <x-slot:title>{{ __('Detail Berks Siswa') }}</x-slot:title>
        <x-slot:secondPage>{{ __('Berkas Selesai') }}</x-slot:secondPage>
        <x-slot:activePage>{{ __('Informasi Berkas Siswa') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    @if (session('error-show-attachment'))
        <div class="grid grid-cols-1 mt-4">
            <div class="col-span-1">
                <x-notifications.basic-alert isCloseable="true">
                    <x-slot:title>{{ session('error-show-attachment') }}</x-slot:title>
                </x-notifications.basic-alert>
            </div>
        </div>
    @else
        <!--ANCHOR - HERO COVER STUDENT PROFILE-->
        <x-animations.fade-down showTiming="50">
            <div class="grid grid-cols-1 mt-4">
                <x-cards.hero-card
                    :isAvatar="!empty($attachmentDetail->studentAttachment->photo)"
                    :avatarImageAsset="$attachmentDetail->studentAttachment->photo">
                    <x-slot:title>
                        {{ $attachmentDetail->student_name }}
                    </x-slot:title>
                    <x-slot:firstSubTitle>
                        <flux:icon.school variant="mini" />
                        {{ $attachmentDetail->branch_name }}
                    </x-slot:firstSubTitle>
                    <x-slot:secondSubTitle>
                        <flux:icon.book-marked variant="mini" />
                        {{ $attachmentDetail->program_name }}
                    </x-slot:secondSubTitle>
                    <x-slot:thirdSubTitle>
                        <flux:icon.graduation-cap variant="mini" />
                        {{ $attachmentDetail->academic_year }}
                    </x-slot:thirdSubTitle>
                    <x-slot:fourthSubTitle>
                        <flux:icon.file-digit variant="mini" />
                        {{ $attachmentDetail->reg_number }}
                    </x-slot:fourthSubTitle>
                </x-cards.hero-card>

            </div>
        </x-animations.fade-down>
        <!--#HERO COVER STUDENT PROFILE-->

        <!--ANCHOR - ATTACHMENT STUDENT-->
        <x-animations.fade-down showTiming="150">
            <div class="grid grid-cols-1 mt-4">
                <x-animations.fancybox>
                    <x-cards.soft-glass-card rounded="rounded-lg">
                        <flux:heading size="xl" class="font-semibold">Berkas Siswa</flux:heading>

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
                                </div>
                            </div>
                            <!--#Student's Family Card-->
                        </div>

                        <div class="flex items-start">
                            <flux:button
                                icon="undo-2"
                                href="{{ route('admin.data_verification.student_attachment.verified') }}"
                                wire:navigate
                                variant="filled">
                                Kembali
                            </flux:button>
                        </div>
                    </x-cards.soft-glass-card>
                </x-animations.fancybox>
            </div>
        </x-animations.fade-down>
        <!--#ATTACHMENT STUDENT-->
    @endif

</div>
