<flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <flux:brand href="#" logo="{{ asset('images/logo/alfitrah-logo.png') }}" name="{{ config('app.name') }}" class="max-lg:hidden dark:hidden" />
    <flux:brand href="#" logo="{{ asset('images/logo/alfitrah-logo.png') }}" name="{{ config('app.name') }}" class="max-lg:hidden! hidden dark:flex" />

    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item
        icon="blocks" 
        href="{{ route('branch_quota') }}" 
        :current="Route::is('branch_quota')" 
        wire:navigate>
            Kuota
        </flux:navbar.item>

        <flux:navbar.item 
        icon="contact" 
        href="{{ route('registration_form', [ 'branchId' => Crypt::encrypt(0) ]) }}" 
        :current="Route::is('registration_form')"
        wire:navigate>
            Daftar
        </flux:navbar.item>

        <flux:navbar.item icon="log-in" href="{{ route('login') }}" wire:navigate>Login</flux:navbar.item>
        
        <flux:separator vertical variant="subtle" class="my-2"/>
    </flux:navbar>
</flux:header>