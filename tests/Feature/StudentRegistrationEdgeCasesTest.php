<?php

use App\Livewire\Visitor\StudentRegistration\RegistrationForm;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\Support\InteractsWithStudentRegistration;

uses(InteractsWithStudentRegistration::class);

beforeEach(function () {
    $this->initializeStudentRegistration();
});

test('a soft-deleted active batch prevents registration', function () {
    DB::table('admission_batches')->update(['deleted_at' => now()]);

    Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(1)])
        ->assertSee('Gelombang pendaftaran belum tersedia')
        ->call('saveStudentRegistration')
        ->assertSee('Gelombang pendaftaran belum tersedia');

    $this->assertDatabaseCount('users', 0);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
});

test('a batch with missing dates prevents registration at mount', function (array $dates) {
    DB::table('admission_batches')->update($dates);

    Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(1)])
        ->assertSee('Gelombang pendaftaran belum tersedia')
        ->call('saveStudentRegistration')
        ->assertSee('Gelombang pendaftaran belum tersedia');

    $this->assertDatabaseCount('users', 0);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
})->with([
    'missing opening date' => [['open_date' => null]],
    'missing closing date' => [['close_date' => null]],
    'missing both dates' => [['open_date' => null, 'close_date' => null]],
]);

test('a batch whose dates disappear after the form loads prevents registration', function (array $dates) {
    $component = $this->filledRegistrationForm();
    DB::table('admission_batches')->update($dates);

    $component->call('saveStudentRegistration')
        ->assertSee('Gelombang pendaftaran belum tersedia')
        ->assertSet('registrationCompleted', false);

    $this->assertDatabaseCount('users', 0);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
})->with([
    'missing opening date' => [['open_date' => null]],
    'missing closing date' => [['close_date' => null]],
    'missing both dates' => [['open_date' => null, 'close_date' => null]],
]);

test('an empty or invalid branch selection cannot be submitted', function (mixed $branchId) {
    $this->filledRegistrationForm()
        ->set('inputs.selectedBranchId', $branchId)
        ->assertSet('inputs.selectedBranchId', '')
        ->assertSet('inputs.selectedEducationProgramId', '')
        ->call('saveStudentRegistration')
        ->assertHasErrors('inputs.selectedBranchId');

    $this->assertDatabaseCount('users', 0);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
})->with(['empty' => '', 'unknown' => 99, 'non-numeric' => 'not-a-branch']);

test('a branch renamed to an unavailable value after mount cannot select its program', function (?string $branchName) {
    $component = Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(1)])
        ->set('inputs.studentName', 'Siswa Test')
        ->set('inputs.gender', 'Laki-Laki')
        ->set('inputs.mobilePhone', '81234567890')
        ->set('inputs.password', 'password-test');
    DB::table('branches')->update(['name' => $branchName]);

    $component->set('inputs.selectedEducationProgramId', 1)
        ->assertSet('inputs.selectedEducationProgramId', '')
        ->assertSet('inputs.registrationFee', '')
        ->assertSet('isQuotaAvailable', false)
        ->assertSee('Program pendidikan tidak tersedia untuk pondok yang dipilih.')
        ->call('saveStudentRegistration')
        ->assertSet('registrationCompleted', false);

    $this->assertDatabaseCount('users', 0);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
})->with(['null name' => null, 'empty name' => '']);

test('an existing mobile number is rejected when saving without sending WhatsApp', function () {
    User::create([
        'role_id' => 2,
        'username' => '81234567890',
        'password' => 'existing-password',
        'fullname' => 'Existing User',
        'gender' => 'Laki-Laki',
        'mobile_phone' => '6281234567890',
    ]);

    $this->filledRegistrationForm()
        ->call('saveStudentRegistration')
        ->assertHasErrors(['inputs.mobilePhone' => 'unique']);

    $this->assertDatabaseCount('users', 1);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
});

test('saving refreshes the fee and does not trust the previously displayed amount', function () {
    $component = $this->filledRegistrationForm()->assertSet('inputs.registrationFee', 350000);
    DB::table('admission_fees')->update(['registration_fee' => 475000]);

    $component->call('saveStudentRegistration')->assertSet('registrationCompleted', true);

    $this->assertDatabaseHas('registration_payments', ['amount' => 475000]);
});

test('a zero registration fee is a valid current fee', function () {
    DB::table('admission_fees')->update(['registration_fee' => 0]);

    $this->filledRegistrationForm()
        ->assertSet('inputs.registrationFee', 0)
        ->call('saveStudentRegistration')
        ->assertSet('registrationCompleted', true);

    $this->assertDatabaseHas('registration_payments', ['amount' => 0]);
});

test('a completed registration OTP cannot verify an account from another component instance', function () {
    $registeredComponent = $this->filledRegistrationForm()->call('saveStudentRegistration');
    $user = User::sole();

    Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(1)])
        ->set('inputs.otp', $user->otp)
        ->call('otpVerification')
        ->assertSet('registrationCompleted', false)
        ->assertSet('registeredUserId', null);

    expect($user->fresh()->is_verified)->toBe(0);
    $registeredComponent->set('inputs.otp', $user->otp)
        ->call('otpVerification')
        ->assertRedirect(route('login'));
    expect($user->fresh()->is_verified)->toBe(1);
});
