<?php

namespace Tests\Support;

use App\Enums\RoleEnum;
use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\MultiStudent;
use App\Models\AdmissionData\ParentModel;
use App\Models\AdmissionData\RegistrationPayment;
use App\Models\AdmissionData\Student;
use App\Models\Core\Admission;
use App\Models\Core\Branch;
use App\Models\Core\EducationProgram;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

trait InteractsWithRegistrationPayments
{
    protected User $studentUser;

    protected Student $student;

    protected RegistrationPayment $payment;

    protected AdmissionVerification $verification;

    protected Admission $admission;

    protected function initializeRegistrationPayments(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'database.connections.sqlite.foreign_key_constraints' => false]);
        DB::purge('sqlite');
        $this->createPaymentSchema();
        (require database_path('migrations/2026_10_04_000000_create_payment_accounts_table.php'))->up();
        Storage::fake('public');

        $this->studentUser = $this->createPaymentUser(RoleEnum::STUDENT);
        $parent = ParentModel::create(['user_id' => $this->studentUser->id]);
        $this->admission = Admission::create(['name' => '2026/2027', 'status' => 'Buka']);
        $branch = Branch::create(['name' => 'Cabang Test']);
        $program = EducationProgram::create(['name' => 'Program Test', 'branch_id' => $branch->id]);
        $this->student = Student::create([
            'name' => 'Siswa Test', 'user_id' => $this->studentUser->id, 'parent_id' => $parent->id,
            'admission_id' => $this->admission->id, 'branch_id' => $branch->id, 'education_program_id' => $program->id,
        ]);
        MultiStudent::create(['user_id' => $this->studentUser->id, 'parent_id' => $parent->id, 'student_id' => $this->student->id]);
        $this->payment = RegistrationPayment::create(['student_id' => $this->student->id, 'amount' => 350000, 'payment_status' => VerificationStatusEnum::NOT_STARTED]);
        $this->verification = AdmissionVerification::create(['student_id' => $this->student->id, 'registration_payment' => VerificationStatusEnum::NOT_STARTED]);
        $this->signInForPayment($this->studentUser);
    }

    /**
     * The legacy MySQL migrations have duplicate primary keys and jobs tables;
     * isolate payment tests from those unrelated migration failures.
     */
    protected function createPaymentSchema(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->integer('role_id');
            $table->string('username');
            $table->string('fullname');
            $table->string('photo')->nullable();
            $table->string('password');
            $table->timestamps();
        });
        Schema::create('parents', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
        Schema::create('admissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('branches', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('mobile_phone')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('education_programs', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('branch_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('parent_id');
            $table->bigInteger('admission_id');
            $table->bigInteger('branch_id');
            $table->bigInteger('education_program_id');
            $table->string('name');
            $table->string('gender')->nullable();
            $table->string('country_code')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->timestamps();
        });
        Schema::create('multi_students', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('parent_id');
            $table->bigInteger('student_id');
            $table->timestamps();
        });
        Schema::create('registration_payments', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('student_id');
            $table->double('amount');
            $table->string('evidence')->nullable();
            $table->enum('payment_status', ['Proses', 'Belum', 'Valid', 'Tidak Valid', 'Expired'])->default('Belum');
            $table->timestamps();
        });
        Schema::create('admission_verifications', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('student_id');
            $table->enum('registration_payment', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->default('Belum');
            foreach (['biodata', 'attachment', 'placement_test', 'fu_payment', 'fu_biodata', 'fu_attachment', 'fu_placement_test', 'payment_error_msg', 'biodata_error_msg', 'attachment_error_msg'] as $field) {
                $table->string($field)->nullable();
            }
            $table->timestamps();
        });
        Schema::create('registration_invoices', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('student_id');
            $table->double('amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    protected function createPaymentUser(int $roleId): User
    {
        return User::factory()->afterMaking(function (User $user): void {
            unset($user->token);
        })->create(['role_id' => $roleId]);
    }

    protected function signInForPayment(User $user): void
    {
        $this->actingAs($user);
        session(['userCheck' => true, 'userData' => $user]);
    }
}
