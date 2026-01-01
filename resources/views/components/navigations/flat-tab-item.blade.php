@props([
    'label' => '',
    'isActive' => false,
    'href' => null,
    'activeTextColor' => 'text-dark',
    'inactiveTextColor' => 'text-gray-400 hover:border-gray-600 hover:border-b-3'
])

<button class="relative pb-1 text-base font-medium {{ $isActive ? 'border-primary ' . $activeTextColor . ' border-b-3' : $inactiveTextColor }}">
    @if ($href)
        <a href="{{ route($href) }}" wire:navigate>
            {{ $label }}
        </a>
    @else
        {{ $label }}
    @endif
</button>