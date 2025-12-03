<?php

namespace App\Livewire\Components\Modals\Setting;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Core\Admission;
use App\Helpers\AdmissionHelper;
use App\Helpers\FormatCurrencyHelper;
use App\Models\Core\AdmissionFee;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Facades\Crypt;
use App\Queries\Core\AdmissionFeeQuery;
use App\Queries\Core\EducationProgramQuery;

class EditAdmissionFeeModal extends Component
{
    //String
    public $modalId, $branchName, $educationProgramName, $academicYear;
    //Boolean
    public $isEditing = true, $isMobile = false;
    #[Reactive]
    public $activeAdmission;
    //Array
    public $inputs = [
        'selectedAdmissionId' => '',
        'selectedEducationProgramId' => '',
        'selectedAdmissionFeeId' => '',
        'registrationFee' => '',
        'internalRegistrationFee' => ''
    ];

    protected $rules = [
        'inputs.registrationFee' => [
            'required',
            'min:1'
        ],
        'inputs.internalRegistrationFee' => [
            'required',
            'min:1'
        ]
    ];

    protected $messages = [
        'inputs.registrationFee.required' => 'Biaya pendaftaran wajib diisi',
        'inputs.internalRegistrationFee.required' => 'Biaya pendaftaran wajib diisi',
        'inputs.registrationFee.min' => 'Biaya pendaftaran minimal Rp :min',
        'inputs.internalRegistrationFee.min' => 'Biaya pendaftaran minimal Rp :min',
    ];

    #[On('open-edit-admission-fee-modal')]
    public function setEditValue($id)
    {
        try {
            $educationProgramId = Crypt::decrypt($id);
            $checkFeeData = AdmissionFee::where('education_program_id', $educationProgramId)
                ->where('admission_id', $this->activeAdmission->id)
                ->first();

            $checkFeeData ? $admissionFeeData = AdmissionFeeQuery::fetchDetailFeeWithProgram($checkFeeData->id) : null;

            if ($checkFeeData) {
                $this->isEditing = true;
                $this->inputs['registrationFee'] = FormatCurrencyHelper::convertCurrency($admissionFeeData->registration_fee);
                $this->inputs['internalRegistrationFee'] = FormatCurrencyHelper::convertCurrency($admissionFeeData->internal_registration_fee);
                $this->inputs['selectedAdmissionFeeId'] = Crypt::encrypt($admissionFeeData->id);
                $this->inputs['selectedAdmissionId'] = $admissionFeeData->admission_id;
                $this->inputs['selectedEducationProgramId'] = $admissionFeeData->education_program_id;
                $this->branchName = $admissionFeeData->educationProgram?->branch_name;
                $this->educationProgramName = $admissionFeeData->educationProgram?->program_name;
                $this->academicYear = $admissionFeeData->admission->name;
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
    public function resetAllProperty()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset('inputs', 'isEditing', 'branchName', 'educationProgramName', 'academicYear');
        $this->dispatch('reset-submit');
    }

    //ACTION - Save admission fee data
    public function saveAdmissionFee()
    {
        $this->validate($this->rules, $this->messages);
        try {
            $decryptedAdmissionFeeId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedAdmissionFeeId']) : null;
            AdmissionFee::updateOrCreate([
                'id' => $decryptedAdmissionFeeId
            ], [
                'admission_id' => $this->inputs['selectedAdmissionId'],
                'education_program_id' => $this->inputs['selectedEducationProgramId'],
                'registration_fee' => $this->inputs['registrationFee'],
                'internal_registration_fee' => $this->inputs['internalRegistrationFee']
            ]);

            $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
            $this->resetAllProperty();
        } catch (\Throwable $th) {
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.setting.edit-admission-fee-modal');
    }
}
