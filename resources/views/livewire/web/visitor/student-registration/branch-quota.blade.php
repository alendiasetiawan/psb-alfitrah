<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Kuota Penerimaan Santri Baru') }} {{ $activeAdmission?->name }}</x-slot:title>
    </x-navigations.breadcrumb>

    @if ($activeAdmission === null)
        <x-notifications.basic-alert variant="warning" icon="triangle-alert" class="mt-4">
            <x-slot:title>Informasi penerimaan santri baru belum tersedia.</x-slot:title>
            <x-slot:subTitle>Silakan kembali lagi setelah informasi pendaftaran tersedia.</x-slot:subTitle>
        </x-notifications.basic-alert>
    @elseif (!$isAdmissionOpen)
        <!--Alert When Admission Closed-->
        <x-notifications.basic-alert class="mt-4">
            <x-slot:title>Mohon maaf, saat ini pendaftaran sudah tutup. Silahkan kembali lagi nanti, terima kasih ^^</x-slot:title>
        </x-notifications.basic-alert>
        <!--Alert When Admission Closed-->
    @endif   

    @if ($activeAdmission !== null)
    <x-animations.fade-down showTiming="50">
        <div class="grid lg:grid-cols-3 md:grid-cols-2 mt-4 gap-3">
            @forelse ($this->branchQuotaLists as $branch)
                @php
                    $isBranchReady = $branch->educationPrograms->isNotEmpty()
                        && $branch->educationPrograms->every(fn ($program) => $program->admissionQuotas->isNotEmpty());
                @endphp
                <div class="col-span-1" wire:key='branch-{{ $branch->id }}'>
                    <x-cards.product-card src="{{ $branch->photo ? asset('storage/'.$branch->photo) : null }}">
                        @if (!is_null($branch->map_link))
                            <x-slot:category>
                                <a href="{{ $branch->map_link }}" target="_blank">
                                    <div class="flex flex-between items-center">
                                        Lihat Map
                                        <flux:icon.map-pin variant="micro"/>
                                    </div>
                                </a>
                            </x-slot:category>
                        @endif
                        <x-slot:productTitle>{{ $branch->branch_name }}</x-slot:productTitle>
                        <x-slot:productDescription>
                            {{ Str::limit($branch->address, 100, '...') }}
                            </br>
                            HP: {{ $branch->mobile_phone }}
                        </x-slot:productDescription>
            
                        <!--Education Program Lists-->
                        @forelse ($branch->educationPrograms as $program)
                            <x-lists.list-group>
                                <x-slot:title>{{ $program->name }}</x-slot:title>
                                <x-slot:subTitle>
                                    @if ($program->admissionQuotas->isEmpty())
                                        Kuota penerimaan belum tersedia.
                                    @else
                                        Kuota Penerimaan : {{ $program->admissionQuotas->first()->amount }} Santri
                                    @endif
                                </x-slot:subTitle>
                            </x-lists.list-group>
                        @empty
                            <flux:text variant="soft" class="mt-2">Jenjang pendidikan belum tersedia.</flux:text>
                        @endforelse
                        <!--#Education Program Lists-->

                        @if (!$isBranchReady)
                            <x-notifications.basic-alert variant="warning" icon="triangle-alert" class="mt-2">
                                <x-slot:title>Pendaftaran pondok ini belum tersedia.</x-slot:title>
                                <x-slot:subTitle>Silakan kembali lagi setelah informasi jenjang pendidikan dan kuota tersedia.</x-slot:subTitle>
                            </x-notifications.basic-alert>
                        @endif
            
                        <!--CTA-->
                        @if ($isAdmissionOpen && $isBranchReady)
                            <a href="{{ route('registration_form', [ 'branchId' => Crypt::encrypt($branch->id) ]) }}" wire:navigate>
                                <flux:button 
                                variant="primary" 
                                icon="file-pen-line"
                                class="mt-2" 
                                :loading="false">
                                    Isi Formulir
                                </flux:button>
                            </a>
                        @else
                            <flux:button
                            variant="filled"
                            class="mt-2"
                            size="sm"
                            :disabled
                            >
                                {{ $isAdmissionOpen ? 'Belum Tersedia' : 'Tutup' }}
                            </flux:button>
                        @endif
                        <!--#CTA-->
                    </x-cards.product-card>
                </div>      
            @empty
            <div class="col-span-full">
                <x-notifications.basic-alert variant="warning" icon="triangle-alert">
                    <x-slot:title>Informasi pondok belum tersedia.</x-slot:title>
                    <x-slot:subTitle>Silakan kembali lagi setelah informasi pondok, jenjang pendidikan, dan kuota tersedia.</x-slot:subTitle>
                </x-notifications.basic-alert>
            </div>
            @endforelse
        </div>
    </x-animations.fade-down>
    @endif
</div>
