<?php

namespace App\Livewire\Admin;

use Detection\MobileDetect;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Dashboard Admin PSB')]
class AdminDashboard extends Component
{
    public bool $isMobile = false;

    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.admin-dashboard')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => false
            ]);
        }

        return view('livewire.web.admin.admin-dashboard')->layout('components.layouts.web.web-app');
    }
}
