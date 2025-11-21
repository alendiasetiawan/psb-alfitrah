@props([
'isShowBackButton' => false,
'isShowBottomNavbar' => false,
'isShowTitle' => false,
'link' => '#'
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
   @include('partials.head')
</head>

<body class="min-h-screen bg-gradient-to-b from-white via-primary-100 to-primary-300 dark:bg-zinc-800 mb-6">

   <!--Header-->
   @if ($isShowBackButton)
   <x-layouts.mobile.partials.headers.mobile-header :title="$title" :link="$link" />
   @endif
   <!--#Header-->

   <div class="px-4 py-4">
      @if (!$isShowBackButton && $isShowTitle)
      <!--Header Title When Back Button is Off-->
      <div class="mb-4">
         <flux:heading size="xxl">{{ $title }}</flux:heading>
      </div>
      <!--#Header Title When Back Button is Off-->
      @endif
      {{ $slot }}
   </div>

   <!--Bottom Navbar-->
   @if ($isShowBottomNavbar)
   @if (session('userData')->role_id == \App\Enums\RoleEnum::ADMIN)
   <x-layouts.mobile.partials.bottom-navbars.admin-bottom-navbar />
   @else
   <x-layouts.mobile.partials.bottom-navbars.student-bottom-navbar />
   @endif
   @endif
   <!--#Bottom Navbar-->

   <x-notifications.toast />

   @fluxScripts

   <!--Animate Element When Insert Into Viewport-->
   <script>
      document.addEventListener('alpine:init', () => {
      
          Alpine.directive('intersect', (el, { modifiers, expression }, { evaluateLater, cleanup }) => {
              let evaluate = evaluateLater(expression)
      
              let observer = new IntersectionObserver(entries => {
                  entries.forEach(entry => {
                      if (entry.isIntersecting) {
                          evaluate()
                          if (!modifiers.includes('multiple')) {
                              observer.unobserve(el)
                          }
                      }
                  })
              })
      
              observer.observe(el)
      
              cleanup(() => observer.disconnect())
          })
      
      })
   </script>
   <!--#Animate Element When Insert Into Viewport-->

</body>

</html>