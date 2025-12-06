@props([
    'pagination' => false,
])

<div x-data="swiperContainer({
    grabCursor: true,
    effect: 'creative',
    creativeEffect: {
        prev: {
            shadow: true,
            translate: ['-130%', 0, -500],
        },
        next: {
            shadow: true,
            translate: ['130%', 0, -500],
        },
    },
    slidesPerView: 'auto',
    slidesOffsetAfter: 10,
    slidesOffsetBefore: 10,
})" x-init="init()"
    class="mt-4 relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw]">
    <div class="swiper" x-ref="container">
        <div class="swiper-wrapper">
            {{ $slot }} <!-- Add Style: width: 85vw; -->
        </div>
    </div>

    <div class="swiper-pagination !relative !bottom-0 gap-1 @if (!$pagination) hidden @endif" x-ref="pagination"></div>
</div>
