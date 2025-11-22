@props([
    'rounded' => 'rounded-2xl',
    'padding' => 'px-5 py-4',
])

<div {{ $attributes->merge([
    'class' => "relative overflow-hidden $rounded",
]) }}>

    {{-- MASKED GRADIENT BORDER (Tailwind v4) --}}
    <div class="
        pointer-events-none absolute inset-0
        p-[2px] {{ $rounded }}
        bg-gradient-to-br from-white/20 via-white/10 to-white/5
        backdrop-saturate-100
        shadow-[inset_0_2px_2px_rgba(255,255,255,0.7)]
        z-[1]
    "></div>
    
    {{-- GLASS BACKDROP --}}
    <div class="
        absolute inset-0 {{ $rounded }}
        backdrop-blur-xl
        bg-white/10 dark:bg-white/5
        backdrop-saturate-100
        shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]
        z-[0]
    "></div>

    {{-- INNER AURORA TINT (Tailwind v4) --}}
    <div class="
        pointer-events-none absolute inset-0 z-[0]

        [@background:conic-gradient(
            from_180deg_at_50%_50%,
            rgba(255,80,200,0.15),
            rgba(80,150,255,0.12),
            rgba(80,255,180,0.12),
            rgba(255,80,200,0.15)
        )]

        opacity-70
        [mix-blend-mode:overlay]
    "></div>

    {{-- CORNER HIGHLIGHT --}}
    <div class="
        pointer-events-none absolute inset-0 z-[0]
        [@background:radial-gradient(
            circle_at_90%_10%,
            rgba(255,255,255,0.4),
            transparent_60%
        )]
        opacity-75
    "></div>

    {{-- GLASS THICKNESS SHADOW --}}
    <div class="
        pointer-events-none absolute inset-x-0 bottom-0 h-10 z-[0]
        bg-gradient-to-t from-black/20 to-transparent
        blur-xl opacity-50
    "></div>

    {{-- CONTENT SLOT --}}
    <div class="relative z-[10] {{ $padding }}">
        {{ $slot }}
    </div>
</div>
