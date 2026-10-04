<?php

use App\Enums\PaymentStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\VerificationStatusEnum;
use App\Livewire\Student\AdmissionData\RegistrationPayment;
use App\Models\Core\Branch;
use App\Services\RegistrationPaymentService;
use App\Services\UploadFileService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\Support\InteractsWithRegistrationPayments;

uses(InteractsWithRegistrationPayments::class);

beforeEach(function () {
    $this->initializeRegistrationPayments();
});

test('student uploads a transfer image and both statuses await verification', function (string $extension) {
    Livewire::test(RegistrationPayment::class)
        ->set('evidence', UploadedFile::fake()->image('transfer.'.$extension))
        ->call('saveEvidence')
        ->assertHasNoErrors()
        ->assertSet('evidence', null)
        ->assertSee('Menunggu Verifikasi Admin')
        ->assertSee('Bukti Transfer Sebelumnya')
        ->assertSeeHtml('data-modal="previous-payment-evidence"')
        ->assertDontSee('Checkout Pembayaran');

    $payment = $this->payment->fresh();
    expect($payment->payment_status)->toBe(VerificationStatusEnum::PROCESS)
        ->and($this->verification->fresh()->registration_payment)->toBe(VerificationStatusEnum::PROCESS);
    Storage::disk('public')->assertExists($payment->evidence);
    $this->assertDatabaseCount('registration_invoices', 0);
})->with(['jpg', 'jpeg', 'png']);

test('student must provide a transfer image', function () {
    Livewire::test(RegistrationPayment::class)->call('saveEvidence')->assertHasErrors(['evidence' => 'required']);
    expect($this->payment->fresh()->payment_status)->toBe('Belum');
});

test('student cannot upload non images or unsupported images', function (string $filename, string $mime) {
    Livewire::test(RegistrationPayment::class)
        ->set('evidence', UploadedFile::fake()->create($filename, 20, $mime))
        ->call('saveEvidence')->assertHasErrors('evidence');
    expect($this->payment->fresh()->evidence)->toBeNull();
})->with([['transfer.pdf', 'application/pdf'], ['transfer.svg', 'image/svg+xml'], ['transfer.php', 'application/x-php']]);

test('transfer images over five megabytes are rejected', function () {
    Livewire::test(RegistrationPayment::class)
        ->set('evidence', UploadedFile::fake()->image('transfer.jpg')->size(5121))
        ->call('saveEvidence')->assertHasErrors(['evidence' => 'max']);
    expect($this->payment->fresh()->evidence)->toBeNull();
});

test('transfer images at five megabytes are accepted', function () {
    Livewire::test(RegistrationPayment::class)
        ->set('evidence', UploadedFile::fake()->image('transfer.jpg')->size(5120))
        ->call('saveEvidence')->assertHasNoErrors();
    expect($this->payment->fresh()->payment_status)->toBe('Proses');
});

test('rejected and expired payments can be resubmitted and the old image is replaced', function (string $status) {
    Storage::disk('public')->put('registration-payments/old.jpg', 'old evidence');
    $this->payment->update(['evidence' => 'registration-payments/old.jpg', 'payment_status' => $status]);
    $this->verification->update(['registration_payment' => 'Tidak Valid', 'payment_error_msg' => 'Gambar tidak jelas']);

    $component = Livewire::test(RegistrationPayment::class);
    if ($status === VerificationStatusEnum::INVALID) {
        $component->assertSee('Gambar tidak jelas');
    }
    $component->set('evidence', UploadedFile::fake()->image('retry.png'))
        ->call('saveEvidence')->assertHasNoErrors();

    Storage::disk('public')->assertMissing('registration-payments/old.jpg');
    Storage::disk('public')->assertExists($this->payment->fresh()->evidence);
    expect($this->payment->fresh()->payment_status)->toBe('Proses')
        ->and($this->verification->fresh()->registration_payment)->toBe('Proses')
        ->and($this->verification->fresh()->payment_error_msg)->toBeNull();
})->with([VerificationStatusEnum::INVALID, PaymentStatusEnum::EXPIRED]);

test('valid or processing evidence cannot be replaced', function (string $status) {
    Storage::disk('public')->put('registration-payments/old.jpg', 'original');
    $this->payment->update(['payment_status' => $status, 'evidence' => 'registration-payments/old.jpg']);
    $this->verification->update(['registration_payment' => $status]);

    Livewire::test(RegistrationPayment::class)
        ->set('evidence', UploadedFile::fake()->image('replacement.jpg'))
        ->call('saveEvidence')->assertHasErrors('evidence');

    expect($this->payment->fresh()->evidence)->toBe('registration-payments/old.jpg');
    Storage::disk('public')->assertExists('registration-payments/old.jpg');
})->with([VerificationStatusEnum::VALID, VerificationStatusEnum::PROCESS]);

