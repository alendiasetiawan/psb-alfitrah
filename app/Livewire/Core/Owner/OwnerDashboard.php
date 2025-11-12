<?php

namespace App\Livewire\Core\Owner;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\Title;

class OwnerDashboard extends Component
{
    #[Title('Dashboard Owner')]

    public function render(MobileDetect $mobileDetect)
    {
        if ($mobileDetect->isMobile()) {
            return view('livewire.core.owner.owner-dashboard')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true
            ]);
        }

        return view('livewire.core.owner.owner-dashboard')->layout('components.layouts.web.web-app');
    }
}
