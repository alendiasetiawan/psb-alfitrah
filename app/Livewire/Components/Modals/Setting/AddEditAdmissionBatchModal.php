<?php

namespace App\Livewire\Components\Modals\Setting;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use App\Models\Core\AdmissionBatch;
use Illuminate\Support\Facades\Crypt;
use App\Queries\Core\AdmissionBatchQuery;
use Illuminate\Contracts\Encryption\DecryptException;

class AddEditAdmissionBatchModal extends Component
{
    //String
    public $modalId, $admissionName;
    //Boolean
    public $isEditing = false;
    //Array
    public $inputs = [
        'admissionBatchName' => '',
        'admissionBatchStart' => '',
        'admissionBatchEnd' => '',
        'selectedAdmissionBatchId' => null,
        'selectedAdmissionId' => null
    ];

    protected $rules = [
        'inputs.admissionBatchName' => [
            'required', 'min:9'
        ],
        'inputs.admissionBatchStart' => 'required',
        'inputs.admissionBatchEnd' => 'required',
    ];

    protected $messages = [
        'inputs.admissionBatchName.required' => 'Nama batch harus diisi',
        'inputs.admissionBatchName.min' => 'Nama batch minimal 9 karakter',
        'inputs.admissionBatchStart.required' => 'Tanggal mulai harus diisi',
        'inputs.admissionBatchEnd.required' => 'Tanggal berakhir harus diisi',
    ];

    #[On('create-new-batch')]
    public function setCreatetValue($id) {
        $this->inputs['selectedAdmissionId'] = $id;
    }

    #[On('open-add-edit-admission-batch-modal')]
    public function setEditValue($id) {
        try {
            $this->isEditing = true;
            $realId = Crypt::decrypt($id);

            $admissionData = AdmissionBatchQuery::fetchAdmissionBatchDetail($realId);
            $this->inputs['selectedAdmissionId'] = Crypt::encrypt($admissionData->admission_id);
            $this->inputs['selectedAdmissionBatchId'] = Crypt::encrypt($admissionData->id);
            $this->inputs['admissionBatchName'] = $admissionData->name;
            $this->inputs['admissionBatchStart'] = $admissionData->open_date;
            $this->inputs['admissionBatchEnd'] = $admissionData->close_date;
            $this->admissionName = $admissionData->admission?->name;
        } catch (DecryptException) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //ACTION - Reset property when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ACTION - Save admission batch data
    public function saveAdmissionBatch() {
        $decryptedAdmissionId = Crypt::decrypt($this->inputs['selectedAdmissionId']);
        $decryptedAdmissionBatchId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedAdmissionBatchId']) : null;

        try {
            AdmissionBatch::updateOrCreate([
                'id' => $decryptedAdmissionBatchId
            ], [
                'admission_id' => $decryptedAdmissionId,
                'name' => $this->inputs['admissionBatchName'],
                'open_date' => $this->inputs['admissionBatchStart'],
                'close_date' => $this->inputs['admissionBatchEnd']
            ]);

            $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
            $this->reset();
        } catch (\Throwable $th) {
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.setting.add-edit-admission-batch-modal');
    }
}
