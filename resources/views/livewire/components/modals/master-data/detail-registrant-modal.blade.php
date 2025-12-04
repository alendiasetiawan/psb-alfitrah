@props([
    'isMobile' => false
])

<div>
    <flux:modal name="{{ $modalId }}" class="md:w-100 lg:w-120" @close="resetAllProperty" variant="{{ $isMobile ? 'flyout' : '' }}" position="{{ $isMobile ? 'bottom' : '' }}">
        <div
            x-data="{
                isModalLoading: false
            }"
            x-on:open-detail-registrant-modal.window="
            isModalLoading = true;
            $wire.setDetailValue($event.detail.id).then(() => {
                isModalLoading = false
            });"
            x-on:reset-submit.window="
            isSubmitActive = false;"
        >
            <!--Loading Skeleton-->
            <template x-if="isModalLoading">
                <div class="space-y-4 animate-pulse">
                    <x-loading.title-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.form-skeleton/>
                    <x-loading.button-skeleton/>
                </div>
            </template>
            <!--#Loading Skeleton-->

            <div class="space-y-3" x-show="!isModalLoading">
                <flux:heading size="xxl" class="text-center">
                        Detail Data Pendaftar
                </flux:heading>

                <div class="flex justify-center items-center">
                    <div class="flex flex-col items-center gap-2">
                        <flux:avatar size="xxl" 
                            icon="user"
                            src="{{ $detailRegistrantData?->user_photo ? asset('storage/' . $detailRegistrantData->user_photo) : '' }}"/>
                        @if ($detailRegistrantData?->payment_status == \App\Enums\VerificationStatusEnum::VALID)
                            <flux:badge color="green" size="sm" icon="banknotes">
                                Sudah Bayar
                            </flux:badge>
                        @else
                            <flux:badge color="red" size="sm" icon="x-circle">
                                Belum Bayar
                            </flux:badge>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1">
                    <div class="col-span-1 text-center">
                        <flux:heading>Nomor Registrasi</flux:heading>
                        <flux:text variant="soft">{{ $detailRegistrantData?->reg_number }}</flux:text>
                    </div>
                </div>
            
                <div class="grid md:grid-cols-2 gap-3 justify-between">
                    <div class="col-span-1">
                        <flux:heading>Nama Santri</flux:heading>
                        <flux:text variant="soft">{{ $detailRegistrantData?->student_name }}</flux:text>
                    </div>
                    <div class="col-span-1">
                        <flux:heading>Jenis Kelamin</flux:heading>
                        <flux:text variant="soft">{{ $detailRegistrantData?->gender }}</flux:text>
                    </div>
                    <div class="col-span-1">
                        <flux:heading>Cabang</flux:heading>
                        <flux:text variant="soft">{{ $detailRegistrantData?->branch_name }}</flux:text>
                    </div>
                    <div class="col-span-1">
                        <flux:heading>Program</flux:heading>
                        <flux:text variant="soft">{{ $detailRegistrantData?->program_name }}</flux:text>
                    </div>
                    <div class="col-span-1">
                        <flux:heading>Username</flux:heading>
                        <flux:text variant="soft">{{ $detailRegistrantData?->username }}</flux:text>
                    </div>
                    <div class="col-span-1">
                        <flux:heading>Tanggal Daftar</flux:heading>
                        <flux:text variant="soft">{{ \App\Helpers\DateFormatHelper::indoDateTime($detailRegistrantData?->registration_date ) }}</flux:text>
                    </div>
                </div>

                <div class="flex justify-end">
                    <flux:modal.close>
                        <flux:button variant="filled">Tutup</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </form>
    </flux:modal>
</div>