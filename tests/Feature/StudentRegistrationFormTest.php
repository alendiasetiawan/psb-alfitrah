<?php

use App\Livewire\Visitor\StudentRegistration\RegistrationForm;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\Support\InteractsWithStudentRegistration;

uses(InteractsWithStudentRegistration::class);

beforeEach(function () {
    $this->initializeStudentRegistration();
});

test('registration page handles missing admission configuration', function (string $table, string $message) {
    DB::table($table)->delete();

    $this->get(route('registration_form', ['branchId' => Crypt::encrypt(0)]))
        ->assertOk()->assertSee($message)->assertDontSee('wire:submit="saveStudentRegistration"', false);

    Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(0)])
        ->call('saveStudentRegistration')->assertSee($message);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
    $this->assertDatabaseCount('users', 0);
})->with([
    ['admissions', 'Informasi penerimaan santri baru belum tersedia.'],
    ['admission_batches', 'Gelombang pendaftaran belum tersedia.'],
    ['branches', 'Informasi pondok, jenjang pendidikan, atau kuota pendaftaran belum tersedia.'],
    ['education_programs', 'Informasi pondok, jenjang pendidikan, atau kuota pendaftaran belum tersedia.'],
    ['admission_quotas', 'Informasi pondok, jenjang pendidikan, atau kuota pendaftaran belum tersedia.'],
]);

test('invalid branch links show a persistent notice and cannot submit registration', function (string $kind) {
    $branchId = match ($kind) {
        'ciphertext' => 'broken-link',
        'array' => Crypt::encrypt(['id' => 1]),
        'negative' => Crypt::encrypt(-1),
        'missing' => Crypt::encrypt(99),
        'deleted' => Crypt::encrypt(1),
    };
    if ($kind === 'deleted') {
        DB::table('branches')->update(['deleted_at' => now()]);
    }

    $this->get(route('registration_form', compact('branchId')))->assertOk()->assertSee('Lihat kuota pendaftaran');
    Livewire::test(RegistrationForm::class, compact('branchId'))
        ->assertDontSee('wire:submit="saveStudentRegistration"', false)
        ->call('$refresh')->assertSee('Lihat kuota pendaftaran')
        ->call('saveStudentRegistration')->assertDontSee('Pendaftaran Berhasil');
    expect($this->registrationWhatsappRequests)->toBeEmpty();
})->with(['ciphertext', 'array', 'negative', 'missing', 'deleted']);

test('only programs with quotas for the current admission are offered', function () {
    DB::table('admissions')->insert(['id' => 2, 'name' => '2025-2026', 'status' => 'Tutup']);
    DB::table('education_programs')->insert(['id' => 2, 'branch_id' => 1, 'name' => 'Program Tahun Lalu']);
    DB::table('admission_quotas')->insert(['admission_id' => 2, 'education_program_id' => 2, 'amount' => 30, 'status' => 'Buka']);

    Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(1)])
        ->assertSee('Jenjang Test')->assertDontSee('Program Tahun Lalu')
        ->set('inputs.selectedEducationProgramId', 2)
        ->assertSet('isQuotaAvailable', false)->assertSee('Kuota penerimaan belum tersedia');
});

test('clearing or selecting a missing program is safe', function (mixed $programId) {
    $this->filledRegistrationForm()
        ->set('inputs.selectedEducationProgramId', $programId)
        ->assertSet('isQuotaAvailable', false)->assertSet('inputs.registrationFee', '');
})->with(['cleared' => '', 'deleted' => 99, 'bad value' => 'not-a-number']);

