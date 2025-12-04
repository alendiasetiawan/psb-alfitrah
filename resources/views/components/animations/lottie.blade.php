@props([
    'src' => null,
    'width' => '300',
    'height' => '300',
    'loop' => true,
    'autoplay' => true,
])

<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    <div 
        x-data="lottieAnimation" 
        x-init="init('{{ asset($src) }}', {{ $loop ? 'true' : 'false' }}, {{ $autoplay ? 'true' : 'false' }})"
        style="width: {{ $width }}px; height: {{ $height }}px;"
    ></div>
</div>

@once
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('lottieAnimation', () => ({
                animation: null,
                init(src, loop, autoplay) {
                    this.animation = lottie.loadAnimation({
                        container: this.$el,
                        renderer: 'svg',
                        loop: loop,
                        autoplay: autoplay,
                        path: src
                    });
                },
                destroy() {
                    if (this.animation) {
                        this.animation.destroy();
                    }
                }
            }));
        });
    </script>
    @endpush
@endonce
