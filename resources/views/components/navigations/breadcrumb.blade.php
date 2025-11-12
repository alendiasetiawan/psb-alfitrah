@props([
    'firstLink' => "/login"
])

<div class="hidden lg:block">
    <flux:breadcrumbs>
        <flux:heading class="mr-3" size="xl">{{ $title ?? '' }}</flux:heading>
        @isset($activePage)
            <flux:separator vertical class="mr-3"/>
            <flux:breadcrumbs.item href="/login" icon="home" wire:navigate/>
        @endisset

        @isset($firstPage)
            <flux:breadcrumbs.item href="{{ $firstLink }}" wire:navigate>{{ $firstPage }}</flux:breadcrumbs.item>
        @endisset

        @isset($secondPage)
            <flux:breadcrumbs.item href="{{ $secondLink }}" wire:navigate>{{ $secondPage }}</flux:breadcrumbs.item>
        @endisset

        @isset($activePage)
            <flux:breadcrumbs.item>{{ $activePage }}</flux:breadcrumbs.item>            
        @endisset
    </flux:breadcrumbs>
</div>