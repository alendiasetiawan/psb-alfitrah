<?php

namespace App\Providers;

use App\Models\AdmissionData\Student;
use Illuminate\Support\ServiceProvider;
use App\Models\AdmissionData\StudentAttachment;
use App\Observers\AdmissionData\StudentObserver;
use App\Models\AdmissionData\AdmissionVerification;
use App\Observers\AdmissionData\StudentAttachmentObserver;
use App\Observers\AdmissionData\AdmissionVerificationObserver;

class AppServiceProvider extends ServiceProvider
{
   /**
    * Register any application services.
    */
   public function register(): void
   {
   }

   /**
    * Bootstrap any application services.
    */
   public function boot(): void {
      Student::observe(StudentObserver::class);
      StudentAttachment::observe(StudentAttachmentObserver::class);
      AdmissionVerification::observe(AdmissionVerificationObserver::class);
   }
}
