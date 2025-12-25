@props([
    'headers' => [],
    'emptyText' => 'No data available.',
])

<div class="bg-white/10 shadow-[inset_2px_3px_5px_rgba(255,255,255,0.7)] backdrop-blur-md rounded-xl overflow-hidden">
    @isset($heading)
        <!-- Table Header -->
        <div class="p-5 border-b border-gray-200">
            <div class="flex md:flex-row md:items-center md:justify-between">
                <flux:heading variant="bold" size="xxl">{{ $heading }}</flux:heading>

                @isset($label)
                    {{ $label }}
                @endisset
            </div>

            @isset($action)
                <!-- Search and Filter -->
                {{-- <div class="flex justify-between items-center mt-4 gap-2">
                    <div class="w-2/6">
                        <flux:input placeholder="Cari nama pendaftar" wire:model.live.debounce.500ms="searchRegistrant"
                            icon="search" />
                    </div>
                    <div class="w-1/6">
                        <flux:select wire:model.live="selectedAcademicYear" placeholder="Tahun Ajaran">
                            <flux:select.option value="">Semua</flux:select.option>
                            <flux:select.option value="1">Aktif</flux:select.option>
                            <flux:select.option value="0">Tidak Aktif</flux:select.option>
                        </flux:select>
                    </div>
                </div> --}}
                {{ $action }}
            @endisset
        </div>
    @endisset

    @isset($content)
        {{ $content }}
    @endisset

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="table-fixed min-w-full divide-y divide-gray-200">
            <thead class="bg-white/50 shadow-[inset_2px_3px_5px_rgba(255,255,255,0.7)] backdrop-blur-md">
                <tr>
                    @foreach ($headers as $header)
                        <th class="px-5 py-3 text-left text-xs font-bold text-dark uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="bg-white/10 backdrop-blur-md divide-y divide-gray-200">
                @if(trim($slot))
                    {{ $slot }}
                @else
                    <x-tables.empty :colspan="count($headers)" :text="$emptyText" />
                @endif
            </tbody>
        </table>

        @isset($pagination)
            <div class="px-5 py-3 bg-white/10 backdrop-blur-md">
                {{ $pagination }}
            </div>
        @endisset
    </div>
</div>
