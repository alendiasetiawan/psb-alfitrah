<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admission_batches', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('admission_id')->nullable()->index('admission_id');
            $table->string('name', 50)->nullable();
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->softDeletes();
        });

        Schema::create('admission_fees', function (Blueprint $table) {
            $table->integer('id', true)->unique('admission_fees_index_22');
            $table->integer('admission_id')->nullable()->index('admission_fees_index_23');
            $table->integer('education_program_id')->nullable()->index('admission_fees_index_24');
            $table->double('registration_fee')->nullable();
            $table->double('internal_registration_fee')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->index(['admission_id', 'education_program_id'], 'admission_fees_index_25');
            $table->primary(['id']);
        });

        Schema::create('admission_quotas', function (Blueprint $table) {
            $table->integer('id', true)->unique('admission_quota_index_18');
            $table->integer('admission_id')->nullable()->index('admission_quota_index_19');
            $table->integer('education_program_id')->nullable()->index('admission_quota_index_20');
            $table->integer('amount')->nullable();
            $table->enum('status', ['Buka', 'Tutup'])->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->index(['admission_id', 'education_program_id'], 'admission_quota_index_21');
            $table->primary(['id']);
        });

        Schema::create('admission_verifications', function (Blueprint $table) {
            $table->bigInteger('id', true)->unique('admission_verifications_index_36');
            $table->bigInteger('student_id')->nullable()->index('admission_verifications_index_37');
            $table->enum('registration_payment', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->nullable()->default('Belum')->index('admission_verifications_index_38');
            $table->enum('biodata', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->nullable()->default('Belum')->index('admission_verifications_index_39');
            $table->enum('attachment', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->nullable()->default('Belum')->index('admission_verifications_index_40');
            $table->enum('placement_test', ['Belum', 'Sudah', 'Tidak Hadir'])->nullable()->default('Belum')->index('admission_verifications_index_41');
            $table->string('payment_error_msg', 500)->nullable();
            $table->string('biodata_error_msg', 500)->nullable();
            $table->string('attachment_error_msg', 500)->nullable();
            $table->enum('fu_payment', ['Belum', 'Sudah'])->nullable()->default('Belum');
            $table->enum('fu_biodata', ['Belum', 'Sudah'])->nullable()->default('Belum');
            $table->enum('fu_attachment', ['Belum', 'Sudah'])->nullable()->default('Belum');
            $table->enum('fu_placement_test', ['Belum', 'Sudah'])->nullable()->default('Belum');
            $table->timestamp('biodata_verified_at')->nullable();
            $table->timestamp('attachment_verified_at')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->primary(['id']);
        });

        Schema::create('admissions', function (Blueprint $table) {
            $table->integer('id', true)->unique('admissions_index_17');
            $table->string('name', 15)->nullable();
            $table->enum('status', ['Buka', 'Tutup'])->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->softDeletes();

            $table->primary(['id']);
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->integer('id', true)->unique('branches_index_12');
            $table->string('name', 100)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('mobile_phone', 20)->nullable();
            $table->string('map_link')->nullable();
            $table->string('photo')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->softDeletes();

            $table->primary(['id']);
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('country_codes', function (Blueprint $table) {
            $table->integer('id', true)->unique('country_codes_index_15');
            $table->string('name', 50)->nullable();
            $table->integer('code')->nullable();

            $table->primary(['id']);
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->char('id', 7)->nullable()->unique('districts_index_7');
            $table->char('regency_id', 4)->nullable()->index('districts_index_8');
            $table->string('name', 50)->nullable();
        });

        Schema::create('education_programs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('branch_id')->nullable()->index('branch_id');
            $table->string('name', 50)->nullable();
            $table->string('description')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->softDeletes();
        });

        Schema::create('invoice_logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('external_id', 100)->nullable();
            $table->longText('payload')->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->integer('id', true)->unique('jobs_index_13');
            $table->string('name', 50)->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->primary(['id']);
        });

        Schema::create('last_educations', function (Blueprint $table) {
            $table->integer('id', true)->unique('last_educations_index_14');
            $table->string('name', 50)->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->primary(['id']);
        });

        Schema::create('multi_students', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('user_id')->nullable()->index('user_id');
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('student_id')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });

        Schema::create('parents', function (Blueprint $table) {
            $table->bigInteger('id', true)->unique('parents_index_33');
            $table->bigInteger('user_id')->nullable()->index('user_id');
            $table->boolean('is_parent')->nullable()->comment('Apakah wali atau orang tua?');
            $table->string('father_name', 100)->nullable();
            $table->string('father_birth_place', 50)->nullable();
            $table->date('father_birth_date')->nullable();
            $table->string('father_address', 500)->nullable();
            $table->integer('father_country_code')->nullable();
            $table->string('father_mobile_phone', 15)->nullable();
            $table->integer('father_last_education_id')->nullable()->index('father_last_education_id');
            $table->integer('father_job_id')->nullable()->index('father_job_id');
            $table->integer('father_sallary_id')->nullable()->index('father_sallary_id');
            $table->string('mother_name', 100)->nullable();
            $table->string('mother_birth_place', 50)->nullable();
            $table->date('mother_birth_date')->nullable();
            $table->string('mother_address', 500)->nullable();
            $table->integer('mother_country_code')->nullable();
            $table->string('mother_mobile_phone', 15)->nullable();
            $table->integer('mother_last_education_id')->nullable()->index('mother_last_education_id');
            $table->integer('mother_job_id')->nullable()->index('mother_job_id');
            $table->integer('mother_sallary_id')->nullable()->index('mother_sallary_id');
            $table->string('guardian_name', 100)->nullable();
            $table->string('guardian_birth_place', 50)->nullable();
            $table->date('guardian_birth_date')->nullable();
            $table->string('guardian_address', 500)->nullable();
            $table->integer('guardian_country_code')->nullable();
            $table->string('guardian_mobile_phone', 15)->nullable();
            $table->integer('guardian_last_education_id')->nullable();
            $table->integer('guardian_job_id')->nullable();
            $table->integer('guardian_sallary_id')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->primary(['id']);
        });

        Schema::create('placement_test_presences', function (Blueprint $table) {
            $table->bigInteger('id', true)->unique('placement_test_presences_index_52');
            $table->bigInteger('student_id')->nullable()->index('placement_test_presences_index_53');
            $table->dateTime('check_in_time')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->primary(['id']);
        });

        Schema::create('placement_test_results', function (Blueprint $table) {
            $table->bigInteger('id', true)->unique('placement_test_results_index_54');
            $table->bigInteger('student_id')->nullable()->index('placement_test_results_index_55');
            $table->double('psikotest_score')->nullable();
            $table->string('psikotest_note', 500)->nullable();
            $table->double('read_quran_score')->nullable();
            $table->integer('read_quran_tester')->nullable()->index('read_quran_tester');
            $table->string('read_quran_note', 500)->nullable();
            $table->enum('parent_interview', ['Direkomendasikan', 'Dipertimbangkan', 'Tidak Direkomendasikan'])->nullable();
            $table->integer('parent_interview_tester')->nullable()->index('parent_interview_tester');
            $table->string('parent_interview_note', 500)->nullable();
            $table->enum('student_interview', ['Direkomendasikan', 'Dipertimbangkan', 'Tidak Direkomendasikan'])->nullable();
            $table->integer('student_interview_tester')->nullable()->index('student_interview_tester');
            $table->string('student_interview_note', 500)->nullable();
            $table->double('final_score')->nullable();
            $table->enum('final_result', ['Menunggu', 'Lulus', 'Tidak Lulus'])->nullable()->default('Menunggu')->index('placement_test_results_index_57');
            $table->string('final_note', 500)->nullable();
            $table->enum('publication_status', ['Hold', 'Release'])->nullable()->default('Hold')->index('placement_test_results_index_56');
            $table->dateTime('publication_date')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->primary(['id']);
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->char('id', 2)->nullable()->unique('provinces_index_4');
            $table->string('name', 50)->nullable();
        });

        Schema::create('regencies', function (Blueprint $table) {
            $table->char('id', 4)->nullable()->unique('regencies_index_5');
            $table->char('province_id', 2)->nullable()->index('regencies_index_6');
            $table->string('name', 50)->nullable();
        });

        Schema::create('registration_invoices', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('username', 50)->nullable();
            $table->bigInteger('student_id')->nullable()->index('registration_invoices_index_47');
            $table->string('invoice_id')->nullable()->index('registration_invoices_index_48')->comment('Kode unik transaksi xendit');
            $table->string('external_id', 100)->nullable();
            $table->double('amount')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->text('payment_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->longText('raw_callback')->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->enum('status', ['Pending', 'Paid', 'Expired', 'Failed'])->nullable()->default('Pending');
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->unique(['id'], 'registration_invoices_index_46');
        });

        Schema::create('registration_payments', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('student_id')->nullable()->index('registration_payments_index_43');
            $table->double('amount')->nullable();
            $table->string('evidence')->nullable();
            $table->enum('payment_status', ['Proses', 'Belum', 'Valid', 'Tidak Valid', 'Expired'])->nullable()->default('Belum')->index('registration_payments_index_44');
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->unique(['id'], 'registration_payments_index_42');
            $table->index(['student_id', 'payment_status'], 'registration_payments_index_45');
        });

        Schema::create('request_phone_changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->string('new_country_code', 5);
            $table->string('new_phone', 15);
            $table->string('otp', 6);
            $table->timestamp('otp_expired_at');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['id']);
            $table->index(['otp', 'is_verified']);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50)->nullable();
            $table->string('description')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->softDeletes();

            $table->unique(['id'], 'roles_index_11');
        });

        Schema::create('sallaries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->unique(['id'], 'sallaries_index_16');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('student_attachments', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('student_id')->nullable()->index('student_attachments_index_35');
            $table->string('photo')->nullable();
            $table->string('parent_card')->nullable();
            $table->string('family_card')->nullable();
            $table->string('born_card')->nullable();
            $table->enum('photo_status', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->nullable();
            $table->enum('parent_card_status', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->nullable();
            $table->enum('family_card_status', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->nullable();
            $table->enum('born_card_status', ['Valid', 'Tidak Valid', 'Proses', 'Belum'])->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->timestamp('modified_at')->nullable();

            $table->unique(['id'], 'student_attachments_index_34');
        });

        Schema::create('students', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('user_id')->nullable()->index('user_id');
            $table->bigInteger('parent_id')->nullable()->index('parent_id');
            $table->integer('branch_id')->nullable()->index('students_index_28');
            $table->integer('education_program_id')->nullable()->index('education_program_id');
            $table->integer('admission_id')->nullable()->index('students_index_29');
            $table->integer('admission_batch_id')->nullable()->index('admission_batch_id');
            $table->char('province_id', 2)->nullable()->index('province_id');
            $table->char('regency_id', 4)->nullable()->index('regency_id');
            $table->char('district_id', 7)->nullable()->index('district_id');
            $table->char('village_id', 10)->nullable()->index('village_id');
            $table->string('reg_number', 20)->nullable();
            $table->string('name', 100)->nullable();
            $table->enum('gender', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->text('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('address', 500)->nullable();
            $table->integer('country_code')->nullable();
            $table->string('mobile_phone', 15)->nullable();
            $table->string('nisn', 30)->nullable();
            $table->string('old_school_name', 50)->nullable();
            $table->string('old_school_address', 500)->nullable();
            $table->string('npsn', 30)->nullable();
            $table->boolean('is_scholarship')->nullable()->default(false);
            $table->boolean('is_walkout')->nullable()->default(false);
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->timestamp('modified_at')->nullable();

            $table->unique(['id'], 'students_index_26');
            $table->index(['branch_id', 'education_program_id'], 'students_index_30');
            $table->index(['admission_id', 'branch_id'], 'students_index_31');
            $table->index(['admission_id', 'branch_id', 'education_program_id'], 'students_index_32');
        });

        Schema::create('test_pass_scores', function (Blueprint $table) {
            $table->integer('id', true);
            $table->double('min_final_score')->nullable()->comment('Batas Minimal Nilai Untuk LULUS');
            $table->integer('psikotest_weight')->nullable()->comment('Persentase');
            $table->integer('read_quran_weight')->nullable()->comment('Persentase');
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
        });

        Schema::create('test_qr_codes', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('student_id')->nullable()->index('test_qr_codes_index_50');
            $table->string('qr')->nullable()->unique('test_qr_codes_index_51');
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
        });

        Schema::create('testers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50)->nullable();
            $table->enum('gender', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('role_id')->nullable()->index('users_index_1');
            $table->string('username', 50)->nullable();
            $table->string('password')->nullable();
            $table->string('fullname', 50)->nullable();
            $table->enum('gender', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->string('photo')->nullable();
            $table->string('otp', 50)->nullable();
            $table->timestamp('otp_expired_at')->nullable();
            $table->boolean('is_verified')->nullable()->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->nullable()->default('now()');

            $table->unique(['id'], 'users_index_0');
            $table->index(['username', 'password'], 'users_index_2');
            $table->index(['otp', 'is_verified'], 'users_index_3');
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->char('id', 10)->nullable()->unique('villages_index_9');
            $table->char('district_id', 7)->nullable()->index('villages_index_10');
            $table->string('name', 50)->nullable();
        });

        Schema::create('walkout_students', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('student_id')->nullable()->index('student_id');
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::table('admission_batches', function (Blueprint $table) {
            $table->foreign(['admission_id'], 'admission_batches_ibfk_1')->references(['id'])->on('admissions')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('admission_fees', function (Blueprint $table) {
            $table->foreign(['admission_id'], 'admission_fees_ibfk_1')->references(['id'])->on('admissions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['education_program_id'], 'admission_fees_ibfk_2')->references(['id'])->on('education_programs')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('admission_quotas', function (Blueprint $table) {
            $table->foreign(['admission_id'], 'admission_quotas_ibfk_1')->references(['id'])->on('admissions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['education_program_id'], 'admission_quotas_ibfk_2')->references(['id'])->on('education_programs')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('admission_verifications', function (Blueprint $table) {
            $table->foreign(['student_id'], 'admission_verifications_ibfk_1')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->foreign(['regency_id'], 'districts_ibfk_1')->references(['id'])->on('regencies')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('education_programs', function (Blueprint $table) {
            $table->foreign(['branch_id'], 'education_programs_ibfk_1')->references(['id'])->on('branches')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('multi_students', function (Blueprint $table) {
            $table->foreign(['user_id'], 'multi_students_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->foreign(['father_last_education_id'], 'parents_ibfk_1')->references(['id'])->on('last_educations')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['father_job_id'], 'parents_ibfk_2')->references(['id'])->on('jobs')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['father_sallary_id'], 'parents_ibfk_3')->references(['id'])->on('sallaries')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['mother_last_education_id'], 'parents_ibfk_4')->references(['id'])->on('last_educations')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['mother_job_id'], 'parents_ibfk_5')->references(['id'])->on('jobs')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['mother_sallary_id'], 'parents_ibfk_6')->references(['id'])->on('sallaries')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'], 'parents_ibfk_7')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('placement_test_presences', function (Blueprint $table) {
            $table->foreign(['student_id'], 'placement_test_presences_ibfk_1')->references(['id'])->on('students')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('placement_test_results', function (Blueprint $table) {
            $table->foreign(['student_id'], 'placement_test_results_ibfk_1')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['read_quran_tester'], 'placement_test_results_ibfk_2')->references(['id'])->on('testers')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['parent_interview_tester'], 'placement_test_results_ibfk_3')->references(['id'])->on('testers')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['student_interview_tester'], 'placement_test_results_ibfk_4')->references(['id'])->on('testers')->onUpdate('no action')->onDelete('set null');
        });

        Schema::table('regencies', function (Blueprint $table) {
            $table->foreign(['province_id'], 'regencies_ibfk_1')->references(['id'])->on('provinces')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('registration_invoices', function (Blueprint $table) {
            $table->foreign(['student_id'], 'registration_invoices_ibfk_1')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });

        Schema::table('registration_payments', function (Blueprint $table) {
            $table->foreign(['student_id'], 'registration_payments_ibfk_1')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });

        Schema::table('request_phone_changes', function (Blueprint $table) {
            $table->foreign(['user_id'], 'request_phone_changes_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('student_attachments', function (Blueprint $table) {
            $table->foreign(['student_id'], 'student_attachments_ibfk_1')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'students_ibfk_10')->references(['id'])->on('parents')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'students_ibfk_11')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['branch_id'], 'students_ibfk_2')->references(['id'])->on('branches')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['education_program_id'], 'students_ibfk_3')->references(['id'])->on('education_programs')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['admission_id'], 'students_ibfk_4')->references(['id'])->on('admissions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['admission_batch_id'], 'students_ibfk_5')->references(['id'])->on('admission_batches')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['province_id'], 'students_ibfk_6')->references(['id'])->on('provinces')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['regency_id'], 'students_ibfk_7')->references(['id'])->on('regencies')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['district_id'], 'students_ibfk_8')->references(['id'])->on('districts')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['village_id'], 'students_ibfk_9')->references(['id'])->on('villages')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('test_qr_codes', function (Blueprint $table) {
            $table->foreign(['student_id'], 'test_qr_codes_ibfk_1')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['role_id'], 'users_ibfk_1')->references(['id'])->on('roles')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->foreign(['district_id'], 'villages_ibfk_1')->references(['id'])->on('districts')->onUpdate('no action')->onDelete('no action');
        });

        Schema::table('walkout_students', function (Blueprint $table) {
            $table->foreign(['student_id'], 'walkout_students_ibfk_1')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('walkout_students', function (Blueprint $table) {
            $table->dropForeign('walkout_students_ibfk_1');
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->dropForeign('villages_ibfk_1');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_ibfk_1');
        });

        Schema::table('test_qr_codes', function (Blueprint $table) {
            $table->dropForeign('test_qr_codes_ibfk_1');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign('students_ibfk_10');
            $table->dropForeign('students_ibfk_11');
            $table->dropForeign('students_ibfk_2');
            $table->dropForeign('students_ibfk_3');
            $table->dropForeign('students_ibfk_4');
            $table->dropForeign('students_ibfk_5');
            $table->dropForeign('students_ibfk_6');
            $table->dropForeign('students_ibfk_7');
            $table->dropForeign('students_ibfk_8');
            $table->dropForeign('students_ibfk_9');
        });

        Schema::table('student_attachments', function (Blueprint $table) {
            $table->dropForeign('student_attachments_ibfk_1');
        });

        Schema::table('request_phone_changes', function (Blueprint $table) {
            $table->dropForeign('request_phone_changes_ibfk_1');
        });

        Schema::table('registration_payments', function (Blueprint $table) {
            $table->dropForeign('registration_payments_ibfk_1');
        });

        Schema::table('registration_invoices', function (Blueprint $table) {
            $table->dropForeign('registration_invoices_ibfk_1');
        });

        Schema::table('regencies', function (Blueprint $table) {
            $table->dropForeign('regencies_ibfk_1');
        });

        Schema::table('placement_test_results', function (Blueprint $table) {
            $table->dropForeign('placement_test_results_ibfk_1');
            $table->dropForeign('placement_test_results_ibfk_2');
            $table->dropForeign('placement_test_results_ibfk_3');
            $table->dropForeign('placement_test_results_ibfk_4');
        });

        Schema::table('placement_test_presences', function (Blueprint $table) {
            $table->dropForeign('placement_test_presences_ibfk_1');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->dropForeign('parents_ibfk_1');
            $table->dropForeign('parents_ibfk_2');
            $table->dropForeign('parents_ibfk_3');
            $table->dropForeign('parents_ibfk_4');
            $table->dropForeign('parents_ibfk_5');
            $table->dropForeign('parents_ibfk_6');
            $table->dropForeign('parents_ibfk_7');
        });

        Schema::table('multi_students', function (Blueprint $table) {
            $table->dropForeign('multi_students_ibfk_1');
        });

        Schema::table('education_programs', function (Blueprint $table) {
            $table->dropForeign('education_programs_ibfk_1');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropForeign('districts_ibfk_1');
        });

        Schema::table('admission_verifications', function (Blueprint $table) {
            $table->dropForeign('admission_verifications_ibfk_1');
        });

        Schema::table('admission_quotas', function (Blueprint $table) {
            $table->dropForeign('admission_quotas_ibfk_1');
            $table->dropForeign('admission_quotas_ibfk_2');
        });

        Schema::table('admission_fees', function (Blueprint $table) {
            $table->dropForeign('admission_fees_ibfk_1');
            $table->dropForeign('admission_fees_ibfk_2');
        });

        Schema::table('admission_batches', function (Blueprint $table) {
            $table->dropForeign('admission_batches_ibfk_1');
        });

        Schema::dropIfExists('walkout_students');

        Schema::dropIfExists('villages');

        Schema::dropIfExists('users');

        Schema::dropIfExists('testers');

        Schema::dropIfExists('test_qr_codes');

        Schema::dropIfExists('test_pass_scores');

        Schema::dropIfExists('students');

        Schema::dropIfExists('student_attachments');

        Schema::dropIfExists('sessions');

        Schema::dropIfExists('sallaries');

        Schema::dropIfExists('roles');

        Schema::dropIfExists('request_phone_changes');

        Schema::dropIfExists('registration_payments');

        Schema::dropIfExists('registration_invoices');

        Schema::dropIfExists('regencies');

        Schema::dropIfExists('provinces');

        Schema::dropIfExists('placement_test_results');

        Schema::dropIfExists('placement_test_presences');

        Schema::dropIfExists('parents');

        Schema::dropIfExists('multi_students');

        Schema::dropIfExists('last_educations');

        Schema::dropIfExists('jobs');

        Schema::dropIfExists('invoice_logs');

        Schema::dropIfExists('education_programs');

        Schema::dropIfExists('districts');

        Schema::dropIfExists('country_codes');

        Schema::dropIfExists('cache_locks');

        Schema::dropIfExists('cache');

        Schema::dropIfExists('branches');

        Schema::dropIfExists('admissions');

        Schema::dropIfExists('admission_verifications');

        Schema::dropIfExists('admission_quotas');

        Schema::dropIfExists('admission_fees');

        Schema::dropIfExists('admission_batches');
    }
};
