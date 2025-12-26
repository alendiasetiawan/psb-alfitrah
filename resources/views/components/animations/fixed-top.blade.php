@props([
    'top' => 'top-10',
    'bg' => 'bg-white',
    'shadow' => 'shadow-[0_6px_12px_-6px_rgba(0,0,0,0.7)]',
])

<div class="fixed {{ $bg }} {{ $top }} left-0 right-0 z-50 px-4 py-3 {{ $shadow }}">
    {{ $slot }}
</div>

<!-- Spacer to prevent content from hiding under fixed header -->
{{-- <div class="h-[15vh]"></div> --}}