<?php

namespace App\Livewire\Components\Modals\Setting;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Core\Admission;
use App\Models\Core\AdmissionBatch;
use App\Queries\Core\AdmissionQuery;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AddEditAdmissionModal extends Component
{
    //String
    public $modalId;
    //Boolean
    public $isEditing = false;
    //Array
    public $inputs = [
        'admissionName' => '',
        'admissionStatus' => '',
        'admissionBatchName' => '',
        'admissionBatchStart' => '',
        'admissionBatchEnd' => '',
        'selectedAdmissionId' => null,
        'selectedAdmissionBatchId' => null
    ];

    protected $messages = [
        'inputs.admissionName.required' => 'Tahun ajaran wajib diisi',
        'inputs.admissionName.min' => 'Tahun ajaran minimal 3 karakter',
        'inputs.admissionStatus.required' => 'Status wajib diisi',
        'inputs.admissionBatchStart.required' => 'Tanggal awal harus diisi',
        'inputs.admissionBatchEnd.required' => 'Tanggal akhir harus diisi',
        'inputs.admissionBatchEnd.after' => 'Tanggal tidak boleh kurang dari tanggal mulai'
    ];

    //ACTION - Reset property when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ACTION - Set value for selected store
    #[On('open-add-edit-admission-modal')]
    public function setEditValue($id) {
        try {
            $this->isEditing = true;
            $realId = Crypt::decrypt($id);

            $admissionData = AdmissionQuery::fetchAdmissionDetail($realId);
            $this->inputs['selectedAdmissionId'] = Crypt::encrypt($admissionData->id);
            $this->inputs['admissionName'] = $admissionData->name;
            $this->inputs['admissionStatus'] = $admissionData->status;

        } catch (DecryptException) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //ACTION - Save admission data
    public function saveAdmission() {
        $rules = [
            'inputs.admissionName' => [
                'required', 'min:9'
            ],
            'inputs.admissionStatus' => 'required',
            'inputs.admissionBatchName' => $this->isEditing ? 'nullable' : 'required',
            'inputs.admissionBatchStart' => $this->isEditing ? 'nullable' : 'required',
            'inputs.admissionBatchEnd' => [
                $this->isEditing ? 'nullable' : 'required', 'after:inputs.admissionBatchStart'
            ]
        ];

        $this->validate($rules, $this->messages);

        $decryptedAdmissionId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedAdmissionId']) : null;

        try {
            //Check if status Open close other status
            if ($this->inputs['admissionStatus'] == "Buka") {
                Admission::where('status', 'Buka')
                ->update([
                    'status' => 'Tutup'
                ]);
            }
            
            $saveAdmission = Admission::updateOrCreate([
                'id' => $decryptedAdmissionId
            ], [
                'name' => $this->inputs['admissionName'],
                'status' => $this->inputs['admissionStatus']
            ]);

            if (!$this->isEditing) {
                if ($saveAdmission->wasRecentlyCreated) {
                    $decryptedAdmissionId = $saveAdmission->id;
                }

                AdmissionBatch::create([
                    'admission_id' => $decryptedAdmissionId,
                    'name' => $this->inputs['admissionBatchName'],
                    'open_date' => $this->inputs['admissionBatchStart'],
                    'close_date' => $this->inputs['admissionBatchEnd']
                ]);
            }

            $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
            $this->reset();
        } catch (\Throwable $th) {
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.setting.add-edit-admission-modal');
    }
}
