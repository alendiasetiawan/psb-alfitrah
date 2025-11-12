<?php

namespace App\Livewire\Components\Modals\Setting;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\AdmissionHelper;
use Livewire\Attributes\Reactive;
use App\Models\Core\AdmissionQuota;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Container\Attributes\Log;
use App\Queries\Core\AdmissionQuotaQuery;
use App\Queries\Core\EducationProgramQuery;

class EditAdmissionQuotaModal extends Component
{
    //String
    public $modalId, $branchName, $educationProgramName, $academicYear;
    //Array
    public $inputs = [
        'quotaAmount' => null,
        'quotaStatus' => '',
        'selectedAdmissionQuotaId' => null,
        'selectedAdmissionId' => null,
        'selectedEducationProgramId' => null
    ];
    //Boolean
    public $isEditing = true;
    //Object
    #[Reactive]
    public $activeAdmission;

    protected $rules = [
        'inputs.quotaAmount' => [
            'required', 'numeric', 'min:1'
        ],
        'inputs.quotaStatus' => 'required'
    ];
    protected $messages = [
        'inputs.quotaAmount.required' => 'Jumlah kuota harus diisi',
        'inputs.quotaAmount.numeric' => 'Jumlah kuota harus berupa angka',
        'inputs.quotaAmount.min' => 'Jumlah kuota minimal 1',
        'inputs.quotaStatus.required' => 'Status harus diisi',
    ];

    #[On('open-edit-admission-quota-modal')]
    public function setEditValue($id) {
        try {
            $educationProgramId = Crypt::decrypt($id);
            $checkQuotaData = AdmissionQuota::where('education_program_id', $educationProgramId)
            ->where('admission_id', $this->activeAdmission->id)
            ->first();
            $checkQuotaData ? $admissionQuotaData = AdmissionQuotaQuery::fetchDetailQuotaWithProgram($checkQuotaData->id) : null;

            if ($checkQuotaData) {
                $this->isEditing = true;
                $this->inputs['quotaAmount'] = $admissionQuotaData->amount;
                $this->inputs['quotaStatus'] = $admissionQuotaData->status;
                $this->inputs['selectedAdmissionQuotaId'] = Crypt::encrypt($admissionQuotaData->id);
                $this->inputs['selectedAdmissionId'] = $admissionQuotaData->admission_id;
                $this->inputs['selectedEducationProgramId'] = $admissionQuotaData->education_program_id;
                $this->branchName = $admissionQuotaData->educationProgram?->branch_name;
                $this->educationProgramName = $admissionQuotaData->educationProgram?->program_name;
                $this->academicYear = $admissionQuotaData->admission->name;
            } else {
                $this->isEditing = false;
                $programData = EducationProgramQuery::fetchDetailProgram($educationProgramId);
                $this->branchName = $programData->branch->name;
                $this->educationProgramName = $programData->name;
                $this->academicYear = $this->activeAdmission->name;
                $this->inputs['selectedAdmissionId'] = $this->activeAdmission->id;
                $this->inputs['selectedEducationProgramId'] = $educationProgramId;
            }
        } catch (\Throwable $th) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //ACTION - Reset property and validation when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset('inputs', 'isEditing', 'branchName', 'educationProgramName', 'academicYear');
        $this->dispatch('reset-submit');
    }

    //ACTION - Save admission quota
    public function saveAdmissionQuota() {
        $this->validate($this->rules, $this->messages);

        try {
            $decryptedAdmissionQuotaId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedAdmissionQuotaId']) : null;
            AdmissionQuota::updateOrCreate([
                'id' => $decryptedAdmissionQuotaId
            ], [
                'admission_id' => $this->inputs['selectedAdmissionId'],
                'education_program_id' => $this->inputs['selectedEducationProgramId'],
                'amount' => $this->inputs['quotaAmount'],
                'status' => $this->inputs['quotaStatus']
            ]);

            $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
            $this->reset('inputs', 'isEditing', 'branchName', 'educationProgramName', 'academicYear');
        } catch (\Throwable $th) {
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.setting.edit-admission-quota-modal');
    }
}
