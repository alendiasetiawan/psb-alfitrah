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
        <div class="flex items-center px-4 py-3 fixed bg-white top-0 left-0 right-0 gap-2">
            <!-- Tombol Back -->
            @if (is_null($link))
                <a href="#" onclick="history.back(); return false;">
                    <flux:icon icon="arrow-left-circle" variant="mini" class="text-white"/>
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
    @endif
</div>

