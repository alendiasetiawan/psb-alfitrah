@props([
'showTiming' => '50' //Default for viewport delay is 1000ms
])

<div {{ $attributes->merge([
    'class' => 'opacity-0 -translate-y-10 transition-all duration-[2000ms] ease-[cubic-bezier(0.16,1,0.3,1)]',
    'x-data' => '{
    show: false
    }',
    'x-bind:class' => "show ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-10'",
    'x-intersect' => "setTimeout(() => show = true, $showTiming)"
    ]) }}>
    {{ $slot }}
</div>