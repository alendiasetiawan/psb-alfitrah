// resources/js/components/swiper.js

import Swiper from "swiper";
import { EffectCoverflow, FreeMode, Mousewheel, Navigation, Pagination } from "swiper/modules";

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
        modules: [FreeMode, Mousewheel, Navigation, Pagination, EffectCoverflow],
        slidesPerView: "auto",
        spaceBetween: 16,
        navigation: {
          nextEl: this.$refs.next,
          prevEl: this.$refs.prev,
        },
        pagination: {
          el: this.$refs.pagination,
          clickable: true,
        },
      };

      const finalOptions = { ...defaultOptions, ...options };

      this.swiper = new Swiper(this.$refs.container, finalOptions);
    },
  };
}
