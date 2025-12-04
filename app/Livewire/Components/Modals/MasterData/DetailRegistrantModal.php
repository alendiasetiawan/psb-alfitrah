<?php

namespace App\Livewire\Components\Modals\MasterData;

use App\Queries\AdmissionData\StudentQuery;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailRegistrantModal extends Component
{
    public string $modalId;
    public object $detailRegistrantData;
    public bool $isMobile;

    //ANCHOR - SET DETAIL VALUE
    #[On('open-detail-registrant-modal')]
    public function setDetailValue($id)
    {
        try {
            $realId = Crypt::decrypt($id);
            $this->detailRegistrantData = StudentQuery::fetchDetailRegistrant($realId);
        } catch (\Throwable $th) {
            session()->flash('error-id-detail', 'Dilarang modifikasi ID parameter');
        }
    }

    //ANCHOR - Reset property when modal is closed
    public function resetAllProperty()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.components.modals.master-data.detail-registrant-modal');
    }
}
