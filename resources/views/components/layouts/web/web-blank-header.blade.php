<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-[#0070af] via-[#004996] to-[#241f6e] dark:bg-zinc-800">
        <!--Header-->
        <x-layouts.web.partials.headers.visitor-blank-header />
        <!--#Header-->

        <!--Sidebar-->
        <x-layouts.web.partials.sidebars.visitor-sidebar />
        <!--#Sidebar-->
        
        <flux:main container>
            {{ $slot }}
        </flux:main>

        @fluxScripts
    </body>
</html>