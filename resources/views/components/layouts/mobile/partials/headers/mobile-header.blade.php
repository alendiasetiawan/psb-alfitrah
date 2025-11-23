@props([
    'link' => '#',
    'title' => '',
    'historyBack' => true
])

<div>
    <div class="flex items-center px-4 py-3 border-gray-200 bg-white shadow w-full gap-2">
        <!-- Tombol Back -->
        @if ($historyBack)
            <a href="#" onclick="history.back(); return false;">
                <button class="flex items-center text-gray-700 hover:text-gray-900">
                    <flux:icon icon="arrow-left-circle" variant="solid"/>
                </button>
            </a>
        @else
            <a href="{{ route($link) }}" wire:navigate>
                <button class="flex items-center text-gray-700 hover:text-gray-900">
                    <flux:icon icon="arrow-left-circle" variant="solid"/>
                </button>
            </a>
        @endif
        
        <!-- Judul -->
        <flux:heading size="xl" variant="dark-bold">
            {{ $title }}
        </flux:heading>
    </div>
</div>

