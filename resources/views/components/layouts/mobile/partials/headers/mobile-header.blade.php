@props([
    'link' => null,
    'title' => '',
    'historyBack' => true,
    'isRollOver' => false
])

<div>
    @if ($isRollOver)
        <div class="p-3 inset-0 top-0 right-0 left-0 fixed">
            <!-- Tombol Back -->
            @if (is_null($link))
                <a href="#" onclick="history.back(); return false;">
                    <flux:icon icon="arrow-left-circle" class="text-white"/>
                </a>
            @else
                <a href="{{ route($link) }}" wire:navigate>
                    <flux:icon icon="arrow-left-circle" class="text-white"/>
                </a>
            @endif
        </div>
    @else
        <div class="flex items-center px-4 py-2 bg-gradient-to-br from-white/20 via-white/10 to-white/5 backdrop-blur-lg w-full gap-2 shadow-xl border-b border-white/30">
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
    @endif
</div>

