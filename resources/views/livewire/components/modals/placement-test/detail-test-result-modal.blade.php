<div>
    <flux:modal name="{{ $modalId }}" 
        variant="{{ $isMobile ? 'flyout' : '' }}" 
        position="{{ $isMobile ? 'bottom' : '' }}"
        class="max-h-[calc(100vh-15rem)]"
    >
        <div 
            x-data="{
               isModalLoading: false
            }"
            x-on:open-detail-test-result-modal.window="
               isModalLoading = true;
               $wire.setDetailValue($event.detail.id).then(() => {
                   isModalLoading = false
               });"
        >
            <!--Loading Skeleton-->
            <template x-if="isModalLoading">
                <div class="space-y-4 animate-pulse">
                    <x-loading.title-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.button-skeleton/>
                </div>
            </template>
            <!--#Loading Skeleton-->

            <flux:heading size="xxl" class="text-center"><strong>Detail Hasil Tes</strong></flux:heading>

            <div class="space-y-4" x-show="!isModalLoading">

                <!--SECTION: STUDENT INFO-->
                <div class="grid grid-cols-2 lg:grid-cols-3 mt-4 gap-6">
                    <!--Student Name-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Nama Siswa</flux:text>
                            <flux:text>{{ $testQuery?->student_name }}</flux:text>
                        </div>
                    </div>
                    <!--#Student Name-->

                    <!--Branch Name-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Cabang</flux:text>
                            <flux:text>{{ $testQuery?->branch_name }}</flux:text>
                        </div>
                    </div>
                    <!--#Branch Name-->

                    <!--Program Name-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Program</flux:text>
                            <flux:text>{{ $testQuery?->program_name }}</flux:text>
                        </div>
                    </div>
                    <!--#Program Name-->

                    <!--Academic Year-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Tahun Ajaran</flux:text>
                            <flux:text>{{ $testQuery?->academic_year }}</flux:text>
                        </div>
                    </div>
                    <!--#Academic Year-->

                    <!--Batch-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Batch</flux:text>
                            <flux:text>{{ $testQuery?->batch_name }}</flux:text>
                        </div>
                    </div>
                    <!--#Batch-->

                    <!--Test Date-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Waktu Kehadiran Tes</flux:text>
                            <flux:text>{{ \App\Helpers\DateFormatHelper::indoDateTime($testQuery?->check_in_time) }}</flux:text>
                        </div>
                    </div>
                    <!--#Test Date-->

                    <!--Psikotest-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Nilai Psikotest</flux:text>
                            <flux:text>{{ $testQuery->psikotest_score ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Psikotest-->

                    <!--Al Quran-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Nilai Al Quran</flux:text>
                            <flux:text>{{ $testQuery->read_quran_score ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Al Quran-->

                    <!--Al Quran-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Penguji Al Qur'an</flux:text>
                            <flux:text>{{ $testQuery->read_quran_tester ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Al Quran-->
                </div>
                <!--#STUDENT INFO-->

                <!--SECTION: SCORE-->
                <div class="grid grid-cols-2 gap-6">
                    <!--Parent Interview-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Wawancara Orang Tua</flux:text>
                            <flux:text>{{ $testQuery->parent_interview ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Parent Interview-->

                    <!--Parent Interview Tester-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Penguji Wawancara Orang Tua</flux:text>
                            <flux:text>{{ $testQuery->parent_interview_tester ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Parent Interview Tester-->

                    <!--Student Interview-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Wawancara Siswa</flux:text>
                            <flux:text>{{ $testQuery->student_interview ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Student Interview-->

                    <!--Student Interview-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Penguji Wawancara Siswa</flux:text>
                            <flux:text>{{ $testQuery->student_interview_tester ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Student Interview Tester-->

                    <!--Final Score-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Nilai Akhir</flux:text>
                            <flux:text>{{ $testQuery->final_score ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Final Score-->

                    <!--Final Result-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Hasil Akhir</flux:text>
                            <flux:text>{{ $testQuery->final_result ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Final Result-->

                    <!--Status Publication-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Status Publikasi</flux:text>
                            <flux:text>{{ $testQuery->publication_status ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Status Publication-->

                    <!--Publication Date-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Tanggal Publikasi</flux:text>
                            <flux:text>{{ \App\Helpers\DateFormatHelper::shortIndoDate($testQuery?->publication_date) ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Publication Date-->
                </div>

                <div class="grid grid-cols-1 space-y-4">
                    <!--Notes-->
                    <div class="col-span-1">
                        <div class="flex flex-col items-start">
                            <flux:text variant="soft">Catatan</flux:text>
                            <flux:text>{{ $testQuery->final_note ?? '-' }}</flux:text>
                        </div>
                    </div>
                    <!--#Notes-->
                </div>
                <!--#SCORE-->

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="filled" icon="undo-2">Kembali</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </div>
    </flux:modal>
</div>
