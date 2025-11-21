@props([
   'isViewPort' => false,
   'showTiming' => '50'
])

<div
{{ $attributes->merge([
   'class' => 'opacity-0 -translate-y-8 transition-all duration-[2000ms] ease-[cubic-bezier(0.16,1,0.3,1)]',
   'x-data' => '{
      show: false
   }',
   'x-init' => "setTimeout(() => show = true, ' . $showTiming . ')",
   'x-bind:class' => "show ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-8'"
]) }}>
   {{ $slot }}
</div>