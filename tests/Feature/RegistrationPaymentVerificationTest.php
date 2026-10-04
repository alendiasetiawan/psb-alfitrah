<?php

use App\Enums\RoleEnum;
use App\Livewire\Admin\DataVerification\RegistrationPayment\PaymentPaid;
use App\Livewire\Admin\DataVerification\RegistrationPayment\PaymentProcess;
use App\Models\Core\Admission;
use App\Models\Payment\RegistrationInvoice;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Support\InteractsWithRegistrationPayments;

uses(InteractsWithRegistrationPayments::class);

beforeEach(function () {
    $this->initializeRegistrationPayments();
    $this->signInForPayment($this->createPaymentUser(RoleEnum::ADMIN));
    Storage::disk('public')->put('registration-payments/transfer.jpg', 'evidence');
    $this->payment->update(['evidence' => 'registration-payments/transfer.jpg', 'payment_status' => 'Proses']);
    $this->verification->update(['registration_payment' => 'Proses']);
});

test('admin approves evidence and updates both statuses and access caches', function () {
    Cache::put('student_biodata_'.$this->student->id, 'stale');
    Cache::put('student_attachment_'.$this->student->id, 'stale');

    Livewire::test(PaymentProcess::class)
        ->call('openVerification', $this->student->id)
        ->set('paymentStatus', 'Valid')
        ->call('verifyPayment')->assertHasNoErrors()->assertSet('selectedStudentId', null);

    expect($this->payment->fresh()->payment_status)->toBe('Valid')
        ->and($this->verification->fresh()->registration_payment)->toBe('Valid')
        ->and(Cache::has('student_biodata_'.$this->student->id))->toBeFalse()
        ->and(Cache::has('student_attachment_'.$this->student->id))->toBeFalse();
});

test('admin rejects evidence and records the reason', function () {
    Livewire::test(PaymentProcess::class)
        ->call('openVerification', $this->student->id)
        ->set('paymentStatus', 'Tidak Valid')->set('invalidReason', 'Nominal transfer tidak sesuai')
        ->call('verifyPayment')->assertHasNoErrors();

    expect($this->payment->fresh()->payment_status)->toBe('Tidak Valid')
        ->and($this->verification->fresh()->registration_payment)->toBe('Tidak Valid')
        ->and($this->verification->fresh()->payment_error_msg)->toBe('Nominal transfer tidak sesuai');
});

test('rejection requires a reason and verification only accepts valid or invalid', function (string $status, string $field) {
    Livewire::test(PaymentProcess::class)
        ->call('openVerification', $this->student->id)
        ->set('paymentStatus', $status)->call('verifyPayment')->assertHasErrors($field);
    expect($this->payment->fresh()->payment_status)->toBe('Proses');
})->with([['Tidak Valid', 'invalidReason'], ['Expired', 'paymentStatus']]);

test('student accounts cannot open the admin verification component', function () {
    $this->signInForPayment($this->studentUser);
    Livewire::test(PaymentProcess::class)->assertForbidden();
});

test('admin actions reject a session that has expired since the page was opened', function () {
    $component = Livewire::test(PaymentProcess::class)->call('openVerification', $this->student->id)->set('paymentStatus', 'Valid');
    session(['userCheck' => false]);
    $component->call('verifyPayment')->assertForbidden();
    expect($this->payment->fresh()->payment_status)->toBe('Proses');
});

test('admin cannot verify an evidence free legacy payment', function () {
    $this->payment->update(['evidence' => null]);
    Livewire::test(PaymentProcess::class)->assertSee('Belum ada bukti transfer')->call('openVerification', $this->student->id)->assertForbidden();
});

test('a second administrator cannot overwrite a completed verification', function () {
    $component = Livewire::test(PaymentProcess::class)->call('openVerification', $this->student->id)->set('paymentStatus', 'Tidak Valid')->set('invalidReason', 'Tidak jelas');
    app(\App\Services\RegistrationPaymentService::class)->verifyEvidence($this->student->id, 'Valid', $this->payment->fresh()->evidence);
    $component->call('verifyPayment')->assertHasErrors('paymentStatus');
    expect($this->payment->fresh()->payment_status)->toBe('Valid');
});

test('a stale verification modal cannot approve evidence that was rejected and resubmitted', function () {
    $staleComponent = Livewire::test(PaymentProcess::class)
        ->call('openVerification', $this->student->id)
        ->set('paymentStatus', 'Valid');
    app(\App\Services\RegistrationPaymentService::class)->verifyEvidence($this->student->id, 'Tidak Valid', $this->payment->fresh()->evidence, 'Tidak jelas');
    $this->signInForPayment($this->studentUser);
    Livewire::test(\App\Livewire\Student\AdmissionData\RegistrationPayment::class)
        ->set('evidence', \Illuminate\Http\UploadedFile::fake()->image('replacement.jpg'))
        ->call('saveEvidence')
        ->assertHasNoErrors();
    $newEvidence = $this->payment->fresh()->evidence;
    $this->signInForPayment($this->createPaymentUser(RoleEnum::ADMIN));

    $staleComponent->call('verifyPayment')->assertHasErrors('paymentStatus');

    expect($this->payment->fresh()->payment_status)->toBe('Proses')
        ->and($this->payment->fresh()->evidence)->toBe($newEvidence)
        ->and($this->verification->fresh()->registration_payment)->toBe('Proses');
});

test('admin cannot open verification for a student outside the active admission', function () {
    $otherAdmission = Admission::create(['name' => '2027/2028', 'status' => 'Tutup']);
    $this->student->update(['admission_id' => $otherAdmission->id]);

    Livewire::test(PaymentProcess::class)->call('openVerification', $this->student->id);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

test('selected evidence snapshot cannot be changed by the browser', function () {
    Livewire::test(PaymentProcess::class)
        ->call('openVerification', $this->student->id)
        ->set('selectedEvidence', 'registration-payments/tampered.jpg');
})->throws(\Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException::class);

test('admin process lists render manual and rejected payments without invoices on both layouts', function (bool $mobile) {
    $this->payment->update(['payment_status' => 'Tidak Valid']);
    Livewire::test(PaymentProcess::class)->set('isMobile', $mobile)->assertSee('Siswa Test')->assertSee('Tidak Valid')
        ->assertDontSee('Lihat Bukti Transfer')
        ->assertDontSeeHtml('data-fancybox="registration-payment-'.$this->student->id.'"');
})->with([false, true]);

test('admin paid lists render manual payments without invoices on both layouts', function (bool $mobile) {
    $this->payment->update(['payment_status' => 'Valid']);
    Livewire::test(PaymentPaid::class)->set('isMobile', $mobile)->assertSee('Siswa Test')->assertSee('Transfer Manual');
})->with([false, true]);

test('admin paid lists render legacy invoice payment details on both layouts', function (bool $mobile) {
    $this->payment->update(['payment_status' => 'Valid', 'evidence' => null]);
    RegistrationInvoice::create([
        'student_id' => $this->student->id,
        'amount' => 350000,
        'payment_method' => 'Bank Transfer',
        'paid_at' => now(),
    ]);

    Livewire::test(PaymentPaid::class)->set('isMobile', $mobile)
        ->assertSee('Siswa Test')->assertSee('Bank Transfer')->assertDontSee('Transfer Manual');
})->with([false, true]);
