<?php

namespace App\Livewire\Mobile\Student;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Semua Menu')]
class StudentMegaMenu extends Component
{
    public function render()
    {
        return view('livewire.mobile.student.student-mega-menu')->layout('components.layouts.mobile.mobile-app', [
            'isShowBackButton' => true,
        ]);
    }
}
