<?php

namespace App\Livewire\Components\Modals\Setting;

use Livewire\Component;
use App\Models\Core\Branch;
use Livewire\Attributes\On;
use App\Queries\Core\BranchQuery;
use App\Models\Core\EducationProgram;
use Illuminate\Support\Facades\Crypt;
use App\Queries\Core\EducationProgramQuery;
use Illuminate\Contracts\Encryption\DecryptException;

class AddEditEducationProgramModal extends Component
{
    //String
    public $modalId, $branchName;
    //Boolean
    public $isEditing = false;
    //Array
    public $inputs = [
        'selectedBranchId' => '',
        'selectedEducationProgramId' => null,
        'educationProgramName' => null,
        'description' => null,
    ];
    public $branchLists = [];

    protected $rules = [
        'inputs.selectedBranchId' => 'required',
        'inputs.educationProgramName' => [
            'required', 'min:2'
        ],
    ];

    protected $messages = [
        'inputs.selectedBranchId.required' => 'Pilih cabang terlebih dahulu',
        'inputs.educationProgramName.required' => 'Tulis nama program pendidikan',
        'inputs.educationProgramName.min' => 'Minimal karakter 2',
    ];

    //LISTENER - Set detail value for selected program and fill in the modal
    #[On('open-add-edit-education-program-modal')]
    public function setEditValue($id) {
        try {
            $this->isEditing = true;
            $realId = Crypt::decrypt($id);
            $educationProgramData = EducationProgramQuery::fetchDetailProgram($realId);

            $this->inputs['selectedEducationProgramId'] = Crypt::encrypt($educationProgramData->id);
            $this->inputs['educationProgramName'] = $educationProgramData->name;
            $this->inputs['description'] = $educationProgramData->description;
            $this->inputs['selectedBranchId'] = $educationProgramData->branch_id;
            $this->branchName = $educationProgramData->branch->name;
        } catch (DecryptException) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //HOOK - Execute once when component is rendered
    public function mount() {
        $this->branchLists = Branch::pluck('name', 'id');
    }

    //ACTION - Reset property when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ACTION - Save education program data
    public function saveEducationProgram() {
        $this->validate($this->rules, $this->messages);

        try {
            $decryptedEducationProgramId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedEducationProgramId']) : null;

            EducationProgram::updateOrCreate([
                'id' => $decryptedEducationProgramId
            ], [
                'branch_id' => $this->inputs['selectedBranchId'],
                'name' => $this->inputs['educationProgramName'],
                'description' => $this->inputs['description']
            ]);

            $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
            $this->reset();
        } catch (\Throwable $th) {
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.setting.add-edit-education-program-modal');
    }
}
