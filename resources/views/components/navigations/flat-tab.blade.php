@props([
    'borderColor' => 'border-gray-300/70',
])

<nav class="relative flex gap-20 justify-center items-center overflow-x-auto -mx-3 border-b {{ $borderColor }}">
    {{ $slot }} 
    <!--Isi dengan flat-tab-item -->
</nav>