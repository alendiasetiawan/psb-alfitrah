<?php

use App\Enums\RoleEnum;
use App\Livewire\Admin\Setting\PaymentAccount;
use App\Livewire\Student\AdmissionData\RegistrationPayment;
use App\Models\Payment\PaymentAccount as PaymentAccountModel;
use App\Services\PaymentAccountService;
use Livewire\Livewire;
use Tests\Support\InteractsWithRegistrationPayments;

uses(InteractsWithRegistrationPayments::class);

beforeEach(function () {
    $this->initializeRegistrationPayments();
    $this->signInForPayment($this->createPaymentUser(RoleEnum::ADMIN));
});

test('account settings initially use the existing configured bank details', function () {
    config([
        'services.registration_payment.bank_name' => 'Bank Lama',
        'services.registration_payment.account_number' => '0012345',
        'services.registration_payment.account_name' => 'Yayasan Lama',
    ]);

    Livewire::test(PaymentAccount::class)
        ->assertSet('bankName', 'Bank Lama')
        ->assertSet('accountNumber', '0012345')
        ->assertSet('accountName', 'Yayasan Lama');

    $this->assertDatabaseCount('payment_accounts', 0);
});

test('admin can save and replace the account and students see the saved details in both layouts', function (bool $mobile) {
    config(['services.registration_payment.bank_name' => 'Bank Lama']);

    $settings = Livewire::test(PaymentAccount::class)->set('isMobile', $mobile);
    $settings->set('bankName', 'Bank Pertama')
        ->set('accountNumber', '00987654321')
        ->set('accountName', 'Yayasan Pertama')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('Rekening pembayaran berhasil disimpan.');

    $settings->set('bankName', ' Bank Baru ')
        ->set('accountNumber', ' 00123456789 ')
        ->set('accountName', ' Yayasan Baru ')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('payment_accounts', 1);
    $this->assertDatabaseHas('payment_accounts', [
        'id' => PaymentAccountModel::REGISTRATION_ID,
        'bank_name' => 'Bank Baru',
        'account_number' => '00123456789',
        'account_name' => 'Yayasan Baru',
    ]);

    Livewire::test(PaymentAccount::class)
        ->assertSet('bankName', 'Bank Baru')
        ->assertSet('accountNumber', '00123456789')
        ->assertSet('accountName', 'Yayasan Baru');

    $this->signInForPayment($this->studentUser);
    Livewire::test(RegistrationPayment::class)->set('isMobile', $mobile)
        ->assertSee('Bank Baru')
        ->assertSee('00123456789')
        ->assertSee('Yayasan Baru')
        ->assertDontSee('Bank Lama');
})->with([false, true]);

test('student refresh reads subsequent account changes without stale cached details', function () {
    $service = app(PaymentAccountService::class);
    $service->saveRegistrationAccount('Bank Pertama', '00123', 'Yayasan Pertama');
    $this->signInForPayment($this->studentUser);
    $component = Livewire::test(RegistrationPayment::class)->assertSee('Bank Pertama');

    $service->saveRegistrationAccount('Bank Kedua', '00456', 'Yayasan Kedua');
    $component->call('$refresh')->assertSee('Bank Kedua')->assertSee('00456')->assertDontSee('Bank Pertama');
});

test('invalid account settings do not overwrite the saved account', function (string $property, string $value, string $rule) {
    app(PaymentAccountService::class)->saveRegistrationAccount('Bank Asli', '000123', 'Yayasan Asli');

    Livewire::test(PaymentAccount::class)
        ->set($property, $value)
        ->call('save')
        ->assertHasErrors([$property => $rule]);

    $this->assertDatabaseHas('payment_accounts', ['bank_name' => 'Bank Asli', 'account_number' => '000123', 'account_name' => 'Yayasan Asli']);
})->with([
    ['bankName', '   ', 'required'],
    ['accountNumber', '', 'required'],
    ['accountName', ' ', 'required'],
    ['accountNumber', '123ABC', 'regex'],
    ['accountNumber', '123-456', 'regex'],
    ['accountNumber', '123 456', 'regex'],
    ['bankName', str_repeat('a', 256), 'max'],
    ['accountNumber', str_repeat('1', 51), 'max'],
    ['accountName', str_repeat('a', 256), 'max'],
]);

test('only admins with an active session can open account settings', function (string $change) {
    if ($change === 'role') {
        $this->signInForPayment($this->studentUser);
    } else {
        session(['userCheck' => false]);
    }

    $this->get(route('admin.setting.payment_account'))->assertRedirect(route('login'));
    Livewire::test(PaymentAccount::class)->assertForbidden();
})->with(['role', 'session']);

test('admin permission is rechecked when saving an open settings form', function (string $change) {
    $component = Livewire::test(PaymentAccount::class)
        ->set('bankName', 'Bank Test')
        ->set('accountNumber', '00123')
        ->set('accountName', 'Yayasan Test');

    if ($change === 'role') {
        auth()->user()->update(['role_id' => RoleEnum::STUDENT]);
    } else {
        session(['userCheck' => false]);
    }

    $component->call('save')->assertForbidden();
    $this->assertDatabaseCount('payment_accounts', 0);
})->with(['role', 'session']);

test('admin route and mobile settings menus expose payment account settings', function () {
    $this->get(route('admin.setting.payment_account'))->assertOk()->assertSee('Simpan Rekening');
    $this->get(route('admin.setting.landing'))->assertOk()->assertSee('Rekening Pembayaran');
    $this->get(route('admin.mega_menu'))->assertOk()->assertSee('Rekening Pembayaran');
});

test('failed persistence gives feedback and preserves the existing account', function () {
    app(PaymentAccountService::class)->saveRegistrationAccount('Bank Asli', '00123', 'Yayasan Asli');
    $component = Livewire::test(PaymentAccount::class)->set('bankName', 'Bank Baru');
    $this->mock(PaymentAccountService::class)->shouldReceive('saveRegistrationAccount')->once()->andThrow(new RuntimeException('Database unavailable.'));

    $component->call('save')->assertSee('Rekening pembayaran gagal disimpan.');
    $this->assertDatabaseHas('payment_accounts', ['bank_name' => 'Bank Asli']);
});
