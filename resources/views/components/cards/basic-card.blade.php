@props([
    'isLink' => false,
    'customClass' => 'bg-white rounded-lg px-4 py-3 overflow-hidden'
])

<div {{ $attributes->merge([
    'class' => $customClass . ($isLink ? 'hover:shadow-xl cursor-pointer' : ''),
])}}>
    {{ $slot }}
</div>
