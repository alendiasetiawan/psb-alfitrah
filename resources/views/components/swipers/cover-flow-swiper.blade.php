@props([
    'pagination' => false,
    'gap' => 16,
    'effect' => '', // cards
])

<div {{ $attributes->merge(['class' => 'px-3 mt-3 swiper']) }}
    x-init="$nextTick(() => $el._x_swiper = new Swiper($el, {
        effect: '{{ $effect }}',
        slidesPerView: 'auto',
        spaceBetween: {{ $gap }},
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        }
    }))"
>

    <div class="swiper-wrapper">
        {{ $slot }}
    </div>


    <div class="static mt-1 swiper-pagination @if(!$pagination) hidden @endif" ></div>

</div>
