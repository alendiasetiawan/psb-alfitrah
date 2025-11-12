@props([
    'avatarInitial' => "",
    'avatarImage' => "",
    'isLink' => false,
])

<x-cards.basic-card :isLink="$isLink" {{ $attributes->merge() }}>
    <!-- Header -->
    <div class="flex items-start justify-between">
        <!-- Avatar -->
        <div class="flex items-center">
            <flux:avatar :initials="$avatarInitial ? $avatarInitial : null" :src="$avatarImage ? $avatarImage : null"></flux:avatar>
        </div>

        <!-- Menu Button -->
        @isset($actionMenu)
        <a x-on:click.stop>
            <flux:dropdown offset="-5" gap="1">
                <flux:button variant="ghost" size="xs">
                    <flux:icon.ellipsis-vertical variant="micro" />
                </flux:button>
                <flux:menu>
                    @isset($menuItem)
                        {{ $menuItem }}
                        {{-- <flux:menu.item icon="file-pen-line">Edit</flux:menu.item>
                        <flux:menu.item icon="trash">Hapus</flux:menu.item> --}}
                    @endisset
                </flux:menu>
            </flux:dropdown>
        </a>
        @endisset
    </div>

    <!--Content-->
    <div class="mt-2">
        <h1 class="text-xl font-medium text-gray-900 truncate max-w-[150px]">
            {{ $title }}
        </h1>
        <p class="text-xs text-gray-500">{{ $subTitle }}</p>
    </div>

    <!-- Badge -->
    @isset($label)
        <div class="mt-3 flex items-center justify-between">
            {{ $label }}
            {{-- <flux:badge color="sky" icon="user">Diki</flux:badge>
            <flux:badge color="sky" icon="package-search">150</flux:badge> --}}
        </div>
    @endisset
</x-cards.basic-card>
