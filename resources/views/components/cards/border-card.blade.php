@props([
    'borderColor' => '',
    'subTextVariant' => 'subtle',
    'subTextColor' => ''
])

<div>
    <div class="relative bg-white rounded-lg shadow-sm px-4 py-3 border-l-[6px] border-{{ $borderColor }}-600">
        <div class="flex justify-between">
            <div>
                <flux:heading size="xl">{{ $title }}</flux:heading>
                @isset($subTitle)
                    <flux:text variant="{{ $subTextVariant }}" color="{{ $subTextColor }}" class="mb-6">
                        {{ $subTitle }}
                    </flux:text>
                @endisset
            </div>

            @isset($buttonAction)
            <div class="flex justify-center">
                {{ $buttonAction }}
            </div>
            @endisset
        </div>

        <!-- Progress bar -->
        {{ $slot }}
        {{-- <div class="h-2 w-full bg-gray-100 rounded-full mb-2">
            <div class="h-2 bg-indigo-600 rounded-full w-1/4"></div>
        </div> --}}
    
        @isset($actionInformation)
            <div class="flex items-center justify-between">
                <span class="text-{{ $borderColor }}-600 font-semibold text-lg">
                    {{ $textInfo }}
                </span>
                {{ $action }}
            </div>
        @endisset
    </div>
</div>
