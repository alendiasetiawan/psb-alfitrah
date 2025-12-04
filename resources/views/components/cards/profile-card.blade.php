@props([
    'avatarInitial' => "",
    'avatarImage' => "",
    'isLink' => false,
])

<x-cards.soft-glass-card :isLink="$isLink" {{ $attributes->merge() }}>
    <!-- Header -->
    <div class="flex items-start justify-between">
        <!-- Avatar -->
        <div class="flex items-center">
            <flux:avatar 
            :initials="$avatarInitial ? $avatarInitial : null" 
            :src="$avatarImage ? $avatarImage : null"
            />
        </div>

        <!-- Menu Button -->
        @isset($actionMenu)
        <a x-on:click.stop>
            <flux:dropdown offset="-5" gap="1">
                <flux:button variant="ghost" size="xs">
                    <flux:icon.ellipsis-vertical variant="micro" class="text-white"/>
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
        <flux:heading size="xl" variant="bold" class="truncate max-w-[150px]">
            {{ $title }}
        </flux:heading>
        <flux:text variant="soft" size="sm">
            {{ $subTitle }}
        </flux:text>
    </div>

    <!-- Badge -->
    @isset($label)
        <div class="mt-3 flex items-center justify-between">
            {{ $label }}
            {{-- <flux:badge color="sky" icon="user">Diki</flux:badge>
            <flux:badge color="sky" icon="package-search">150</flux:badge> --}}
        </div>
    @endisset
</x-cards.soft-glass-card>
