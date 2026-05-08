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

    {{-- <a href="{{ asset('storage/' . $detailAttachment->studentAttachment->photo) }}"
        data-fancybox="valid-attachment" data-caption="Photo Siswa">
        <img src="{{ asset('storage/' . $detailAttachment->studentAttachment->photo) }}"
            width="250" height="auto" />
    </a> --}}
</div>