<?php

namespace App\Livewire\Student\PlacementTest;

use App\Queries\AdmissionData\StudentQuery;
use App\Services\AdmissionVerificationService;
use App\Services\StudentDataService;
use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\Title;
#[Title('Pengumuman Kelulusan')]
class PrivateAnnouncement extends Component
{
   public bool $isMobile = false, $isCanSeeResult = false;
   public $testResultQuery;

   //HOOK - Execute once when component is rendered
   public function mount(MobileDetect $mobileDetect, StudentDataService $studentDataService, AdmissionVerificationService $admissionVerificationService)
   {
      $this->isMobile = $mobileDetect->isMobile();

      //Assign mandatory parameter
      $parentId = session('userData')->parent->id;
      $this->studentId = $studentDataService->findActiveStudentId($parentId);

      //Set core query
      $this->testResultQuery = StudentQuery::fetchAnnouncementTestResult($this->studentId);
      $this->isCanSeeResult = $admissionVerificationService->isStudentCanCreateQr($this->testResultQuery->registration_payment, $this->testResultQuery->biodata, $this->testResultQuery->attachment);
   }
   
   public function render()
   {
      if ($this->isMobile) {
         return view('livewire.mobile.student.placement-test.private-announcement')->layout('components.layouts.mobile.mobile-app', [
            'isShowBottomNavbar' => true,
            'isShowTitle' => true
         ]);
      }
      return view('livewire.web.student.placement-test.private-announcement')->layout('components.layouts.web.web-app');
   }
}
