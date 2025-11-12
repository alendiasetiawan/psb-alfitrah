@props([
    'activeOne' => false,
    'activeTwo' => false,
    'activeThree' => false,
    'activeFour' => false,
])

@php
    match (true) {
        Route::is('student.student_dashboard') => $activeOne = true,
        Route::is('student.payment.registration_payment') => $activeTwo = true,
        Route::is('student.admission_data.*') => $activeThree = true,
        Route::is('student.placement_test.announcement.*') => $activeFour = true,
        default => null
    };
@endphp

<div class="fixed inset-x-0 bottom-0 z-50 bg-transparent" style="padding-bottom: env(safe-area-inset-bottom, 0);">
    <div class="relative mx-auto w-full h-24">
        <!-- Latar Belakang Navbar dengan SVG -->
        <div class="absolute bottom-0 w-full h-24 pointer-events-none">
            <svg
                width="100%"
                height="100%"
                viewBox="0 0 375 144"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                preserveAspectRatio="none"
                class="filter drop-shadow-[0_-2px_8px_rgba(0,0,0,0.08)]"
            >
            <path
                d="M0 48
                    L127 48
                    C140,50 140,130 182,130
                    C235,140 235,50 245,50
                    L375 48
                    V144
                    H0
                    Z"
                class="fill-white dark:fill-zinc-800"
            />
            </svg>
        </div>

        <!-- Floating Action Button (FAB) -->
        <div class="absolute left-1/2 transform -translate-x-1/2 top-4 z-30">
            <a href="{{ route('student.placement_test.qr_presence_test') }}">
                <button
                    class="w-15 h-15 rounded-full bg-primary-500 flex items-center justify-center text-white shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-300 transition-transform duration-200 hover:scale-105"
                    type="button"
                >
                    <flux:icon icon="qr-code" class="size-7"/>
                </button>
            </a>
        </div>

        <!-- Item Menu -->
        <div class="absolute bottom-0 w-full h-16 flex items-center justify-between z-20">
            <!--First Menu-->
            <a class="flex flex-col items-center justify-center w-1/4 focus:outline-none {{ $activeOne ? 'text-primary-400 dark:text-primary-500' : 'text-zinc-500 dark:text-zinc-400' }}" href="{{ route('student.student_dashboard') }}" wire:navigate>
                <flux:icon icon="home" variant="solid"/>
                @if ($activeOne)
                    <span class="text-xs font-semibold">{{ __('Home') }}</span>
                @endif
            </a>

            <!--Second Menu-->
            <a class="flex flex-col items-center justify-center w-1/4 focus:outline-none {{ $activeTwo ? 'text-primary-400 dark:text-primary-500' : 'text-zinc-500 dark:text-zinc-400' }}" href="{{ route('student.payment.registration_payment') }}" wire:navigate>
                <flux:icon icon="banknotes"/>
                @if ($activeTwo)
                    <span class="text-xs font-semibold">{{ __('Pembayaran') }}</span>
                @endif
            </a>

            <div class="w-1/4"></div>

            <!--Third Menu-->
            <a class="flex flex-col items-center justify-center w-1/4 focus:outline-none {{ $activeThree ? 'text-primary-400 dark:text-primary-500' : 'text-zinc-500 dark:text-zinc-400' }}" href="{{ route('student.admission_data.biodata') }}" wire:navigate>
                <flux:icon icon="contact-round"/>
                @if ($activeThree)
                    <span class="text-xs font-semibold">{{ __('Biodata') }}</span>
                @endif
            </a>

            <!--Fourth Menu-->
            <a class="flex flex-col items-center justify-center w-1/4 focus:outline-none {{ $activeFour ? 'text-primary-400 dark:text-primary-500' : 'text-zinc-500 dark:text-zinc-400' }}" href="{{ route('student.placement_test.announcement.private_announcement') }}" wire:navigate>
                <flux:icon icon="book-open-check"/>
                @if ($activeFour)
                    <span class="text-xs font-semibold">{{ __('Hasil Tes') }}</span>
                @endif
            </a>
        </div>
    </div>
</div>

