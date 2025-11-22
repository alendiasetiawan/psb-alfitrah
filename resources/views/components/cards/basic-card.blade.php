@props([
    'isLink' => false,
])

<div class="p-[1px] rounded-lg bg-gradient-to-r from-pink-300 via-purple-300 to-blue-300">
   <div {{ $attributes->merge([
      'class' => 'rounded-lg px-4 py-3 bg-white/20 backdrop-blur-sm backdrop-filter ' . ($isLink ? 'hover:shadow-xl cursor-pointer' : ''),
   ])}}>
      {{ $slot }}
   </div>
</div>
