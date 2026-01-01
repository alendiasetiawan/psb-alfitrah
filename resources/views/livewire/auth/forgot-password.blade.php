 <div>
    @if (session('success-send-otp'))
        <!--ANCHOR: INPUT OTP-->
        <x-auth-header :title="__('Verifikasi Reset Password')" :description="__('Masukan kode OTP yang kami kirimkan ke nomor Whatsapp anda')" />
        
        <form class="space-y-6 mt-6" 
            wire:submit="verifyOtp"
            x-data="{
                ...countDownTimer({
                    countdown: 30
                })
            }
            " 
            x-effect="startTimer()">
            <div class="text-center">
                <flux:otp wire:model="otpCode" length="6" class="mx-auto"/>
            </div>

            <div class="flex flex-col gap-2">
                <flux:button variant="primary" type="submit" class="w-full">
                    <x-items.loading-indicator wireTarget="verifyOtp">
                        <x-slot:buttonName>Verifikasi OTP</x-slot:buttonName>
                        <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                    </x-items.loading-indicator>
                </flux:button>

                <flux:button type="button" variant="filled" class="w-full" x-on:click="resetTimer()"
                    wire:click='resendOtp' x-bind:disabled="countdown > 0 ? true : false">
                    <span x-show="countdown > 0" class="text-gray-500">
                        Kirim Ulang OTP (<span x-text="countdown"></span>)
                    </span>
                    <span x-show="countdown === 0" class="text-amber-500">
                        Kirim Ulang OTP
                    </span>
                </flux:button>
            </div>
        </form>
        <!--#INPUT OTP-->
    @else
        <!--ANCHOR: CHECK USERNAME-->
        <x-auth-header :title="__('Lupa Password')" :description="__('Masukan nomor Whatsapp untuk mendapatkan kode OTP')" />

        <form  
            x-data="
            formValidation({
                username: ['required', 'numeric', 'minLength:8', 'maxLength:15']
            })"
            wire:submit="sendOtpReset" 
            class="flex flex-col">

            <!-- Email Address -->
            <flux:input
                wire:model="inputs.username"
                label="Nomor Whatsapp"
                inputmode="numeric"
                :isNumberMode="true"
                :isValidate="true"
                fieldName="username"
                autofocus
                placeholder="08577823xxx"
            />

            @if (session('error-user-not-found'))            
                <!--SECTION: ALERT WHEN USER NOT FOUND-->
                <div class="flex items-center mt-4 gap-2">
                    <flux:icon.triangle-alert class="text-amber-500" variant="mini"/>
                    <flux:text class="text-amber-500">
                        {{ session('error-user-not-found') }}
                    </flux:text>
                </div>
                <!--#ALERT WHEN USER NOT FOUND-->
            @endif

            @if (session('error-send-otp'))
            <!--SECTION: ALERT FAILED SEND OTP-->
                <div class="flex items-center mt-4 gap-2">
                    <flux:icon.triangle-alert class="text-amber-500" variant="mini"/>
                    <flux:text class="text-amber-500">
                        {{ session('error-send-otp') }}
                    </flux:text>
                </div>
            <!--#ALERT FAILED SEND OTP-->
            @endif
                
            <flux:button variant="primary" type="submit" class="w-full mt-4" x-bind:disabled="!isSubmitActive" :loading="false">
                <x-items.loading-indicator wireTarget="sendOtpReset">
                    <x-slot:buttonName>Kirim OTP</x-slot:buttonName>
                    <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                </x-items.loading-indicator>
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400 mt-2">
            <span>{{ __('Kembali ke halaman') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log In') }}</flux:link>
        </div>
        <!--#CHECK USERNAME-->
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