test('a pending legacy invoice without evidence accepts a manual upload', function () {
    $this->payment->update(['payment_status' => 'Proses']);
    Livewire::test(RegistrationPayment::class)
        ->assertSee('Kirim Bukti Transfer')
        ->set('evidence', UploadedFile::fake()->image('legacy.jpg'))
        ->call('saveEvidence')->assertHasNoErrors();
    expect($this->payment->fresh()->evidence)->not->toBeNull();
});

test('failed storage preserves rejected evidence and statuses', function () {
    Storage::disk('public')->put('registration-payments/old.jpg', 'original');
    $this->payment->update(['payment_status' => 'Tidak Valid', 'evidence' => 'registration-payments/old.jpg']);
    $this->verification->update(['registration_payment' => 'Tidak Valid', 'payment_error_msg' => 'Tidak jelas']);
    $this->mock(UploadFileService::class)->shouldReceive('compressAndSavePhoto')->once()->andReturn('missing.jpg');

    Livewire::test(RegistrationPayment::class)
        ->set('evidence', UploadedFile::fake()->image('retry.jpg'))
        ->call('saveEvidence')->assertSee('Bukti transfer gagal disimpan.');

    expect($this->payment->fresh()->payment_status)->toBe('Tidak Valid')
        ->and($this->payment->fresh()->evidence)->toBe('registration-payments/old.jpg')
        ->and($this->verification->fresh()->payment_error_msg)->toBe('Tidak jelas');
    Storage::disk('public')->assertExists('registration-payments/old.jpg');
});

test('a database failure after saving new evidence removes it and preserves the previous evidence', function () {
    $oldPath = 'registration-payments/old.jpg';
    $newPath = 'registration-payments/'.$this->student->id.'/new.jpg';
    Storage::disk('public')->put($oldPath, 'original');
    Storage::disk('public')->put($newPath, 'new evidence');
    $this->payment->update(['payment_status' => VerificationStatusEnum::INVALID, 'evidence' => $oldPath]);
    $this->verification->update(['registration_payment' => VerificationStatusEnum::INVALID, 'payment_error_msg' => 'Tidak jelas']);
    $this->mock(UploadFileService::class)->shouldReceive('compressAndSavePhoto')->once()->andReturn($newPath);
    \App\Models\AdmissionData\RegistrationPayment::updating(function (): never {
        throw new RuntimeException('Database write failed.');
    });

    DB::transaction(function (): void {
        expect(fn () => app(RegistrationPaymentService::class)->submitEvidence(
            $this->student->id,
            $this->studentUser->id,
            UploadedFile::fake()->image('retry.jpg'),
        ))->toThrow(RuntimeException::class);
    });

    expect($this->payment->fresh()->payment_status)->toBe(VerificationStatusEnum::INVALID)
        ->and($this->payment->fresh()->evidence)->toBe($oldPath)
        ->and($this->verification->fresh()->registration_payment)->toBe(VerificationStatusEnum::INVALID)
        ->and($this->verification->fresh()->payment_error_msg)->toBe('Tidak jelas');
    Storage::disk('public')->assertExists($oldPath);
    Storage::disk('public')->assertMissing($newPath);
});

test('an after commit cache observer failure preserves committed replacement evidence', function () {
    $oldPath = 'registration-payments/old.jpg';
    $newPath = 'registration-payments/'.$this->student->id.'/new.jpg';
    Storage::disk('public')->put($oldPath, 'original');
    Storage::disk('public')->put($newPath, 'new evidence');
    $this->payment->update(['payment_status' => VerificationStatusEnum::INVALID, 'evidence' => $oldPath]);
    $this->verification->update(['registration_payment' => VerificationStatusEnum::INVALID, 'payment_error_msg' => 'Tidak jelas']);
    $this->mock(UploadFileService::class)->shouldReceive('compressAndSavePhoto')->once()->andReturn($newPath);
    \App\Models\AdmissionData\AdmissionVerification::updated(function (): void {
        DB::afterCommit(function (): void {
            Cache::forget('after-commit-cache-failure');
        });
    });
    Cache::shouldReceive('forget')->atLeast()->once()->andReturnUsing(function (string $key): bool {
        if ($key === 'after-commit-cache-failure') {
            throw new RuntimeException('Cache unavailable.');
        }

        return true;
    });

    app(RegistrationPaymentService::class)->submitEvidence(
        $this->student->id,
        $this->studentUser->id,
        UploadedFile::fake()->image('retry.jpg'),
    );

    expect($this->payment->fresh()->payment_status)->toBe(VerificationStatusEnum::PROCESS)
        ->and($this->payment->fresh()->evidence)->toBe($newPath)
        ->and($this->verification->fresh()->registration_payment)->toBe(VerificationStatusEnum::PROCESS)
        ->and($this->verification->fresh()->payment_error_msg)->toBeNull();
    Storage::disk('public')->assertExists($newPath);
});

