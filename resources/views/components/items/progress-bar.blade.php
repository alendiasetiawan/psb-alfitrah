@props([
    'progress' => 0
])
<div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-3 overflow-hidden">
    <div
        class="h-3 rounded-full bg-gradient-to-r from-white via-amber-500 to-primary-500 transition-all duration-500 ease-out"
        :style="`width: ${progress}%`">
    </div>
</div>
