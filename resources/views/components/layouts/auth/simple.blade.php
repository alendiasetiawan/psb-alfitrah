<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-[#0070af] via-[#004996] to-[#241f6e] dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <x-animations.fade-down showTiming="50">
                <div class="flex w-full max-w-sm flex-col gap-2 bg-white/10 shadow-[inset_2px_3px_5px_rgba(255,255,255,0.7)] backdrop-blur-lg rounded-2xl p-5">
                    <div class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                        <span class="flex h-12 w-12 mb-1 items-center justify-center rounded-md">
                            <img src="{{ asset('images/logo/alfitrah-logo.png') }}" alt="logo" />
                            {{-- <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" /> --}}
                        </span>
                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </div>

                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            </x-animations.fade-down>

        </div>
        @fluxScripts
    </body>
</html>