test('missing or unset fees disable saving without a server error', function (string $change) {
    $component = $this->filledRegistrationForm();
    if ($change === 'missing') {
        DB::table('admission_fees')->delete();
    } else {
        DB::table('admission_fees')->update(['registration_fee' => null]);
    }

    $component->set('inputs.selectedEducationProgramId', 1)
        ->assertSet('isQuotaAvailable', false)->assertSee('Biaya pendaftaran belum tersedia')
        ->call('saveStudentRegistration')->assertSee('Biaya pendaftaran belum tersedia');
    expect($this->registrationWhatsappRequests)->toBeEmpty();
    $this->assertDatabaseCount('users', 0);
})->with(['missing', 'unset']);

test('changing pondok clears the prior program and fee and rejects a program from another pondok', function () {
    DB::table('branches')->insert(['id' => 2, 'name' => 'Pondok Kedua']);
    DB::table('education_programs')->insert(['id' => 2, 'branch_id' => 2, 'name' => 'Program Kedua']);
    DB::table('admission_quotas')->insert(['admission_id' => 1, 'education_program_id' => 2, 'amount' => 10, 'status' => 'Buka']);

    $this->filledRegistrationForm()->set('inputs.selectedBranchId', 2)
        ->assertSet('inputs.selectedEducationProgramId', '')
        ->assertSet('inputs.registrationFee', '')->assertSet('isQuotaAvailable', false)
        ->assertSee('Program Kedua')->assertDontSee('Jenjang Test')
        ->set('inputs.selectedEducationProgramId', 1)
        ->assertSee('Program pendidikan tidak tersedia untuk pondok yang dipilih.')
        ->call('saveStudentRegistration')->assertHasErrors('inputs.selectedEducationProgramId');
    expect($this->registrationWhatsappRequests)->toBeEmpty();
});

test('server checks current configuration again before submitting', function (string $change) {
    $component = $this->filledRegistrationForm();
    match ($change) {
        'quota missing' => DB::table('admission_quotas')->delete(),
        'quota closed' => DB::table('admission_quotas')->update(['status' => 'Tutup']),
        'quota zero' => DB::table('admission_quotas')->update(['amount' => 0]),
        'period ended' => DB::table('admission_batches')->update(['close_date' => now()->subDay()->toDateString()]),
        'program deleted' => DB::table('education_programs')->update(['deleted_at' => now()]),
        'pondok deleted' => DB::table('branches')->update(['deleted_at' => now()]),
    };

    $component->call('saveStudentRegistration')->assertSet('registrationCompleted', false);
    $this->assertDatabaseCount('users', 0);
    expect($this->registrationWhatsappRequests)->toBeEmpty();
})->with(['quota missing', 'quota closed', 'quota zero', 'period ended', 'program deleted', 'pondok deleted']);

test('gateway failures give feedback without creating an account', function (string $failure) {
    $this->fakeRegistrationWhatsapp([match ($failure) {
        'invalid JSON' => new Response(200, [], 'not-json'),
        'missing status' => new Response(200, [], '{}'),
        'rejected' => new Response(200, [], '{"status":false}'),
        'HTTP failure' => new Response(503, [], 'unavailable'),
    }]);

    $this->filledRegistrationForm()->call('saveStudentRegistration')
        ->assertSee($failure === 'HTTP failure' ? 'Pendaftaran gagal' : 'kami tidak dapat mengirimkan pesan')
        ->assertSet('registrationCompleted', false);
    $this->assertDatabaseCount('users', 0);
})->with(['invalid JSON', 'missing status', 'rejected', 'HTTP failure']);

test('registration reads trusted admission and fee values and keeps the OTP step across refreshes', function () {
    $component = $this->filledRegistrationForm()
        ->set('inputs.activeAdmissionId', 99)->set('inputs.activeAdmissionBatchId', 99)->set('inputs.registrationFee', 1)
        ->call('saveStudentRegistration')->assertHasNoErrors()
        ->assertSet('registrationCompleted', true)->assertSee('Pendaftaran Berhasil')
        ->call('$refresh')->assertSee('Kode OTP');

    $this->assertDatabaseHas('students', ['admission_id' => 1, 'admission_batch_id' => 10, 'name' => 'Siswa Test']);
    $this->assertDatabaseHas('registration_payments', ['amount' => 350000]);
    expect($this->registrationWhatsappRequests)->toHaveCount(2);
    $component->call('saveStudentRegistration');
    $this->assertDatabaseCount('users', 1);
    expect($this->registrationWhatsappRequests)->toHaveCount(2);
});

