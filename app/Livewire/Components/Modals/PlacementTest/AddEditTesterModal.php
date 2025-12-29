<?php

namespace App\Livewire\Components\Modals\PlacementTest;

use App\Models\PlacementTest\Tester;
use Livewire\Attributes\On;
use Livewire\Component;

class AddEditTesterModal extends Component
{
    public string $modalId;
    public bool $isMobile;
    public array $testers = [];
    public array $inputs = [
        'testerName' => '',
        'gender' => ''
    ];
    public array $editTesterName = [];
    public array $editTesterGender = [];

    #[On('open-add-edit-tester-modal')]
    public function openModal()
    {
        $this->getTesters();
    }

    public function saveTester()
    {
        try {
            Tester::create([
                'name' => $this->inputs['testerName'],
                'gender' => $this->inputs['gender']
            ]);

            $this->dispatch('tester-saved');
            $this->getTesters();
            $this->reset('inputs');
        } catch (\Throwable $th) {
            session()->flash('error-save-tester', 'Upss.. Gagal menyimpan data penguji');
        }
    }

    public function saveEditTester($id)
    {
        try {
            Tester::where('id', $id)->update([
                'name' => $this->editTesterName[$id],
                'gender' => $this->editTesterGender[$id]
            ]);

            $this->dispatch('tester-saved');
            $this->getTesters();
        } catch (\Throwable $th) {
            session()->flash('error-update-tester', 'Upss.. Gagal memperbarui data penguji');
        }
    }

    public function deleteTester($id)
    {
        try {
            Tester::where('id', $id)->delete();
            $this->dispatch('tester-saved');
            $this->getTesters();
        } catch (\Throwable $th) {
            session()->flash('error-delete-tester', 'Upss.. Gagal menghapus data penguji');
        }
    }

    public function getTesters()
    {
        $this->testers = Tester::orderBy('id', 'desc')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.components.modals.placement-test.add-edit-tester-modal');
    }
}
