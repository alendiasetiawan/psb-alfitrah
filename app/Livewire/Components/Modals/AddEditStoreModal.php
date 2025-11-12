<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Core\Store;
use Livewire\Attributes\On;
use App\Queries\Core\OwnerQuery;
use App\Queries\Core\StoreQuery;
use Illuminate\Support\Facades\DB;
use App\Services\UploadFileService;
use App\Helpers\FormValidatorHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AddEditStoreModal extends Component
{
    use WithFileUploads;
    //String
    public $modalId, $storeLogo = null, $realId;
    //Array
    public $results = [];
    public $inputs = [
        'storeName' => null,
        'storeAddress' => null,
        'searchOwnerName' => '',
        'selectedOwnerId' => null,
        'selectedStoreId' => null,
        'storeDescription' => null
    ];
    //Boolean
    public $isEditing = false;

    protected UploadFileService $uploadFileService;

    protected $rules = [
        'inputs.storeName' => 'required|min:3',
        'inputs.selectedOwnerId' => 'required'
    ];

    //HOOK - Initialize the component
    public function mount() {
        $this->initResult();
    }

    //HOOK - Executed every time the component is rendered
    public function boot(UploadFileService $uploadFileService) {
        $this->uploadFileService = $uploadFileService;
    }

    //HOOK - Update the search results when the query changes
    public function updated($property) {
        if ($property == 'inputs.searchOwnerName') {
            if (strlen($this->inputs['searchOwnerName']) > 2) {
                $this->results = OwnerQuery::getOwnerBySearchName($this->inputs['searchOwnerName']);
            } else {
                $this->results = [];
            }

            if ($this->inputs['searchOwnerName'] == '') {
                $this->inputs['selectedOwnerId'] = '';
            }
        }

        if ($property == 'storeLogo') {
            $this->validate([
                'storeLogo' => 'mimes:jpg,jpeg,png|max:5120'
            ], [
                'storeLogo.mimes' => 'Format file harus .jpg, .jpeg, atau .png',
                'storeLogo.max' => 'Ukuran file maksimal 5MB'
            ]);
        }
    }

    //ACTION - Set value for selected store
    #[On('open-add-edit-store-modal')]
    public function setEditValue($id) {
        try {
            $this->isEditing = true;
            $this->realId = Crypt::decrypt($id);

            $storeData = StoreQuery::fetchStore($this->realId);

            $this->inputs['selectedStoreId'] = $this->realId;
            $this->inputs['storeName'] = $storeData->name;
            $this->inputs['storeAddress'] = $storeData->address;
            $this->inputs['storeDescription'] = $storeData->description;
            $this->inputs['selectedOwnerId'] = $storeData->owner_id;
            $this->inputs['searchOwnerName'] = $storeData->owner->name ?? 'Kosong';
            $this->storeLogo = $storeData->logo;
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

    //ACTION - Init value for results when user click the input
    public function initResult() {
        $this->results = OwnerQuery::getOwnerLimit(5);
    }

    //ACTION - Saving Data
    public function saveStore() {
        try {
            $this->validate();
            DB::transaction(function () {
                // Determine logo value to persist
                $folderName = "store-logo";
                $logoToPersist = null;

                // If new upload
                if ($this->storeLogo instanceof TemporaryUploadedFile) {
                    $logoToPersist = $this->uploadFileService->compressAndSavePhoto($this->storeLogo, $folderName);
                // If existing string path (editing without changing photo)
                } elseif (is_string($this->storeLogo) && $this->storeLogo !== '') {
                    $logoToPersist = $this->storeLogo;
                // If creating and no upload provided, keep null
                } else {
                    $logoToPersist = null;
                }

                //Query to Database
                Store::updateOrCreate([
                    'id' => $this->inputs['selectedStoreId']
                ], [
                    'name' => $this->inputs['storeName'],
                    'address' => $this->inputs['storeAddress'],
                    'description' => $this->inputs['storeDescription'],
                    'owner_id' => $this->inputs['selectedOwnerId'],
                    'logo' => $logoToPersist
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
        return view('livewire.components.modals.add-edit-store-modal');
    }
}
