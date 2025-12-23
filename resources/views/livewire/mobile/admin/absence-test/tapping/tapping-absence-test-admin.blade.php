<div>
    <div class="grid grid-cols-1 mt-4">
        <div class="col-span-1">
            <x-animations.fade-down showTiming="50">
                <x-cards.soft-glass-card padding="p-4">
                    <!--NOTE: Search field for tapping--->
                    <form wire:submit="scanQr">
                        <div class="flex flex-col justify-center items-center text-center mt-5">
                            <flux:heading size="xl">Absensi Kehadiran Tes Masuk TA {{ $academicYear }}</flux:heading>
                            <div class="flex items-center gap-2 my-3">
                                <flux:input
                                icon="qr-code"
                                wire:model="studentQr"
                                placeholder="QR Code"
                                autofocus
                                tabindex="1"
                                />

                                <flux:button
                                type="submit"
                                variant="primary"
                                tabindex="2">
                                    Scan
                                </flux:button>
                            </div>
                        </div>
                    </form>
                    <!--#Search field for tapping--->

                    <!--NOTE: Badge Counter-->
                    <div class="flex justify-between items-center mb-6 gap-2">
                        <flux:badge
                        icon="user-check"
                        variant="solid"
                        color="green">
                            Hadir : {{ $this->presenceStudents['studentLists'][0]->total_presence ?? 0 }}
                        </flux:badge>

                        <flux:badge
                        icon="user-x"
                        variant="solid"
                        color="red">
                            Tidak Hadir : {{ $this->presenceStudents['totalAbsence'] ?? 0 }}
                        </flux:badge>
                    </div>
                    <!--#Badge Counter-->

                    @if (session('error-tapping'))
                        <div class="grid grid-cols-1 my-4 mx-4">
                            <x-notifications.basic-alert>
                                <x-slot:title>{{ session('error-tapping') }}</x-slot:title>
                            </x-notifications.basic-alert>
                        </div>
                    @endif

                    <!--NOTE: Student's Table-->
                    @forelse ($this->presenceStudents['studentLists'] as $presence)
                        <div class="flex flex-col my-3">
                            <flux:heading size="lg" class="truncate max-w-[250px]">{{ $presence->student->student_name }}</flux:heading>
                            <div class="flex justify-between items-center">
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft" size="sm">{{ $presence->student->branch_name }}</flux:text>
                                    <flux:text variant="soft" size="sm">{{ $presence->student->program_name }}</flux:text>
                                </div>

                                <div class="flex flex-col items-end">
                                    <flux:text variant="soft" size="sm">{{ \App\Helpers\DateFormatHelper::shortIndoDate($presence->check_in_time) }}</flux:text>
                                    <flux:text variant="soft" size="sm">{{ \App\Helpers\DateFormatHelper::shortTime($presence->check_in_time) }}</flux:text>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex justify-center items-center mt-4">
                            <flux:text variant="soft">Belum ada data yang bisa ditampilkan</flux:text>
                        </div>
                    @endforelse
                    <!--#Student's Table-->

                    <!--NOTE: Load more button-->
                    @if($this->presenceStudents['studentLists']->hasMorePages())
                        <div class="grid grid-cols-1 mt-4">
                            <div class="col-span-1">
                                <livewire:components.buttons.load-more loadItem="18" />
                            </div>
                        </div>
                    @endif
                    <!--#Load more button-->
                </x-cards.soft-glass-card>
            </x-animations.fade-down>
        </div>
    </div>
</div>
