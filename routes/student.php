<?php

use App\Enums\RoleEnum;
use App\Livewire\Mobile\Student\StudentMegaMenu;
use Illuminate\Support\Facades\Route;
use App\Livewire\Student\AdmissionData\Biodata;
use App\Livewire\Student\PlacementTest\QrPresenceTest;
use App\Livewire\Student\PlacementTest\PublicAnnouncement;
use App\Livewire\Student\AdmissionData\AdmissionAttachment;
use App\Livewire\Student\AdmissionData\RegistrationPayment;
use App\Livewire\Student\PlacementTest\FinalRegistration;
use App\Livewire\Student\PlacementTest\PrivateAnnouncement;
use App\Livewire\Student\StudentDashboard;

Route::middleware('role:' . RoleEnum::STUDENT . '')->group(function () {
    Route::group(['prefix' => 'student', 'as' => 'student.'], function () {
        Route::get('/dashboard', StudentDashboard::class)->name('student_dashboard');
        Route::get('/mega-menu', StudentMegaMenu::class)->name('student_mega_menu');

        //Student Payment Paage Routes
        Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
            Route::get('/registration-payment', RegistrationPayment::class)->name('registration_payment');
        });

        //Admission Data Page Route
        Route::group(['prefix' => 'admission-data', 'as' => 'admission_data.'], function () {
            Route::get('/biodata', Biodata::class)->name('biodata');
            Route::get('/admission-attachment', AdmissionAttachment::class)->name('admission_attachment');
        });

        //Placement Test Page Route
        Route::group(['prefix' => 'placement-test', 'as' => 'placement_test.'], function () {
            Route::get('/qr-presence-test', QrPresenceTest::class)->name('qr_presence_test');
            Route::group(['prefix' => 'test-result', 'as' => 'test_result.'], function () {
                Route::get('/private-announcement', PrivateAnnouncement::class)->name('private_announcement');
                Route::get('/public-announcement', PublicAnnouncement::class)->name('public_announcement');
                Route::get('/final-registration', FinalRegistration::class)->name('final_registration');
            });
        });
    });
});
