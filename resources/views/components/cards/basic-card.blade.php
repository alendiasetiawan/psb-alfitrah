@props([
    'isLink' => false,
])

<div {{ $attributes->merge([
    'class' => "bg-white rounded-lg shadow-md p-5 flex flex-col relative transition-shadow ease-in-out duration-300 " . ($isLink ? 'hover:shadow-xl cursor-pointer' : ''),
])}}>
    {{ $slot }}
</div>
