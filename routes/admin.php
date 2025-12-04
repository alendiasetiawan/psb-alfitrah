<?php

use App\Enums\RoleEnum;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\MasterData\MonitoringQuota;
use App\Livewire\Admin\MasterData\RegistrantDatabase;
use App\Livewire\Admin\MasterData\RegistrantDatabase\DetailRegistrantDatabase;
use App\Livewire\Admin\MasterData\RegistrantDatabase\IndexRegistrantDatabase;
use App\Livewire\Admin\MasterData\RegistrantDemographic;
use App\Livewire\Admin\MasterData\StudentDatabase;
use App\Livewire\Admin\Setting\AdmissionDraft\AcademicYear;
use App\Livewire\Admin\Setting\AdmissionDraft\RegistrationFee;
use App\Livewire\Admin\Setting\AdmissionDraft\StudentQuota;
use App\Livewire\Admin\Setting\School\Branch;
use App\Livewire\Admin\Setting\School\Program;
use Illuminate\Support\Facades\Route;


Route::middleware('role:' . RoleEnum::ADMIN . '')->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        //ANCHOR - Master Data Route
        Route::group(['prefix' => 'master-data', 'as' => 'master_data.'], function () {
            Route::get('/registrant-database', RegistrantDatabase::class)->name('registrant_database');
            //ANCHOR - Registrant Database Route
            Route::group(['prefix' => 'registrant-database', 'as' => 'registrant_database.'], function () {
                Route::get('/detail', DetailRegistrantDatabase::class)->name('detail');
            });

            Route::get('/student-database', StudentDatabase::class)->name('student_database');
            Route::get('/registrant-demographic', RegistrantDemographic::class)->name('registrant_demographic');
            Route::get('/monitoring-quota', MonitoringQuota::class)->name('monitoring_quota');
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
