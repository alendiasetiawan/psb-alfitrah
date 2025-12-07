@props([
'isShowBackButton' => false,
'isShowBottomNavbar' => false,
'isShowTitle' => false,
'link' => null,
'isRollOver' => false
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gradient-to-br from-[#0070af] via-[#004996] to-[#241f6e] dark:bg-zinc-800">

    <!--Header-->
    @if ($isShowBackButton)
        <x-layouts.mobile.partials.headers.mobile-header :title="$title" :link="$link" :isRollOver="$isRollOver" />
    @endif
    <!--#Header-->

    <div class="{{ !$isRollOver ? 'px-3 py-3' : '' }}">
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

    @persist('toast')
        <x-notifications.toast />
    @endpersist

    @stack('scripts')
    @fluxScripts
</body>

</html>