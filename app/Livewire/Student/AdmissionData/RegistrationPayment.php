<?php

namespace App\Livewire\Student\AdmissionData;

use Detection\MobileDetect;
use Livewire\Component;

class RegistrationPayment extends Component
{
    public bool $isMobile;

    //HOOK - Execute once when component is rendered
    public function mount(MobileDetect $mobileDetect) {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.student.admission-data.registration-payment')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);;
        }
        return view('livewire.web.student.admission-data.registration-payment');
    }
}
