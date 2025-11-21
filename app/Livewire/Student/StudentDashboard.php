<?php

namespace App\Livewire\Student;

use Detection\MobileDetect;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Dashboard Siswa')]
class StudentDashboard extends Component
{
   public function render(MobileDetect $mobileDetect)
   {
      if ($mobileDetect->isMobile()) {
         return view('livewire.mobile.student.student-dashboard')->layout('components.layouts.mobile.mobile-app', [
            'isShowBottomNavbar' => true,
            'isShowTitle' => false
         ]);
      }
      return view('livewire.web.student.student-dashboard')->layout('components.layouts.web.web-app');
   }
}
