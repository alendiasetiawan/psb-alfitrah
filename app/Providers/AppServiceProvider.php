<?php

namespace App\Providers;

use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\RegistrationPayment;
use App\Models\AdmissionData\Student;
use App\Models\AdmissionData\StudentAttachment;
use App\Models\Core\Branch;
use App\Models\PlacementTest\PlacementTestResult;
use App\Models\User;
use App\Observers\AdmissionData\AdmissionVerificationObserver;
use App\Observers\AdmissionData\StudentAttachmentObserver;
use App\Observers\AdmissionData\StudentObserver;
use App\Observers\Core\BranchObserver;
use App\Observers\Core\UserObserver;
use App\Observers\Payments\RegistrationPaymentObserver;
use App\Observers\PlacementTest\PlacementTestResultObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   /**
    * Register any application services.
    */
   public function register(): void {}

   /**
    * Bootstrap any application services.
    */
   public function boot(): void
   {
      User::observe(UserObserver::class);
      Student::observe(StudentObserver::class);
      StudentAttachment::observe(StudentAttachmentObserver::class);
      AdmissionVerification::observe(AdmissionVerificationObserver::class);
      Branch::observe(BranchObserver::class);
      RegistrationPayment::observe(RegistrationPaymentObserver::class);
      PlacementTestResult::observe(PlacementTestResultObserver::class);
   }
}
