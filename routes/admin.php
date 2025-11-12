<?php

use App\Enums\RoleEnum;
use App\Livewire\Admin\Setting\AdmissionDraft;
use App\Livewire\Admin\Setting\AdmissionDraft\AcademicYear;
use App\Livewire\Admin\Setting\AdmissionDraft\RegistrationFee;
use App\Livewire\Admin\Setting\AdmissionDraft\StudentQuota;
use App\Livewire\Admin\Setting\PlacementTestFormula;
use App\Livewire\Admin\Setting\School\Branch;
use App\Livewire\Admin\Setting\School\Program;
use Illuminate\Support\Facades\Route;
use App\Livewire\Core\Owner\OwnerDashboard;
use App\Livewire\Core\Owner\Management\OwnerAccount;
use App\Livewire\Core\Owner\Management\StoreProfile;
use App\Livewire\Core\Owner\Management\ResellerAccount;

Route::middleware('role:'.RoleEnum::ADMIN.'')->group(function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard', OwnerDashboard::class)->name('dashboard');

        //Setting Page Route
        Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::get('/placement-test-formula', PlacementTestFormula::class)->name('placement_test_formula');
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

        Route::group(['prefix' => 'management', 'as' => 'management.'], function () {
            Route::get('/store-profile', StoreProfile::class)->name('store_profile');
            Route::get('/owner-account', OwnerAccount::class)->name('owner_account');
            Route::get('/reseller-account', ResellerAccount::class)->name('reseller_account');
        });
    });
});