<div class="flex flex-col gap-6">
   @if (session('resend-otp-success'))
   <!--OTP Verification-->
   <div class="fixed inset-0 flex items-center justify-center">
      <x-cards.basic-card class="md:w-3/6 lg:w-2/6 p-5">
         <!--Alert OTP error-->
         @if (session('otp-failed'))
         <x-notifications.basic-alert isCloseable="true">
            <x-slot:title>{{ session('otp-failed') }}</x-slot:title>
         </x-notifications.basic-alert>
         @endif
         <!--#Alert OTP error-->

         <!--Alert OTP Success-->
         @if (session('otp-success'))
         <x-notifications.basic-alert isCloseable="true" variant="success">
            <x-slot:title>{{ session('otp-success') }}</x-slot:title>
         </x-notifications.basic-alert>
         @endif
         <!--#Alert OTP Success-->

         <form wire:submit='otpVerification' x-data="{
                  ...formValidation({
                        otp: ['required']
                  }),
                  ...countDownTimer({
                        countdown: 30
                  })
               }
            " x-init="startTimer()" x-on:start-countdown.window="startTimer()">
            <div class="flex flex-col justify-center items-center mb-4 text-center space-y-4">
               <flux:icon.shield-user class="size-20 text-green-500" />
               <flux:heading size="xxl">
                  Verifikasi Akun
               </flux:heading>

               <flux:text variant="ghost">
                  Kami telah mengirimkan kode OTP ke nomor whatsapp anda, silahkan masukan kode tersebut pada kolom di
                  bawah ini:
               </flux:text>

               <flux:input type="number" icon="lock-closed" placeholder="Kode OTP" wire:model="inputs.otp"
                  fieldName="otp" :isValidate="true" />

               <flux:button type="submit" variant="primary" x-bind:disabled="!isSubmitActive" :loading="false"
                  class="w-full">
                  <x-items.loading-indicator wireTarget="otpVerification">
                     <x-slot:buttonName>Verifikasi</x-slot:buttonName>
                     <x-slot:buttonReplaceName>Proses</x-slot:buttonReplaceName>
                  </x-items.loading-indicator>
               </flux:button>

               <flux:button type="button" variant="ghost" class="w-full" x-on:click="resetTimer()"
                  wire:click='resendOtp' x-bind:disabled="countdown > 0 ? true : false">
                  <span x-show="countdown > 0" class="text-gray-500">
                     Kirim Ulang OTP (<span x-text="countdown"></span>)
                  </span>
                  <span x-show="countdown === 0" class="text-blue-500">
                     Kirim Ulang OTP
                  </span>
               </flux:button>
            </div>
         </form>
      </x-cards.basic-card>
   </div>
   <!--#OTP Verification-->
   @else
   <x-auth-header :title="__('PSB Online Al Fitrah Islamic School')"
      :description="__('Silahkan masuk untuk mengikuti rangkaian proses Penerimaan Santri Baru secara Online')" />

   <!--Alert Account Verification-->
   @if (session('otp-success'))
   <x-notifications.basic-alert variant="success" isCloseable="true">
      <x-slot:title>{{ session('otp-success') }}</x-slot:title>
   </x-notifications.basic-alert>
   @endif
   <!--#Alert Account Verification-->

   <!--Alert when user unverified-->
   @if (session('user-unverified'))
   <x-notifications.basic-alert>
      <x-slot:title>{{ session('user-unverified') }}</x-slot:title>
      <x-slot:action>
         <flux:button variant="primary" size="sm" wire:click='resendOtp'>
            Kirim OTP
         </flux:button>
      </x-slot:action>
   </x-notifications.basic-alert>
   @endif
   <!--#Alert when user unverified-->

   @if (session('resend-otp-failed'))
   <x-notifications.basic-alert variant="danger" isCloseable="true">
      <x-slot:title>{{ session('resend-otp-failed') }}</x-slot:title>
   </x-notifications.basic-alert>
   @endif

   <form method="POST" wire:submit="login" class="flex flex-col gap-6">
      <!-- Email Address -->
      <flux:input wire:model="username" :label="__('Username/Nomor Whatsapp')" type="text"
         placeholder="Masukan nomor HP" required autofocus />

      <!-- Password -->
      <div class="relative">
         <flux:input wire:model="password" :label="__('Password')" type="password" required
            autocomplete="current-password" placeholder="Masukan kata sandi" viewable />

         @if (Route::has('password.request'))
         <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
            {{ __('Lupa Password?') }}
         </flux:link>
         @endif
      </div>

      <!-- Remember Me -->
      <flux:checkbox wire:model="remember" :label="__('Simpan Password')" />

      <div class="flex items-center justify-end">
         <flux:button variant="primary" type="submit" class="w-full">{{ __('Masuk') }}</flux:button>
      </div>
   </form>

   @if (Route::has('register'))
   <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
      <span>{{ __('Siswa baru?') }}</span>
      <flux:link :href="route('branch_quota')" wire:navigate>{{ __('Daftar Disini') }}</flux:link>
   </div>
   @endif
   @endif
</div>