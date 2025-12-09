<div 
{{ $attributes->merge([
    'class' => ''
]) }}
x-init="let lastScrollTop = 0;
$nextTick(() => {
        Fancybox.bind('[data-fancybox]', {
        on: {
            init: () => {
                lastScrollTop = window.scrollY;
            },
            destroy: () => {
                setTimeout(() => {
                    window.scrollTo({
                        top: lastScrollTop,
                        behavior: 'instant'
                    });
                }, 0);
            }
        }
    });
});">
    {{ $slot }}
</div>