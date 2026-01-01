<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Semua Menu')]
class MegaMenuMobileAdmin extends Component
{
    public function render()
    {
        return view('livewire.mobile.admin.mega-menu-mobile-admin')->layout('components.layouts.mobile.mobile-app', [
            'isShowBackButton' => true,
            'link' => 'admin.dashboard'
        ]);
    }
}
