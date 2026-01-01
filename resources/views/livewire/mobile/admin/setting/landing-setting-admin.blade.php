<div>
    <div class="grid grid-cols-1 mt-4 gap-4">
        <x-animations.fade-down showTiming="50">
            <!--ANCHOR: System PSB-->
            <div class="space-y-1">
                <div class="col-span-1">
                    <flux:heading size="lg">Sistem PSB</flux:heading>
                </div>

                <div class="col-span-1">
                    <x-cards.soft-glass-card rounded="rounded-md">
                        <div class="space-y-4">
                            <!--NOTE: Academic Year-->
                            <a href="{{ route('admin.setting.admission_draft.academic_year') }}" wire:navigate class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="bg-primary-200 p-1 rounded-md">
                                        <flux:icon.graduation-cap variant="mini" class="text-primary-600"/>
                                    </div>
                                    <flux:text variant="bold">
                                        Tahun Akademik
                                    </flux:text>
                                </div>

                                <div>
                                    <flux:icon.chevron-right variant="mini" class="text-white"/>
                                </div>
                            </a>
                            <!--#Academic Year-->

                            <!--NOTE: Student Quota-->
                            <a href="{{ route('admin.setting.admission_draft.student_quota') }}" wire:navigate class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="bg-green-200 p-1 rounded-md">
                                        <flux:icon.user-check variant="mini" class="text-green-600"/>
                                    </div>
                                    <flux:text variant="bold">
                                        Kuota Penerimaan
                                    </flux:text>
                                </div>

                                <div>
                                    <flux:icon.chevron-right variant="mini" class="text-white"/>
                                </div>
                            </a>
                            <!--#Student Quota-->

                            <!--NOTE: Registration Fee-->
                            <a href="{{ route('admin.setting.admission_draft.registration_fee') }}" wire:navigate class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="bg-blue-200 p-1 rounded-md">
                                        <flux:icon.banknote-arrow-down variant="mini" class="text-blue-600"/>
                                    </div>
                                    <flux:text variant="bold">
                                        Biaya Pendaftaran
                                    </flux:text>
                                </div>

                                <div>
                                    <flux:icon.chevron-right variant="mini" class="text-white"/>
                                </div>
                            </a>
                            <!--#Registration Fee-->
                            
                        </div>
                    </x-cards.soft-glass-card>
                </div>
            </div>
            <!--#System PSB-->
        </x-animations.fade-down>

        <!--ANCHOR: School Identity-->
        <x-animations.fade-down showTiming="150">
            <div class="space-y-1">        
                <div class="col-span-1">
                    <flux:heading size="lg">Indentitas Pondok</flux:heading>
                </div>

                <div class="col-span-1">
                    <x-cards.soft-glass-card rounded="rounded-md">
                        <div class="space-y-4">
                            <!--NOTE: Branch-->
                            <a href="{{ route('admin.setting.school.branch') }}" wire:navigate class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="bg-rose-200 p-1 rounded-md">
                                        <flux:icon.school variant="mini" class="text-rose-600"/>
                                    </div>
                                    <flux:text variant="bold">
                                        Cabang Pondok
                                    </flux:text>
                                </div>

                                <div>
                                    <flux:icon.chevron-right variant="mini" class="text-white"/>
                                </div>
                            </a>
                            <!--#Branch-->

                            <!--NOTE: Education Program-->
                            <a href="{{ route('admin.setting.school.program') }}" wire:navigate class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="bg-sky-200 p-1 rounded-md">
                                        <flux:icon.book-marked variant="mini" class="text-sky-600"/>
                                    </div>
                                    <flux:text variant="bold">
                                        Program Pendidikan
                                    </flux:text>
                                </div>

                                <div>
                                    <flux:icon.chevron-right variant="mini" class="text-white"/>
                                </div>
                            </a>
                            <!--#Education Program-->                    
                        </div>
                    </x-cards.soft-glass-card>
                </div>
            </div>
        </x-animations.fade-down>
        <!--#School Identity-->
    </div>
</div>
