<?php

namespace App\Livewire\Student\PlacementTest;

use Livewire\Component;
use Detection\MobileDetect;
use App\Helpers\MessageHelper;
use App\Services\StudentDataService;
use App\Queries\AdmissionData\StudentQuery;

class FinalRegistration extends Component
{
   public bool $isMobile = false;
   public int $studentId;
   public string $studentName, $branchName, $programName, $admissionYear;
   public $studentQuery;

   //HOOK - Execute once when component is rendered
   public function mount(MobileDetect $mobileDetect, StudentDataService $studentDataService) {
      $this->isMobile = $mobileDetect->isMobile();
      $parentId = session('userData')->parent->id;
      $this->studentId = $studentDataService->findActiveStudentId($parentId);
      $this->studentQuery = StudentQuery::fetchAnnouncementTestResult($this->studentId);
      $this->studentName = $this->studentQuery->student_name;
      $this->branchName = $this->studentQuery->branch_name;
      $this->programName = $this->studentQuery->program_name;
      $this->admissionYear = $this->studentQuery->academic_year;
   }

   //ACTION - Send direct chat WA for final registration confirmation
   public function chatAdminFinalRegistration() {
      $params = [
         'studentName' => $this->studentName,
         'branchName' => $this->branchName,
         'programName' => $this->programName,
         'admissionYear' => $this->admissionYear
      ];

      $this->redirect('https://wa.me/6281319847072?text=' . urlencode(MessageHelper::waProcessFinalRegistraion($this->studentName, $this->branchName, $this->programName, $this->admissionYear)));
   }

   public function render()
   {
      if ($this->isMobile) {
         return view('livewire.mobile.student.placement-test.final-registration')->layout('components.layouts.mobile.mobile-app', [
            'isShowBottomNavbar' => true,
            'isShowTitle' => true
         ]);
      }
      return view('livewire.web.student.placement-test.final-registration')->layout('components.layouts.web.web-app');
   }
}
