<?php

namespace Tests\Support;

use App\Livewire\Visitor\StudentRegistration\RegistrationForm;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

trait InteractsWithStudentRegistration
{
    protected array $registrationWhatsappRequests = [];

    protected function initializeStudentRegistration(): void
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.foreign_key_constraints' => false,
            'app.whacenter_api_send_url' => 'https://whatsapp.example.test/send',
            'app.whacenter_device_id' => 'test-device',
        ]);
        DB::purge('sqlite');

        // Avoid the MySQL-only generated migrations and keep local data untouched.
        Schema::create('admissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('admission_batches', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('admission_id');
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('branches', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('education_programs', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('branch_id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('admission_quotas', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('admission_id');
            $table->bigInteger('education_program_id');
            $table->integer('amount')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
        Schema::create('admission_fees', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('admission_id');
            $table->bigInteger('education_program_id');
            $table->double('registration_fee')->nullable();
            $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->integer('role_id');
            foreach (['username', 'password', 'fullname', 'gender', 'mobile_phone'] as $field) {
                $table->string($field);
            }
            $table->string('otp')->nullable();
            $table->timestamp('otp_expired_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
        Schema::create('parents', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            foreach (['user_id', 'parent_id', 'branch_id', 'education_program_id', 'admission_id', 'admission_batch_id'] as $field) {
                $table->bigInteger($field)->nullable();
            }
            foreach (['reg_number', 'name', 'gender', 'country_code', 'mobile_phone'] as $field) {
                $table->string($field)->nullable();
            }
            $table->timestamps();
        });
        Schema::create('multi_students', function (Blueprint $table): void {
            $table->id();
            foreach (['user_id', 'parent_id', 'student_id'] as $field) {
                $table->bigInteger($field);
            }
            $table->timestamps();
        });
        Schema::create('registration_payments', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('student_id');
            $table->double('amount');
            $table->timestamps();
        });
        Schema::create('admission_verifications', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('student_id');
            $table->timestamps();
        });
        Schema::create('placement_test_results', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('student_id');
            $table->string('final_result');
            $table->string('publication_status');
        });

        DB::table('admissions')->insert(['id' => 1, 'name' => '2026-2027', 'status' => 'Buka']);
        DB::table('admission_batches')->insert([
            'id' => 10, 'admission_id' => 1,
            'open_date' => now()->subDay()->toDateString(), 'close_date' => now()->addDay()->toDateString(),
        ]);
        DB::table('branches')->insert(['id' => 1, 'name' => 'Pondok Test']);
        DB::table('education_programs')->insert(['id' => 1, 'branch_id' => 1, 'name' => 'Jenjang Test']);
        DB::table('admission_quotas')->insert(['admission_id' => 1, 'education_program_id' => 1, 'amount' => 30, 'status' => 'Buka']);
        DB::table('admission_fees')->insert(['admission_id' => 1, 'education_program_id' => 1, 'registration_fee' => 350000]);
        $this->fakeRegistrationWhatsapp(array_map(fn () => new Response(200, [], '{"status":true}'), range(1, 6)));
    }

    protected function fakeRegistrationWhatsapp(array $responses): void
    {
        $this->registrationWhatsappRequests = [];
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->registrationWhatsappRequests));
        $this->app->instance(Client::class, new Client(['handler' => $stack]));
    }

    protected function filledRegistrationForm(): Testable
    {
        return Livewire::test(RegistrationForm::class, ['branchId' => Crypt::encrypt(1)])
            ->set('inputs.studentName', 'Siswa Test')
            ->set('inputs.gender', 'Laki-Laki')
            ->set('inputs.selectedEducationProgramId', 1)
            ->set('inputs.mobilePhone', '81234567890')
            ->set('inputs.password', 'password-test');
    }
}
