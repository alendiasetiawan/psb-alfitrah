<?php

namespace App\Livewire\Components\Buttons;

use Livewire\Component;

class LoadMore extends Component
{
    public $loadItem;

    public function loadMore() {
        $this->dispatch('load-more', $this->loadItem);
    }

    public function render()
    {
        return view('livewire.components.buttons.load-more');
    }
}
