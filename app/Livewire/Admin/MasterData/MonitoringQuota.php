<?php

namespace App\Livewire\Admin\MasterData;

use Detection\MobileDetect;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Monitoring Kuota')]
class MonitoringQuota extends Component
{
    public bool $isMobile = false;

    public function boot(MobileDetect $mobileDetect): void
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function render(): View
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.master-data.monitoring-quota')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'link' => 'admin.dashboard',
            ]);
        }

        return view('livewire.web.admin.master-data.monitoring-quota')->layout('components.layouts.web.web-app');
    }
}
