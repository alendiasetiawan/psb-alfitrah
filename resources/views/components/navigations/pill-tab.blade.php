@props([
    'hrefOne' => '#',
    'hrefTwo' => '#',
    'activeOne' => false,
    'activeTwo' => false,
])

@php
    match (true) {
        Route::is('student.admission_data.biodata') => $activeOne = true,
        Route::is('student.admission_data.admission_attachment') => $activeTwo = true,
        default => null
    };
@endphp

<div class="inline-flex rounded-lg bg-gray-100 p-1">
    <!-- Tab 1 -->
    <a {{ $tabOne->attributes->merge(['href' => $hrefOne ?? '#']) }} wire:navigate>
        <button class="px-4 py-2 text-sm font-medium rounded-md {{ $activeOne ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-900' }} focus:outline-none">
            {{ $tabOne }}
        </button>
    </a>

    <!-- Tab 2 -->
    <a {{ $tabOne->attributes->merge(['href' => $hrefTwo ?? '#']) }} wire:navigate>
        <button
            class="px-4 py-2 text-sm font-medium rounded-md text-gray-600 {{ $activeTwo ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-900' }} focus:outline-none">
            {{ $tabTwo }}
        </button>
    </a>
</div>