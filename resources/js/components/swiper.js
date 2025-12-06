// resources/js/components/swiper.js

import Swiper from "swiper";
import {
  Autoplay,
  EffectCards,
  EffectCoverflow,
  EffectCreative,
  EffectCube,
  EffectFade,
  EffectFlip,
  Mousewheel,
  Navigation,
  Pagination,
} from "swiper/modules";

import "swiper/css";
import "swiper/css/effect-cards";
import "swiper/css/effect-creative";
import "swiper/css/effect-cube";
import "swiper/css/effect-fade";
import "swiper/css/effect-flip";
import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/pagination";

export default function AlpineSwiper(options = {}) {
  return {
    swiper: null,

    init() {
      const paginationEl = this.$refs.pagination;

      // default config (bisa di-override dari HTML)
      const defaultOptions = {
        modules: [
          Autoplay,
          Mousewheel,
          Navigation,
          Pagination,
          EffectCoverflow,
          EffectCards,
          EffectCreative,
          EffectCube,
          EffectFade,
          EffectFlip,
        ],
        // ➜ FIX CLICK
        preventClicks: false,
        preventClicksPropagation: false,
        touchStartPreventDefault: false,
        simulateTouch: true,
        noSwipingSelector: "a, button, .no-swipe",
        allowTouchMove: true,
        watchSlidesProgress: true,
        navigation:
          this.$refs.next && this.$refs.prev
            ? { nextEl: this.$refs.next, prevEl: this.$refs.prev }
            : false,
        pagination: paginationEl
          ? {
              el: paginationEl,
              clickable: true,
              renderBullet: function (index, className) {
                return `<span class="${className}" data-index="${index}"></span>`;
              },
            }
          : false,
        on: {
          // Animate dots during swipe based on progress
          setTranslate: function () {
            if (!paginationEl) return;

            const swiper = this;
            const bullets = paginationEl.querySelectorAll(
              ".swiper-pagination-bullet"
            );
            const progress = swiper.progress;
            const slidesCount = swiper.slides.length;

            // Get colors from CSS variables dynamically
            const computedStyle = getComputedStyle(document.documentElement);
            const activeColorHex =
              computedStyle.getPropertyValue("--color-primary-400").trim() ||
              "#ff921a";
            const inactiveColorHex =
              computedStyle.getPropertyValue("--color-dark-400").trim() ||
              "#888888";

            // Helper function to convert hex to RGB
            const hexToRgb = (hex) => {
              const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(
                hex
              );
              return result
                ? {
                    r: parseInt(result[1], 16),
                    g: parseInt(result[2], 16),
                    b: parseInt(result[3], 16),
                  }
                : { r: 255, g: 146, b: 26 };
            };

            const activeColor = hexToRgb(activeColorHex);
            const inactiveColor = hexToRgb(inactiveColorHex);

            bullets.forEach((bullet, index) => {
              // Calculate distance from current progress position
              const slideProgress = progress * (slidesCount - 1) - index;
              const absProgress = Math.abs(slideProgress);

              // Scale: 1.0 for active, smaller for others
              const scale = Math.max(0.6, 1 - absProgress * 0.3);
              // Opacity: 1.0 for active, lower for others
              const opacity = Math.max(0.4, 1 - absProgress * 0.4);

              // Color interpolation: primary (active) to dark (inactive)
              const colorProgress = Math.max(0, 1 - absProgress);
              const r = Math.round(
                inactiveColor.r +
                  (activeColor.r - inactiveColor.r) * colorProgress
              );
              const g = Math.round(
                inactiveColor.g +
                  (activeColor.g - inactiveColor.g) * colorProgress
              );
              const b = Math.round(
                inactiveColor.b +
                  (activeColor.b - inactiveColor.b) * colorProgress
              );

              bullet.style.transform = `scale(${scale})`;
              bullet.style.opacity = opacity;
              bullet.style.backgroundColor = `rgb(${r}, ${g}, ${b})`;
              bullet.style.transition = "none"; // Disable transition during swipe for smooth animation
            });
          },
          // Reset smooth transition after swipe ends
          transitionEnd: function () {
            if (!paginationEl) return;
            const bullets = paginationEl.querySelectorAll(
              ".swiper-pagination-bullet"
            );
            bullets.forEach((bullet) => {
              bullet.style.transition =
                "transform 0.2s ease, opacity 0.2s ease";
            });
          },
        },
      };

      const finalOptions = { ...defaultOptions, ...options };

      this.swiper = new Swiper(this.$refs.container, finalOptions);
    },
  };
}
