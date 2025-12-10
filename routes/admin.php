<?php

use App\Enums\RoleEnum;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\DataVerification\Biodata\Pending\PendingBiodataAdmin;
use App\Livewire\Admin\DataVerification\Biodata\Process\Detail\DetailProcessBiodataAdmin;
use App\Livewire\Admin\DataVerification\Biodata\Process\ProcessBiodataAdmin;
use App\Livewire\Admin\DataVerification\Biodata\Verified\Detail\DetailVerifiedBiodataAdmin;
use App\Livewire\Admin\DataVerification\Biodata\Verified\VerifiedBiodataAdmin;
use App\Livewire\Admin\DataVerification\RegistrationPayment\PaymentPaid;
use App\Livewire\Admin\DataVerification\RegistrationPayment\PaymentProcess;
use App\Livewire\Admin\DataVerification\RegistrationPayment\PaymentUnpaid;
use App\Livewire\Admin\MasterData\MonitoringQuota;
use App\Livewire\Admin\MasterData\RegistrantDatabase;
use App\Livewire\Admin\MasterData\RegistrantDemographic;
use App\Livewire\Admin\MasterData\StudentDatabase\DetailStudentDatabase;
use App\Livewire\Admin\MasterData\StudentDatabase\IndexStudentDatabase;
use App\Livewire\Admin\Setting\AdmissionDraft\AcademicYear;
use App\Livewire\Admin\Setting\AdmissionDraft\RegistrationFee;
use App\Livewire\Admin\Setting\AdmissionDraft\StudentQuota;
use App\Livewire\Admin\Setting\School\Branch;
use App\Livewire\Admin\Setting\School\Program;
use Illuminate\Support\Facades\Route;







Route::middleware('role:' . RoleEnum::ADMIN . '')->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        //ANCHOR - MASTER DATA ROUTE
        Route::group(['prefix' => 'master-data', 'as' => 'master_data.'], function () {
            Route::get('/registrant-database', RegistrantDatabase::class)->name('registrant_database');

            //NOTE - Student Database Route
            Route::group(['prefix' => 'student-database', 'as' => 'student_database.'], function () {
                Route::get('/index', IndexStudentDatabase::class)->name('index');
                Route::get('/detail/{studentId}', DetailStudentDatabase::class)->name('detail');
            });

            Route::get('/registrant-demographic', RegistrantDemographic::class)->name('registrant_demographic');
            Route::get('/monitoring-quota', MonitoringQuota::class)->name('monitoring_quota');
        });

        //ANCHOR - DATA VERIFICATION ROUTE
        Route::group(['prefix' => 'data-verification', 'as' => 'data_verification.'], function () {
            //NOTE - Registration Payment Route
            Route::group(['prefix' => 'registration-payment', 'as' => 'registration_payment.'], function () {
                Route::get('/payment-paid', PaymentPaid::class)->name('payment_paid');
                Route::get('/payment-process', PaymentProcess::class)->name('payment_process');
                Route::get('/payment-unpaid', PaymentUnpaid::class)->name('payment_unpaid');
            });

            //NOTE - Biodata Verification Route
            Route::group(['prefix' => 'biodata', 'as' => 'biodata.'], function () {
                Route::get('/pending', PendingBiodataAdmin::class)->name('pending');

                Route::get('/process', ProcessBiodataAdmin::class)->name('process');
                Route::group(['prefix' => 'process', 'as' => 'process.'], function () {
                    Route::get('/detail/{studentId}', DetailProcessBiodataAdmin::class)->name('detail');
                });

                Route::get('/verified', VerifiedBiodataAdmin::class)->name('verified');
                Route::group(['prefix' => 'verified', 'as' => 'verified.'], function () {
                    Route::get('/detail/{studentId}', DetailVerifiedBiodataAdmin::class)->name('detail');
                });
            });
        });

        //ANCHOR - Setting Page Route
        Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::group(['prefix' => 'admission-draft', 'as' => 'admission_draft.'], function () {
                Route::get('/academic-year', AcademicYear::class)->name('academic_year');
                Route::get('/student-quota', StudentQuota::class)->name('student_quota');
                Route::get('/registration-fee', RegistrationFee::class)->name('registration_fee');
            });
            Route::group(['prefix' => 'school', 'as' => 'school.'], function () {
                Route::get('/branch', Branch::class)->name('branch');
                Route::get('/program', Program::class)->name('program');
            });
        });
    });
});
