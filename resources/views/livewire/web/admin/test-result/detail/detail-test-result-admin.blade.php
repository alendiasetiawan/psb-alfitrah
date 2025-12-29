<div>
    <x-navigations.breadcrumb firstLink="{{ route('admin.placement_test.test_result') }}">
        <x-slot:title>Nilai Tes</x-slot:title>
        <x-slot:firstPage>Hasil Tes</x-slot:firstPage>
        <x-slot:activePage>Kelola Nilai Hasil Tes Siswa</x-slot:activePage>
    </x-navigations.breadcrumb>

    {{-- <x-animations.fade-down> --}}
        <div class="grid grid-cols-1 mt-4">
            @if (session('error-fetch-data'))
                <div class="col-span-1">
                    <x-notifications.basic-alert>
                        <x-slot:title>{{ session('error-fetch-data') }}</x-slot:title>
                        <x-slot:subTitle>
                            <flux:button
                                icon="undo-2"
                                variant="filled"
                                href="{{ route('admin.placement_test.test_result') }}"
                                wire:navigate
                                size="sm">
                                Kembali
                            </flux:button>
                        </x-slot:subTitle>
                    </x-notifications.basic-alert>
                </div>
            @else
                <x-cards.soft-glass-card>
                    <!--SECTION: HEADER-->
                    <div class="flex justify-between items-center">
                        <flux:heading size="xl">Form Edit Nilai Tes Siswa</flux:heading>
                        <flux:modal.trigger name="add-edit-tester-modal">
                            <flux:button icon="users" variant="primary" wire:click="$dispatch('open-add-edit-tester-modal')">
                                Daftar Penguji
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                    <!--#HEADER-->

                    <form wire:submit="saveScore"
                        x-data="
                        formValidation({
                            psikotestScore: ['required', 'numeric'],
                            readQuranScore: ['required', 'numeric'],
                            studentInterview: ['required'],
                            parentInterview: ['required'],
                            finalScore: ['required', 'numeric'],
                            finalResult: ['required']
                        })"
                        x-init="
                            $watch('$wire.inputs.psikotestScore', () => {
                                $wire.inputs.finalScore = (Number($wire.inputs.psikotestScore * $wire.psikotestWeight || 0) + Number($wire.inputs.readQuranScore * $wire.readQuranWeight || 0))
                                if ($wire.inputs.finalScore >= $wire.minFinalScore) {
                                    $wire.inputs.finalResult = 'Lulus';
                                } else {
                                    $wire.inputs.finalResult = 'Tidak Lulus';
                                }
                                form.finalScore = $wire.inputs.finalScore;
                                form.finalResult = $wire.inputs.finalResult;
                            });
                            $watch('$wire.inputs.readQuranScore', () => {
                                $wire.inputs.finalScore = (Number($wire.inputs.psikotestScore * $wire.psikotestWeight || 0) + Number($wire.inputs.readQuranScore * $wire.readQuranWeight || 0))
                                if ($wire.inputs.finalScore >= $wire.minFinalScore) {
                                    $wire.inputs.finalResult = 'Lulus';
                                } else {
                                    $wire.inputs.finalResult = 'Tidak Lulus';
                                }
                                form.finalScore = $wire.inputs.finalScore;
                                form.finalResult = $wire.inputs.finalResult;
                            });
                        ">
                        <!--SECTION: STUDENT DATA-->
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4 space-y-2 gap-4">
                            <!--Student Name-->
                            <div class="col-span-1" wire:ignore>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft">Nama Siswa</flux:text>
                                    <flux:text variant="bold">{{ $detailTest->student_name }}</flux:text>
                                </div>
                            </div>
                            <!--#Student Name-->

                            <!--Branch Name-->
                            <div class="col-span-1" wire:ignore>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft">Cabang</flux:text>
                                    <flux:text>{{ $detailTest->branch_name }}</flux:text>
                                </div>
                            </div>
                            <!--#Branch Name-->

                            <!--Program Name-->
                            <div class="col-span-1" wire:ignore>
                                <div class="flex flex-col items-start">
                                    <flux:text variant="soft">Program</flux:text>
                                    <flux:text>{{ $detailTest->program_name }}</flux:text>
                                </div>
                            </div>
                            <!--#Program Name-->

                            <!--Psikotest Score-->
                            <div class="col-span-1">
                                <flux:input 
                                    type="numeric"
                                    label="Nilai Psikotes"
                                    placeholder="1-100"
                                    icon="brain"
                                    wire:model="inputs.psikotestScore"
                                    :isValidate="true"
                                    fieldName="psikotestScore"
                                />
                            </div>
                            <!--#Psikotest Score-->

                            <!--Read Quran Score-->
                            <div class="col-span-1">
                                <flux:input 
                                    type="numeric"
                                    label="Nilai Al Quran"
                                    placeholder="1-100"
                                    icon="book-open"
                                    wire:model="inputs.readQuranScore"
                                    :isValidate="true"
                                    fieldName="readQuranScore"
                                />
                            </div>
                            <!--#Read Quran Score-->

                            <!--Read Quran Tester-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label badge="Opsional">Penguji Al Quran</flux:label>
                                    <flux:select 
                                    x-on:change="form.selectedReadQuranTester = $wire.inputs.selectedReadQuranTester; validate('selectedReadQuranTester')"
                                    wire:model='inputs.selectedReadQuranTester'
                                    placeholder="--Pilih Penguji--">
                                        @foreach ($testerLists as $key => $value)
                                            <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                            <!--#Read Quran Tester-->
                        </div>
                        <!--#TEST RESULT DATA-->

                        <!--SECTION: INTERVIEW RESULT-->
                        <div class="grid md:grid-cols-2 lg:grid-cols-2 mt-4 space-y-2 gap-4">
                            <!--Student Interview-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>Wawancara Santri</flux:label>
                                    <flux:select 
                                    x-on:change="form.studentInterview = $wire.inputs.studentInterview; validate('studentInterview')"
                                    wire:model='inputs.studentInterview'
                                    placeholder="--Pilih Hasil--">
                                        <flux:select.option>Direkomendasikan</flux:select.option>
                                        <flux:select.option>Dipertimbangkan</flux:select.option>
                                        <flux:select.option>Tidak Direkomendasikan</flux:select.option>
                                    </flux:select>
                                </flux:field>
                            </div>
                            <!--#Student Interview-->

                            <!--Student Interview Tester-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label badge="Opsional">Penguji Wawancara Santri</flux:label>
                                    <flux:select 
                                    wire:model='inputs.selectedStudentInterviewTester'
                                    placeholder="--Pilih Penguji--">
                                        @foreach ($testerLists as $key => $value)
                                            <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                            <!--#Student Interview Tester-->

                            <!--Parent Interview-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>Wawancara Orang Tua</flux:label>
                                    <flux:select 
                                    x-on:change="form.parentInterview = $wire.inputs.parentInterview; validate('parentInterview')"
                                    wire:model='inputs.parentInterview'
                                    placeholder="--Pilih Hasil--">
                                        <flux:select.option>Direkomendasikan</flux:select.option>
                                        <flux:select.option>Dipertimbangkan</flux:select.option>
                                        <flux:select.option>Tidak Direkomendasikan</flux:select.option>
                                    </flux:select>
                                </flux:field>
                            </div>
                            <!--#Parent Interview-->

                            <!--Parent Interview Tester-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label badge="Opsional">Penguji Wawancara Santri</flux:label>
                                    <flux:select 
                                    wire:model='inputs.selectedParentInterviewTester'
                                    placeholder="--Pilih Penguji--">
                                        @foreach ($testerLists as $key => $value)
                                            <flux:select.option value="{{ $key }}">{{ $value }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                            <!--#Parent Interview Tester-->
                        </div>
                        <!--#INTERVIEW RESULT-->

                        <!--SECTION: FINAL RESULT-->
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 mt-4 space-y-2 gap-4">
                            <!--Final Score-->
                            <div class="col-span-1">
                                <flux:input 
                                    type="numeric"
                                    label="Nilai Akhir"
                                    placeholder="1-100"
                                    icon="brain"
                                    wire:model="inputs.finalScore"
                                    :isValidate="true"
                                    fieldName="finalScore"
                                />
                            </div>
                            <!--#Final Score-->

                            <!--Final Result-->
                            <div class="col-span-1">
                                <flux:field>
                                    <flux:label>Hasil Akhir</flux:label>
                                    <flux:select 
                                    x-on:change="form.finalResult = $wire.inputs.finalResult; validate('finalResult')"
                                    wire:model='inputs.finalResult'
                                    placeholder="--Pilih Hasil--">
                                        <flux:select.option>Menunggu</flux:select.option>
                                        <flux:select.option>Lulus</flux:select.option>
                                        <flux:select.option>Tidak Lulus</flux:select.option>
                                        <flux:select.option>Cadangan</flux:select.option>
                                    </flux:select>
                                </flux:field>
                            </div>
                            <!--#Final Result-->

                            <!--Final Notes-->
                            <div class="col-span-1">
                                <flux:textarea
                                    label="Catatan Akhir"
                                    placeholder="Isi catatan hasil tes siswa"
                                    wire:model="inputs.finalNote"
                                />
                            </div>
                            <!--#Final Notes-->
                        </div>
                        <!--#FINAL RESULT-->

                        <!--SECTION: ACTION BUTTON-->
                        <div class="flex justify-end items-center mt-4 gap-2">
                            <flux:button
                                type="submit"
                                variant="primary"
                                x-bind:disabled="!isSubmitActive"
                                :loading="false">
                                <x-items.loading-indicator wireTarget="saveScore">
                                    <x-slot:buttonName>Simpan</x-slot:buttonName>
                                    <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                                </x-items.loading-indicator>
                            </flux:button>

                            <flux:modal.close>
                                <flux:button variant="filled" href="{{ route('admin.placement_test.test_result') }}" wire:navigate>Batal</flux:button>
                            </flux:modal.close>
                        </div>
                        <!--#ACTION BUTTON-->
                    </form>
                </x-cards.soft-glass-card>
            @endif
        </div>
    {{-- </x-animations.fade-down> --}}

    <!--SECTION: ADD EDIT TESTER MODAL-->
    <livewire:components.modals.placement-test.add-edit-tester-modal modalId="add-edit-tester-modal" :$isMobile :$testerLists/>
    <!--#ADD EDIT TESTER MODAL-->
</div>
