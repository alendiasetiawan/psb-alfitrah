@props([
    'isLink' => false,
    'src' => null
])

<div
{{ $attributes->merge([
    'class' => "bg-white rounded-xl shadow-md overflow-hidden" . ($isLink ? 'hover:shadow-xl cursor-pointer' : ''),
])}}>
    <!-- Header background -->
    <div class="relative flex flex-col justify-between">
        <img src="{{ asset('images/background/background-user.jpg') }}" alt="background"
            class="w-full h-24 object-cover">
        <!-- Avatar -->
        <div class="absolute left-6 -bottom-12">
            <img class="w-24 h-24 rounded-full border-4 border-white object-cover"
                src="{{ !empty($src) ? $src : asset('images/avatar/default-avatar.png') }}" alt="avatar">
        </div>
        @isset($socialLink)
            <!-- Social icons -->
            <div class="absolute right-6 -bottom-6 flex space-x-3">
                <a href="#" class="p-2 rounded-full bg-purple-50 text-blue-400 hover:bg-blue-100">
                    <!-- Twitter -->

                </a>
                <a href="#" class="p-2 rounded-full bg-purple-50 text-pink-500 hover:bg-pink-100">
                    <!-- Instagram -->

                </a>
                <a href="#" class="p-2 rounded-full bg-purple-50 text-blue-600 hover:bg-blue-100">
                    <!-- Facebook -->

                </a>
            </div>
        @endisset
    </div>

    <!--Content-->
    <div class="px-6 pt-12">
        <div class="flex justify-between items-center">
            <!-- Avatar + Nama -->
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $fullName }}</h2>
                    <p class="text-gray-500 text-sm">{{ $position }}</p>
                    {{ $slot }}
                </div>
            </div>

            @isset($counter)
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ $counter }}</p>
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                </div>
            @endisset
        </div>
    </div>

    @isset($actionButton)
        <!-- Footer -->
        <div class="pt-2 pb-4 text-center">
            <!-- Action buttons -->
            <div class="flex justify-center space-x-6 mt-6">
                {{ $actionButton }}
                {{-- <button
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200">
                    👍
                </button>
                <button
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200">
                    ⋯
                </button> --}}
            </div>
        </div>
    @endisset
</div>