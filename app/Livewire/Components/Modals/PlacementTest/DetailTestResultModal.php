<?php

namespace App\Livewire\Components\Modals\PlacementTest;

use App\Queries\PlacementTest\PlacementTestResultQuery;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailTestResultModal extends Component
{
    public string $modalId;
    public ?object $testQuery;
    public bool $showDetailTestModal;
    public bool $isMobile;

    #[On('open-detail-test-result-modal')]
    public function setDetailValue($id)
    {
        try {
            $studentId = Crypt::decrypt($id);
            $this->testQuery = PlacementTestResultQuery::fetchStudentDetailTest($studentId);
            $this->showDetailTestModal = true;
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-open-modal', 'Gagal mengambil data detail test, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.placement-test.detail-test-result-modal');
    }
}
