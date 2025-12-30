@props([
    'top' => 'top-0',
    'bg' => 'bg-white',
    'shadow' => 'shadow-[0_3px_12px_0px_rgba(0,0,0,0.7)]',
    'link' => null,
    'title' => '',
])

<div class="flex flex-col items-start px-4 py-2 fixed left-0 right-0 gap-2 z-50 {{ $top }} {{ $bg }} {{ $shadow }}">
    <div class="flex items-center gap-2 justify-start">
    <!-- Tombol Back -->
    @if (is_null($link))
        <a href="#" onclick="history.back(); return false;">
            <flux:icon icon="arrow-left-circle" variant="mini" class="text-dark"/>
        </a>
    @else
        <a href="{{ route($link) }}" wire:navigate>
            <flux:icon icon="arrow-left-circle" variant="mini" class="text-dark"/>
        </a>
    @endif
    
    <!-- Judul -->
    <flux:heading size="lg" variant="dark-bold">
        {{ $title }}
    </flux:heading>
    </div>

    <div class="w-full">
        {{ $slot }}
    </div>
</div>

{{-- <div class="fixed {{ $bg }} {{ $top }} left-0 right-0 z-50 px-4 py-3 border-transparent border-0 shadow-[0_6px_12px_-6px_rgba(0,0,0,0.7)]">
    {{ $slot }}
</div> --}}

<!-- Spacer to prevent content from hiding under fixed header -->
{{-- <div class="h-32"></div> --}}
