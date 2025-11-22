@props([
    'rounded' => 'rounded-3xl',
    'padding' => 'p-6',
])

<div {{ $attributes->merge([
    'class' => "relative overflow-hidden $rounded",
]) }}>

    {{-- 1. LIQUID GEL BORDER (soft meniscus edge) --}}
    <div class="
        pointer-events-none absolute inset-0 z-[4]
        p-[3px] {{ $rounded }}
        [@background:linear-gradient(
            135deg,
            rgba(255,255,255,0.6),
            rgba(255,255,255,0.25),
            rgba(255,255,255,0.18),
            rgba(255,255,255,0.5)
        )]
        [mask:linear-gradient(#fff_0_0)]
        blur-[2px] opacity-95
    "></div>

    {{-- 2. GEL BLUR BACKGROUND --}}
    <div class="
        absolute inset-0 {{ $rounded }}
        backdrop-blur-[28px]
        bg-white/12 dark:bg-white/6
        backdrop-saturate-150
        shadow-[inset_0_0_30px_rgba(255,255,255,0.22)]
        z-[0]
    "></div>

    {{-- 3. LIQUID INTERNAL REFRACTION (curved gel highlight) --}}
    <div class="
        pointer-events-none absolute inset-0 z-[1]

        [@background:conic-gradient(
            from_160deg_at_50%_50%,
            rgba(255,255,255,0.33),
            rgba(255,255,255,0.05),
            rgba(255,255,255,0.22),
            rgba(255,255,255,0.33)
        )]

        blur-xl opacity-55
    "></div>

    {{-- 4. LIQUID AURORA (internal viscosity glow) --}}
    <div class="
        pointer-events-none absolute inset-0 z-[1]

        [@background:radial-gradient(
            circle_at_25%_20%,
            rgba(255,255,255,0.45),
            transparent_55%
        )]

        opacity-35 blur-[45px]
        animate-[gelFloat_7s_ease-in-out_infinite]
        mix-blend-screen
    "></div>

    {{-- 5. **SPECULAR MICRO-SHINE LAYER** (iOS 18 highlight) --}}
    <div class="
        pointer-events-none absolute inset-0 z-[3]

        [@background:repeating-linear-gradient(
            120deg,
            rgba(255,255,255,0.18) 0px,
            rgba(255,255,255,0.18) 1px,
            transparent 2px,
            transparent 6px
        )]

        opacity-30 blur-[1px]
        animate-[microShine_9s_ease-in-out_infinite]
        mix-blend-soft-light
    "></div>

    {{-- 6. MICRO SPECULAR HOTSPOT (tiny light grain) --}}
    <div class="
        pointer-events-none absolute inset-0 z-[3]

        [@background:radial-gradient(
            circle_at_70%_30%,
            rgba(255,255,255,0.4),
            transparent 60%
        )]

        blur-3xl opacity-20
        animate-[sparkleShift_12s_ease-in-out_infinite]
        mix-blend-screen
    "></div>

    {{-- 7. DEPTH SHADOW (gel thickness) --}}
    <div class="
        pointer-events-none absolute inset-x-0 bottom-0 h-16 z-[1]
        bg-gradient-to-t from-black/40 to-transparent
        blur-[35px] opacity-70
    "></div>

    {{-- 8. CONTENT --}}
    <div class="relative z-[10] {{ $padding }}">
        {{ $slot }}
    </div>
</div>

{{-- KEYFRAMES --}}
<style>
@keyframes gelFloat {
  0%   { transform: translate(0,0) scale(1); opacity:.35; }
  50%  { transform: translate(18px,-12px) scale(1.03); opacity:.55; }
  100% { transform: translate(0,0) scale(1); opacity:.35; }
}

@keyframes microShine {
  0%   { transform: translateX(-40%); opacity:.20; }
  50%  { transform: translateX(40%);  opacity:.35; }
  100% { transform: translateX(-40%); opacity:.20; }
}

@keyframes sparkleShift {
  0%,100% { transform: translate(0,0); opacity:.18; }
  50%     { transform: translate(-10px,8px); opacity:.28; }
}
</style>