test('OTP delivery rejection rolls back the registration', function () {
    $this->fakeRegistrationWhatsapp([new Response(200, [], '{"status":true}'), new Response(200, [], '{"status":false}')]);

    $this->filledRegistrationForm()->call('saveStudentRegistration')
        ->assertSet('registrationCompleted', false)->assertSee('Pendaftaran gagal');
    foreach (['users', 'parents', 'students', 'multi_students', 'registration_payments', 'admission_verifications'] as $table) {
        $this->assertDatabaseCount($table, 0);
    }
});

test('resend delivers the same OTP as the one stored and allows verification', function () {
    $component = $this->filledRegistrationForm()->call('saveStudentRegistration')->call('resendOtp');
    $user = User::first();
    $message = (string) $this->registrationWhatsappRequests[2]['request']->getBody();
    expect($message)->toContain('*'.$user->otp.'*');

    $component->set('inputs.otp', $user->otp)->call('otpVerification')->assertRedirect(route('login'));
    expect($user->fresh()->is_verified)->toBe(1);
});

test('a failed resend preserves the previous valid OTP', function () {
    $component = $this->filledRegistrationForm()->call('saveStudentRegistration');
    $previousOtp = User::first()->otp;
    $this->fakeRegistrationWhatsapp([new Response(503, [], 'unavailable')]);

    $component->call('resendOtp')->assertSee('coba lagi')->assertSee('Kode OTP');
    expect(User::first()->otp)->toBe($previousOtp);
});

test('an expired OTP shows feedback and does not verify the account', function () {
    $component = $this->filledRegistrationForm()->call('saveStudentRegistration');
    $user = User::first();
    $user->update(['otp_expired_at' => now()->subMinute()]);

    $component->set('inputs.otp', $user->otp)->call('otpVerification')->assertSee('Kode OTP sudah tidak berlaku');
    expect($user->fresh()->is_verified)->toBe(0);
});

test('partially filled configuration names do not cause type errors', function (string $table) {
    DB::table($table)->update(['name' => null]);
    $this->get(route('registration_form', ['branchId' => Crypt::encrypt(0)]))
        ->assertOk()->assertSee('belum tersedia');
})->with(['admissions', 'branches', 'education_programs']);

test('program capacity is checked against published passing students', function () {
    DB::table('admission_quotas')->update(['amount' => 1]);
    DB::table('students')->insert(['id' => 1, 'admission_id' => 1, 'education_program_id' => 1]);
    DB::table('placement_test_results')->insert(['student_id' => 1, 'final_result' => 'Lulus', 'publication_status' => 'Release']);

    $this->filledRegistrationForm()->assertSet('isQuotaAvailable', false)
        ->call('saveStudentRegistration')->assertSee('Kuota program yang dipilih sudah tutup atau penuh.');
    expect($this->registrationWhatsappRequests)->toBeEmpty();
    $this->assertDatabaseCount('users', 0);
});

test('admission year with a slash can register without failing number generation', function () {
    DB::table('admissions')->update(['name' => '2026/2027']);
    $this->filledRegistrationForm()->call('saveStudentRegistration')->assertSet('registrationCompleted', true);
    expect(DB::table('students')->value('reg_number'))->toStartWith('2627-');
});

test('OTP actions without a completed registration do not send messages or verify accounts', function () {
    Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(0)])
        ->set('inputs.otp', '123456')->call('otpVerification')->assertHasNoErrors()
        ->call('resendOtp')->assertHasNoErrors();
    expect($this->registrationWhatsappRequests)->toBeEmpty();
});
