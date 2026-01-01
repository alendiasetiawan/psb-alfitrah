<div>
    <x-auth-header :title="__('Reset Password')" :description="__('Buat password baru untuk akun anda')" />

    @if ($isTokenValid)        
        <form x-data="formValidation({
            password: ['required', 'minLength:6'],
            confirmPassword: ['required', 'minLength:6']
            })" 
            wire:submit="setNewPassword" class="grid grid-cols-1 space-y-6 mt-6">
            <div class="col-span-1">
                <!-- Password -->
                <flux:input wire:model="inputs.password" :isValidate="true" fieldName="password" :label="__('Password')"
                    type="password" :placeholder="__('Minimal 6 karakter')" viewable />
            </div>

            <div class="col-span-1">
                <!-- Confirm Password -->
                <flux:input wire:model="inputs.confirmPassword" :isValidate="true" fieldName="confirmPassword"
                    :label="__('Konfirmasi Password')" type="password" :placeholder="__('Tulis ulang password')" viewable />
            </div>

            @if (session('error-reset-password'))
                <div class="col-span-1">
                    <!--SECTION: Alert When Failed To Reset-->
                    <x-notifications.basic-alert variant="danger">
                        <x-slot:title>{{ session('error-reset-password') }}</x-slot:title>
                    </x-notifications.basic-alert>
                    <!-- #Alert When Failed To Reset-->
                </div>
            @endif


            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" x-bind:disabled="!isSubmitActive"
                    :loading="false">
                    <x-items.loading-indicator wireTarget="setNewPassword">
                        <x-slot:buttonName>Simpan Password</x-slot:buttonName>
                        <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                    </x-items.loading-indicator>
                </flux:button>
            </div>
        </form>
    @else
    <div class="grid grid-cols-1 mt-6 space-y-6">
        <x-notifications.basic-alert variant="warning">
            <x-slot:title>
                Link expired, silahkan coba lagi!
            </x-slot:title>
        </x-notifications.basic-alert>

        <flux:button variant="primary" href="{{ route('password.request') }}" wire:navigate>
            Request Reset Password
        </flux:button>
    </div>
    @endif

    @push('scripts')
        <script type="text/javascript">
            function preventBack() {
                window.history.forward();
            }

            setTimeout("preventBack()", 0);

            window.onunload = function () { null };
        </script>
    @endpush
</div>
