<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Proses Bayar Pendaftaran') }}</x-slot:title>
        <x-slot:activePage>{{ __('Verifikasi Proses Bayar Pendaftaran') }}</x-slot:activePage>
    </x-navigations.breadcrumb>

    <!--ANCHOR: SEARCH AND FILTER-->
        <div class="grid grid-cols-2 mt-4 justify-between items-center gap-2">
            <div class="flex gap-2">
                <div class="w-4/6">
                    <flux:input
                        icon="search"
                        placeholder="Cari nama santri"
                        wire:model.live.debounce.500ms="searchStudent"
                    />
                </div>
            </div>

            <div class="flex justify-end">
                <flux:badge variant="solid" color="primary" icon="user">
                    Jumlah: {{ $this->totalProcessStudent }}
                </flux:badge>
            </div>
        </div>
    <!--#SEARCH AND FILTER-->

    <!--ANCHOR: STUDENT CARD-->
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
    <!--#STUDENT CARD-->
    <x-modals.registration-payment-verification />
</div>
