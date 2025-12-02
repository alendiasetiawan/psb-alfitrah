@props([
    'link' => null,
    'title' => '',
    'historyBack' => true
])

<div>
    <div class="flex items-center px-4 py-3 bg-gradient-to-br from-white/20 via-white/10 to-white/5 backdrop-blur-lg w-full gap-2 shadow-xl border-b border-white/30">
        <!-- Tombol Back -->
        @if (is_null($link))
            <a href="#" onclick="history.back(); return false;">
                <flux:icon icon="arrow-left-circle" variant="mini" class="text-white"/>
            </a>
        @else
            <a href="{{ route($link) }}" wire:navigate>
                <flux:icon icon="arrow-left-circle" variant="mini" class="text-white"/>
            </a>
        @endif
        
        <!-- Judul -->
        <flux:heading size="lg" variant="bold">
            {{ $title }}
        </flux:heading>
    </div>
</div>

