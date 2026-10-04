<?php

namespace App\Livewire\Admin\Setting;

use App\Enums\RoleEnum;
use App\Services\PaymentAccountService;
use Detection\MobileDetect;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Title('Rekening Pembayaran')]
class PaymentAccount extends Component
{
    public bool $isMobile = false;

    public string $bankName = '';

    public string $accountNumber = '';

    public string $accountName = '';

    public function mount(MobileDetect $mobileDetect, PaymentAccountService $paymentAccountService): void
    {
        $this->authorizeAdmin();
        $this->isMobile = $mobileDetect->isMobile();
        $account = $paymentAccountService->registrationAccount();
        $this->bankName = $account['bank_name'];
        $this->accountNumber = $account['account_number'];
        $this->accountName = $account['account_name'];
    }

    public function save(PaymentAccountService $paymentAccountService): void
    {
        $this->authorizeAdmin();
        $this->bankName = trim($this->bankName);
        $this->accountNumber = trim($this->accountNumber);
        $this->accountName = trim($this->accountName);

        $this->validate([
            'bankName' => ['required', 'string', 'max:255'],
            'accountNumber' => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/'],
            'accountName' => ['required', 'string', 'max:255'],
        ], [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'accountNumber.regex' => 'Nomor rekening hanya boleh berisi angka tanpa spasi atau tanda baca.',
        ], [
            'bankName' => 'Nama bank',
            'accountNumber' => 'Nomor rekening',
            'accountName' => 'Nama pemilik rekening',
        ]);

        try {
            $paymentAccountService->saveRegistrationAccount($this->bankName, $this->accountNumber, $this->accountName);
        } catch (Throwable $exception) {
            report($exception);
            session()->flash('payment-account-failed', 'Rekening pembayaran gagal disimpan. Silakan coba lagi.');

            return;
        }

        session()->flash('payment-account-success', 'Rekening pembayaran berhasil disimpan.');
    }

    protected function authorizeAdmin(): void
    {
        abort_unless(session('userCheck') && (int) auth()->user()?->role_id === RoleEnum::ADMIN, 403);
    }

    public function render(): View
    {
        $this->authorizeAdmin();

        if ($this->isMobile) {
            return view('livewire.mobile.admin.setting.payment-account')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'link' => 'admin.setting.landing',
            ]);
        }

        return view('livewire.web.admin.setting.payment-account')->layout('components.layouts.web.web-app');
    }
}
