@props([
    'scrollHeight' => 50
])

<div 
    x-data="{ scrolled: false }" 
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > {{ $scrollHeight }} })"
    :class="scrolled ? 'bg-white/10 shadow-[inset_0px_-3px_10px_rgba(255,255,255,0.7)] backdrop-blur-sm' : ''"
    class="sticky top-0 z-100 pb-4 -mx-4 px-4 pt-4 -mt-4 transition-all duration-250 ease-in-out">
        {{ $slot }}
</div>