<?php

namespace App\Livewire\Student\PlacementTest;

use App\Models\PlacementTest\TestQrCodes;
use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Services\StudentDataService;
use App\Queries\AdmissionData\StudentQuery;
use App\Services\AdmissionVerificationService;
#[Title('QR Code Kehadiran Tes')]
class QrPresenceTest extends Component
{
   public bool $isMobile = false, $isShowQr = false, $isAttended = true, $isCanCreateQr = true;
   public int $studentId;
   public $presenceTestQuery;

   //HOOK - Execute once when component is rendered
   public function mount(MobileDetect $mobileDetect, AdmissionVerificationService $admissionVerificationService, StudentDataService $studentDataService) 
   {
      $this->isMobile = $mobileDetect->isMobile();

      //Assign value for base query
      $parentId = session('userData')->parent->id;
      $this->studentId = $studentDataService->findActiveStudentId($parentId);
      $this->presenceTestQuery = StudentQuery::fetchStudentPresenceTest($this->studentId);

      //Check value for conditional rendering
      $this->isCanCreateQr = $admissionVerificationService->isStudentCanCreateQr($this->presenceTestQuery->registration_payment, $this->presenceTestQuery->biodata, $this->presenceTestQuery->attachment);

      //Check is student already attended the test
      $this->isStudentAttended();
   }

   //ACTION - Make temporary QR Code for user to use in certain time
   public function generateQrCode() 
   {
      //Check is student already attended the test
      $this->isStudentAttended();
      if ($this->isAttended) {
         $this->dispatch('hide-qr-code');
         $this->redirect(route('student.placement_test.qr_presence_test'), navigate: true);
      } else {
         TestQrCodes::updateOrCreate([
            'student_id' => $this->studentId
         ], [
            'qr' => substr(md5(time()), 0, 10),
         ]);
   
         $this->dispatch('show-qr-code');
      }
   }

   //ACTION - Check is student already attended the test
   public function isStudentAttended()
   {
      if ($this->presenceTestQuery->placementTestPresence) {
         return $this->isAttended = true;
      }

      return $this->isAttended = false;
   }

   public function render()
   {
      if ($this->isMobile) {
         return view('livewire.mobile.student.placement-test.qr-presence-test')->layout('components.layouts.mobile.mobile-app', [
            'isShowBottomNavbar' => true,
            'isShowTitle' => true
         ]);
      }
      return view('livewire.web.student.placement-test.qr-presence-test')->layout('components.layouts.web.web-app');
   }
}