test('payments cannot be submitted on behalf of another account', function () {
    $anotherUser = $this->createPaymentUser(3);
    expect(fn () => app(RegistrationPaymentService::class)->submitEvidence($this->student->id, $anotherUser->id, UploadedFile::fake()->image('transfer.jpg')))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    expect($this->payment->fresh()->evidence)->toBeNull();
});

test('student id cannot be changed by the browser', function () {
    Livewire::test(RegistrationPayment::class)->set('studentId', $this->student->id + 1);
})->throws(\Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException::class);

test('student upload requires an active student session and role on every action', function (string $change) {
    $component = Livewire::test(RegistrationPayment::class)->set('evidence', UploadedFile::fake()->image('transfer.jpg'));

    if ($change === 'session') {
        session(['userCheck' => false]);
    } else {
        $this->studentUser->update(['role_id' => RoleEnum::ADMIN]);
    }

    $component->call('saveEvidence')->assertForbidden();
    expect($this->payment->fresh()->evidence)->toBeNull();
})->with(['session', 'role']);

test('both layouts display valid manual payments without an invoice', function (bool $mobile) {
    $this->payment->update(['payment_status' => 'Valid']);
    Livewire::test(RegistrationPayment::class)->set('isMobile', $mobile)
        ->assertSee('Pembayaran Berhasil')->assertSee('Isi Biodata')->assertDontSee('Kirim Bukti Transfer');
})->with([false, true]);

test('configured bank details are displayed to the student', function (bool $mobile) {
    config(['services.registration_payment.bank_name' => 'Bank Test', 'services.registration_payment.account_number' => '1234567890', 'services.registration_payment.account_name' => 'Yayasan Test']);
    Livewire::test(RegistrationPayment::class)->set('isMobile', $mobile)
        ->assertSee('Bank Test')->assertSee('1234567890')->assertSee('Yayasan Test')
        ->assertSee('Salin')->assertSee('Lampirkan Bukti Transfer');
})->with([false, true]);

test('processing payments link to their branch WhatsApp with the student name', function (bool $mobile, string $phone) {
    Branch::findOrFail($this->student->branch_id)->update(['mobile_phone' => $phone]);
    Branch::create(['name' => 'Cabang Lain', 'mobile_phone' => '6289999999999']);
    $this->student->update(['name' => 'Aisyah & Ali']);
    $this->payment->update(['payment_status' => 'Proses', 'evidence' => 'registration-payments/transfer.jpg']);
    $message = rawurlencode('Halo Admin, saya sudah transfer biaya pendaftaran atas nama *Aisyah & Ali*. Mohon untuk ditindaklanjuti, terima Kasih');

    Livewire::test(RegistrationPayment::class)->set('isMobile', $mobile)
        ->assertSee('Perbarui Status')->assertSee('Hubungi Admin')
        ->assertSeeHtml('href="https://wa.me/6281234567890?text='.$message.'"')
        ->assertDontSee('6289999999999');
})->with([false, true])->with(['0812-3456-7890', '+62 812 3456 7890']);

test('processing payments hide admin contact when their branch phone is null', function (bool $mobile) {
    config(['services.whatsapp.phone' => '6289999999999']);
    $this->payment->update(['payment_status' => 'Proses', 'evidence' => 'registration-payments/transfer.jpg']);

    Livewire::test(RegistrationPayment::class)->set('isMobile', $mobile)
        ->assertSee('Perbarui Status')->assertDontSee('Hubungi Admin');
})->with([false, true]);

test('xendit webhook can no longer change manual payment statuses', function () {
    expect(fn () => Route::getRoutes()->match(Request::create('/api/webhook/xendit/confirm-invoice', 'POST')))
        ->toThrow(NotFoundHttpException::class);
    expect($this->payment->fresh()->payment_status)->toBe('Belum');
});
