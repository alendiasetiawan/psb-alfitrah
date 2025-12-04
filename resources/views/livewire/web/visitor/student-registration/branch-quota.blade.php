<div>
    <x-navigations.breadcrumb>
        <x-slot:title>{{ __('Kuota Penerimaan Santri Baru') }} {{ $activeAdmission->name }}</x-slot:title>
    </x-navigations.breadcrumb>

    @if (!$isAdmissionOpen)     
        <!--Alert When Admission Closed-->
        <x-notifications.basic-alert class="mt-4">
            <x-slot:title>Mohon maaf, saat ini pendaftaran sudah tutup. Silahkan kembali lagi nanti, terima kasih ^^</x-slot:title>
        </x-notifications.basic-alert>
        <!--Alert When Admission Closed-->
    @endif   

    <x-animations.fade-down showTiming="50">
        <div class="grid lg:grid-cols-3 md:grid-cols-2 mt-4 gap-3">
            @forelse ($this->branchQuotaLists as $branch)
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
                        @foreach ($branch->educationPrograms as $program)
                            <x-lists.list-group>
                                <x-slot:title>{{ $program->name }}</x-slot:title>
                                <x-slot:subTitle>Kuota Penerimaan : {{ $program->admissionQuotas[0]->amount ?? '0' }} Santri</x-slot:subTitle>
                            </x-lists.list-group>                        
                        @endforeach
                        <!--#Education Program Lists-->
            
                        <!--CTA-->
                        @if ($isAdmissionOpen)                        
                            <a href="{{ route('registration_form', [ 'branchId' => Crypt::encrypt($branch->id) ]) }}" wire:navigate>
                                <flux:button 
                                variant="primary" 
                                class="mt-2" 
                                size="sm" 
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
                                Tutup
                            </flux:button>
                        @endif
                        <!--#CTA-->
                    </x-cards.product-card>
                </div>      
            @empty
            <div class="col-span-1">
                <x-notifications.not-found message="Tidak ada cabang yang bisa ditampilkan" />
            </div>
            @endforelse
        </div>
    </x-animations.fade-down>
</div>
