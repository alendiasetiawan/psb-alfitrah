@props([
    'borderColor' => 'border-gray-300/70',
])

<nav class="relative flex gap-15 justify-center items-center overflow-x-auto -mx-4 border-b {{ $borderColor }} z-0">
    {{ $slot }} 
    <!--Isi dengan flat-tab-item -->
</nav>