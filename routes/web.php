<?php

use App\Enums\RoleEnum;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Visitor\StudentRegistration\BranchQuota;
use App\Livewire\Visitor\StudentRegistration\RegistrationForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
})->name('home');

//Student Registration Route
Route::get('/kuota-pendaftaran', BranchQuota::class)->name('branch_quota');
Route::get('/form-santri-baru/{branchId}', RegistrationForm::class)->name('registration_form');

Route::middleware('role:'.RoleEnum::OWNER.'')->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/student.php';

