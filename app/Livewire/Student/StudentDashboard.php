<?php

namespace App\Livewire\Student;

use App\Queries\AdmissionData\StudentQuery;
use Detection\MobileDetect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard Siswa')]
class StudentDashboard extends Component
{
    public object $studentQuery;

    // HOOK - Execute once when component is rendered
    public function mount()
    {
        $this->studentQuery = StudentQuery::fetchAnnouncementTestResult(session('userData')->multiStudent->student_id);
    }

    public function render(MobileDetect $mobileDetect)
    {
        if ($mobileDetect->isMobile()) {
            return view('livewire.mobile.student.student-dashboard')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => false,
            ]);
        }

        return view('livewire.web.student.student-dashboard')->layout('components.layouts.web.web-app');
    }
}
