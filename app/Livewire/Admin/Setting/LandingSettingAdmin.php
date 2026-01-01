<?php

namespace App\Livewire\Admin\Setting;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pengaturan')]
class LandingSettingAdmin extends Component
{
    public function render()
    {
        return view('livewire.mobile.admin.setting.landing-setting-admin')->layout('components.layouts.mobile.mobile-app', [
            'isShowBottomNavbar' => true,
            'isShowTitle' => true
        ]);
    }
}
