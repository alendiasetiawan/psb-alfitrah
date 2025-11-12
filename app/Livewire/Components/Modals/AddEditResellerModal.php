<?php

namespace App\Livewire\Components\Modals;

use App\Models\User;
use Livewire\Component;
use App\Const\RoleConst;
use Livewire\Attributes\On;
use App\Models\Core\Reseller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Queries\Core\ResellerQuery;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AddEditResellerModal extends Component
{
    use WithFileUploads;

    //Boolean
    public $isEditing = false;
    //String
    public $modalId, $userPhoto = null;
    //Array
    public $inputs = [
        'resellerName' => null,
        'mobilePhone' => null,
        'email' => null,
        'password' => null,
        'selectedUserId' => null,
        'selectedResellerId' => null
    ];

    protected UploadFileService $uploadFileService;

    //HOOK - Execute every time component is rendered
    public function boot(UploadFileService $uploadFileService) {
        $this->uploadFileService = $uploadFileService;
    }

    //HOOK - Update the search results when the query changes
    public function updated($property) {
        if ($property == 'userPhoto') {
            $this->validate([
                'userPhoto' => 'mimes:jpg,jpeg,png|max:5120'
            ], [
                'userPhoto.mimes' => 'Format file harus .jpg, .jpeg, atau .png',
                'userPhoto.max' => 'Ukuran file maksimal 5MB'
            ]);
        }
    }

    //ACTION - Reset property and validation when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ACTION - Set value for selected reseller
    #[On('open-add-edit-reseller-modal')]
    public function setEditValue($id) {
        try {
            $this->isEditing = true;
            $realId = Crypt::decrypt($id);

            $resellerData = ResellerQuery::fetchProfileReseller($realId);

            $this->inputs['selectedUserId'] = Crypt::encrypt($resellerData->user_id);
            $this->inputs['resellerName'] = $resellerData->name;
            $this->inputs['mobilePhone'] = $resellerData->mobile_phone;
            $this->inputs['email'] = $resellerData->user->email;
            $this->inputs['selectedResellerId'] = Crypt::encrypt($resellerData->id);
            $this->userPhoto = $resellerData->user->photo;
        } catch (DecryptException) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //ACTION - Save reseller to database
    public function saveReseller() {
        try {
            $decryptUserId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedUserId']) : null;
            $decryptResellerId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedResellerId']) : null;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('save-failed', 'Terjadi kesalahan, silahkan coba lagi!');
        }

        //Validation Rules
        $rules = [
            'inputs.resellerName' => 'required|min:3',
            'inputs.mobilePhone' => [
                'required', 'min:7', 'max:12'
            ],
            'inputs.email' => [
                'required',
                'unique:users,email,'.$decryptUserId
            ],
        ];
        $rules['inputs.password'] = $this->isEditing ? 'nullable' : 'required|min:6';

        $this->validate($rules, [
            'inputs.resellerName.required' => 'Nama pemilik wajib diisi.',
            'inputs.resellerName.min' => 'Nama pemilik minimal :min karakter.',

            'inputs.mobilePhone.required' => 'Nomor HP wajib diisi.',
            'inputs.mobilePhone.min' => 'Nomor HP minimal :min digit.',
            'inputs.mobilePhone.max' => 'Nomor HP maksimal :max digit.',

            'inputs.email.required' => 'Username wajib diisi.',
            'inputs.email.unique' => 'Username ini sudah digunakan.',

            'inputs.password.required' => 'Password wajib diisi.',
            'inputs.password.min' => 'Password minimal :min karakter.',
        ]);
        //#Validation Rules

        try {
            DB::transaction(function () use($decryptUserId, $decryptResellerId) {
                // Determine logo value to persist
                $folderName = "user-photo";
                $photoPersist = null;

                // If new upload
                if ($this->userPhoto instanceof TemporaryUploadedFile) {
                    $photoPersist = $this->uploadFileService->compressAndSavePhoto($this->userPhoto, $folderName);
                // If existing string path (editing without changing photo)
                } elseif (is_string($this->userPhoto) && $this->userPhoto !== '') {
                    $photoPersist = $this->userPhoto;
                // If creating and no upload provided, keep null
                } else {
                    $photoPersist = null;
                }

                //Prepare data for query to table Users
                $userData = [
                    'role_id' => RoleConst::RESELLER,
                    'email' => $this->inputs['email'],
                    'name' => $this->inputs['resellerName'],
                    'photo' => $photoPersist,
                ];

                // Only set password on create, or when editing and user entered a new one
                if (!$this->isEditing || ($this->inputs['password'] !== null && $this->inputs['password'] !== '')) {
                    $userData['password'] = Hash::make($this->inputs['password']);
                }

                //Execute query to table users
                $saveUser = User::updateOrCreate([
                    'id' => $decryptUserId
                ], $userData);

                //Check if user just created
                if ($saveUser->wasRecentlyCreated) {
                    $decryptUserId = $saveUser->id;
                }

                //Query to tabel Reseller
                Reseller::updateOrCreate([
                    'id' => $decryptResellerId,
                ], [
                    'user_id' => $decryptUserId,
                    'name' => $this->inputs['resellerName'],
                    'mobile_phone' => $this->inputs['mobilePhone']
                ]);
            });

            $this->dispatch('toast', type: 'success', message: 'Reseller berhasil ditambahkan!');
            $this->reset();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.add-edit-reseller-modal');
    }
}
