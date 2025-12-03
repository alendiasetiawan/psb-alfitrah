<?php

namespace App\Livewire\Components\Modals\Setting;

use Livewire\Component;
use App\Models\Core\Branch;
use Livewire\Attributes\On;
use App\Queries\Core\BranchQuery;
use Illuminate\Support\Facades\DB;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AddEditBranchModal extends Component
{
    use WithFileUploads;

    //String
    public $modalId, $branchPhoto;
    //Boolean
    public $isEditing = false, $isMobile = false;
    //Array
    public $inputs = [
        'selectedBranchId' => null,
        'branchName' => null,
        'mobilePhone' => null,
        'branchAddress' => null,
        'mapLink'  => null
    ];

    protected UploadFileService $uploadFileService;

    protected $rules = [
        'inputs.branchName' => [
            'required',
            'min:3',
            'max:50'
        ],
        'inputs.mobilePhone' => [
            'required',
            'min:7',
            'max:12'
        ],
        'inputs.branchAddress' => [
            'required',
            'min:3',
            'max:500'
        ]
    ];

    protected $messages = [
        'inputs.branchName.required' => 'Wajib diisi!',
        'inputs.branchName.min' => 'Minimal 3 karakter',
        'inputs.branchName.max' => 'Maksimal 50 karakter',

        'inputs.mobilePhone.required' => 'Wajib diisi!',
        'inputs.mobilePhone.min' => 'Minimal 7 karakter',
        'inputs.mobilePhone.max' => 'Maksimal 12 karakter',

        'inputs.branchAddress.required' => 'Wajib diisi!',
        'inputs.branchAddress.min' => 'Minimal 3 karakter',
        'inputs.branchAddress.max' => 'Maksimal 500 karakter',
    ];

    #[On('pen-add-edit-branch-modal')]
    public function setEditValue($id)
    {
        try {
            $this->isEditing = true;
            $realId = Crypt::decrypt($id);
            $branchData = BranchQuery::fetchBranchWithProgram($realId);

            $this->inputs['selectedBranchId'] = Crypt::encrypt($branchData->id);
            $this->inputs['branchName'] = $branchData->name;
            $this->inputs['mobilePhone'] = $branchData->mobile_phone;
            $this->inputs['branchAddress'] = $branchData->address;
            $this->inputs['mapLink'] = $branchData->map_link;
            $this->branchPhoto = $branchData->photo;
        } catch (DecryptException) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //HOOK - Execute every time component is rendered
    public function boot(UploadFileService $uploadFileService)
    {
        $this->uploadFileService = $uploadFileService;
    }

    //HOOK - Execute when the property is updated
    public function updated($property)
    {
        if ($property == 'branchPhoto') {
            $this->validate([
                'branchPhoto' => 'mimes:jpg,jpeg,png|max:5120'
            ], [
                'branchPhoto.mimes' => 'Format file harus .jpg, .jpeg atau .png',
                'branchPhoto.max' => 'Ukuran file maksimal 5MB'
            ]);
        }
    }

    //ACTION - Reset property when modal is closed
    public function resetAllProperty()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ACTION - Save branch data
    public function saveBranch()
    {
        $this->validate($this->rules, $this->messages);

        try {
            $decryptedBranchId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedBranchId']) : null;

            DB::transaction(function () use ($decryptedBranchId) {
                // Determine logo value to persist
                $folderName = "images/branch-logo";
                $photoToPersist = null;

                // If new upload
                if ($this->branchPhoto instanceof TemporaryUploadedFile) {
                    $photoToPersist = $this->uploadFileService->compressAndSavePhoto($this->branchPhoto, $folderName);
                    // If existing string path (editing without changing photo)
                } elseif (is_string($this->branchPhoto) && $this->branchPhoto !== '') {
                    $photoToPersist = $this->branchPhoto;
                    // If creating and no upload provided, keep null
                } else {
                    $photoToPersist = null;
                }

                //Query to Database
                Branch::updateOrCreate([
                    'id' => $decryptedBranchId
                ], [
                    'name' => $this->inputs['branchName'],
                    'mobile_phone' => $this->inputs['mobilePhone'],
                    'address' => $this->inputs['branchAddress'],
                    'map_link' => $this->inputs['mapLink'],
                    'photo' => $photoToPersist
                ]);
            });

            $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.setting.add-edit-branch-modal');
    }
}
