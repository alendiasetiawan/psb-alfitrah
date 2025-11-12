<?php

namespace App\Livewire\Components\Inputs;

use Livewire\Component;

class DatePicker extends Component
{
    public $date;

    public function render()
    {
        return view('livewire.components.inputs.date-picker');
    }
}
