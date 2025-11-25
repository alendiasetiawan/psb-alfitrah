// resources/js/components/swiper.js

import Swiper from "swiper";
import { EffectCards, EffectCoverflow, FreeMode, Mousewheel, Navigation, Pagination } from "swiper/modules";

import "swiper/css";
import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/pagination";

export default function AlpineSwiper(options = {}) {
    return {
        swiper: null,

        init() {
         // default config (bisa di-override dari HTML)
            const defaultOptions = {
                modules: [FreeMode, Mousewheel, Navigation, Pagination, EffectCoverflow, EffectCards],
                slidesPerView: "auto",
                // ➜ FIX CLICK
                preventClicks: false,
                preventClicksPropagation: false,
                touchStartPreventDefault: false,
                simulateTouch: true,
                noSwipingSelector: 'a, button, .no-swipe',
                allowTouchMove: true,
                navigation: this.$refs.next && this.$refs.prev
                    ? { nextEl: this.$refs.next, prevEl: this.$refs.prev }
                    : false,
                pagination: this.$refs.pagination
                    ? { el: this.$refs.pagination, clickable: true }
                    : false,
            };

            const finalOptions = { ...defaultOptions, ...options };

            this.swiper = new Swiper(this.$refs.container, finalOptions);
        },
    };
}
