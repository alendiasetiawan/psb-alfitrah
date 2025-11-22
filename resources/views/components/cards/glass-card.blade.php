{{-- @props([
'isLink' => false,
])

<div {{ $attributes->merge([
   'class' =>
   'relative p-[2px] rounded-xl 
   bg-gradient-to-br from-white/20 via-white/10 to-white/5
   backdrop-blur-xl'
   ]) }}>
   <div class="
      rounded-xl bg-white/10 
      backdrop-blur-2xl backdrop-saturate-200
      shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]
      px-4 py-3
      {{ $isLink ? 'cursor-pointer hover:bg-white/15 hover:shadow-xl' : '' }}
   ">
      {{ $slot }}
   </div>
</div> --}}

@props([
   'isLink' => false,
])

<div {{ $attributes->merge([
   'class' => '
      relative border border-white rounded-xl overflow-hidden

      /* OUTER GLASS CURVE */
      shadow-[0_8px_25px_-5px_rgba(0,0,0,0.25),
              0_4px_15px_rgba(0,0,0,0.12)]

      /* BORDER GRADIENT */
      before:absolute before:inset-0
      before:rounded-xl
      before:p-[1px]
      before:bg-gradient-to-br
      before:from-white/25
      before:via-white/15
      before:to-white/5
      before:pointer-events-none

      backdrop-blur-3xl
      backdrop-saturate-150
   '
]) }}>

   <!-- INNER GLASS SURFACE -->
   <div class="
      relative rounded-[1rem] bg-white/10
      overflow-hidden

      /* REALISTIC EMBOSS (INNER LIGHT + INNER SHADOW) */
      shadow-[inset_0_2px_3px_rgba(255,255,255,0.45),
              inset_0_-3px_8px_rgba(0,0,0,0.22),
              inset_0_0_25px_rgba(255,255,255,0.2)]

      /* SUBTLE GLASS DEPTH */
      backdrop-blur-3xl backdrop-saturate-150

      px-5 py-4
      transition-all duration-300
      {{ $isLink ? 'cursor-pointer hover:bg-white/20 hover:shadow-[inset_0_2px_4px_rgba(255,255,255,0.5),inset_0_-4px_10px_rgba(0,0,0,0.28),0_4px_20px_rgba(255,255,255,0.5)]' : '' }}
   ">

      <!-- LIGHT REFLECTION GLARE -->
      <div class="
         absolute inset-0 pointer-events-none
         bg-gradient-to-br
         from-white/40 via-white/10 to-transparent
         opacity-35 mix-blend-screen
      "></div>

      <!-- INTERNAL LIGHT REFRACTION (WET GLASS FEEL) -->
      <div class="
         absolute inset-0 pointer-events-none
         bg-[radial-gradient(circle_at_20%_30%,rgba(255,255,255,0.45),transparent_70%)]
         opacity-40
      "></div>

      {{ $slot }}
   </div>

</div>