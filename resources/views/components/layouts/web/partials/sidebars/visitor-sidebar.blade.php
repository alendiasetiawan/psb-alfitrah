<flux:sidebar sticky collapsible="mobile" class="lg:hidden bg-white/10 shadow-[inset_0_2px_2px_rgba(255,255,255,0.7)] backdrop-blur-sm dark:bg-zinc-900 border-b border-white/30 dark:border-zinc-700">
    <flux:sidebar.header>
        <flux:sidebar.brand
            href="#"
            logo="{{ asset('images/logo/alfitrah-logo.png') }}"
            logo:dark="{{ asset('images/logo/alfitrah-logo.png') }}"
            name="{{ config('app.name') }}"
        />

        <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.item 
        icon="blocks" 
        href="{{ route('branch_quota') }}" 
        :current="Route::is('branch_quota')" 
        wire:navigate>
            Kuota
        </flux:sidebar.item>

        <flux:sidebar.item 
        icon="contact" 
        href="{{ route('registration_form', [ 'branchId' => Crypt::encrypt(0) ]) }}" 
        :current="Route::is('registration_form')"
        wire:navigate>
            Daftar
        </flux:sidebar.item>
        
        <flux:sidebar.item icon="log-in" href="{{ route('login') }}" wire:navigate>Login</flux:sidebar.item>
    </flux:sidebar.nav>
</flux:sidebar>