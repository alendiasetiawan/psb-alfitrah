<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
   @include('partials.head')
</head>

<body class="min-h-screen bg-gradient-to-br from-[#0070af] via-[#004996] to-[#241f6e] dark:bg-zinc-800">
   <!--Sidebar-->
   @if (session('userData')->role_id == \App\Enums\RoleEnum::ADMIN)
   <x-layouts.web.partials.sidebars.admin-sidebar />
   @else
   <x-layouts.web.partials.sidebars.student-sidebar />
   @endif
   <!--#Sidebar-->

   <flux:main>
      {{ $slot }}
   </flux:main>

   @persist('toast')
   <x-notifications.toast />
   @endpersist

   @fluxScripts

   <!--Script Date Picker-->
   <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
   <!--#Script Date Picker-->
   <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>
</body>

</html>