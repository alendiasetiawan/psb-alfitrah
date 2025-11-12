@props([
    'hrefOne' => '#',
    'hrefTwo' => '#',
])

<div class="inline-flex rounded-lg bg-gray-100 p-1">
    <!-- Tab 1 -->
    <a {{ $tabOne->attributes->merge(['href' => $hrefOne ?? '#']) }} wire:navigate>
        <button class="px-4 py-2 text-sm font-medium rounded-md {{ Route::is('owner.management.owner_account') ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-900' }} focus:outline-none">
            {{ $tabOne }}
        </button>
    </a>

    <!-- Tab 2 -->
    <a {{ $tabOne->attributes->merge(['href' => $hrefTwo ?? '#']) }} wire:navigate>
        <button
            class="px-4 py-2 text-sm font-medium rounded-md text-gray-600 {{ Route::is('owner.management.reseller_account') ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-900' }} focus:outline-none">
            {{ $tabTwo }}
        </button>
    </a>
</div>