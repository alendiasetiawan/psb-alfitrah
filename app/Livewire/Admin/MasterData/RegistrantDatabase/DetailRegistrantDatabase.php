<?php

namespace App\Livewire\Admin\MasterData\RegistrantDatabase;

use Detection\MobileDetect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Profil Pendaftar')]
class DetailRegistrantDatabase extends Component
{
    public bool $isMobile = false;

    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.master-data.registrant-database.detail-registrant-database')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'isShowTitle' => true
            ]);
        }

        return view('livewire.web.admin.master-data.registrant-database.detail-registrant-database')->layout('components.layouts.web.web-app');
    }
}
