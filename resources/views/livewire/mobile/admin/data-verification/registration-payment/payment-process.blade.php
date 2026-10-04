<div>
    <!--ANCHOR - Sticky Search and Filter Section -->
    <div class="my-3">
        <x-navigations.flat-tab>
            <x-navigations.flat-tab-item 
                href="admin.data_verification.registration_payment.payment_unpaid" 
                label="Belum" 
            />
            <x-navigations.flat-tab-item 
                href="admin.data_verification.registration_payment.payment_process" 
                label="Proses" 
                :isActive="true" 
                activeTextColor="text-white"/>
            <x-navigations.flat-tab-item 
                href="admin.data_verification.registration_payment.payment_paid" 
                label="Sudah"/>
        </x-navigations.flat-tab>
    </div>

    <x-animations.fade-down showTiming="50">    
        <div class="grid grid-cols-1">
            <flux:input placeholder="Cari nama siswa" wire:model.live.debounce.500ms="searchStudent" icon="search" />
        </div>

        <div class="flex justify-between mt-2">
            <flux:badge variant="solid" color="primary" icon="user-check">Jumlah : {{ $this->totalProcessStudent }}</flux:badge>
        </div>
    </x-animations.fade-down>

    <!--ANCHOR: STUDENT CARD-->
    <x-animations.fade-down showTiming="150">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            @forelse ($this->processStudentLists as $student)
                <div class="col-span-1" wire:key="student-{{ $student->id }}">
                    <x-cards.registration-payment-process :student="$student" />
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3">
                    <x-animations.not-found />
                </div>
            @endforelse
        </div>

        <div class="grid grid-cols-1 mt-3">
            <!--NOTE: Load More Button-->
            @if ($this->processStudentLists->hasMorePages())
                <livewire:components.buttons.load-more loadItem="18" />
            @endif
            <!--#Load More Button-->
        </div>
    </x-animations.fade-down>
    <!--#STUDENT CARD-->

    <x-modals.registration-payment-verification />
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>
@endpush
