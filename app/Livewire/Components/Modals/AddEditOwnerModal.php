<?php

namespace App\Livewire\Components\Modals;

use App\Models\User;
use Livewire\Component;
use App\Const\RoleConst;
use App\Models\Core\Owner;
use App\Queries\Core\OwnerQuery;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Validation\Rule;

class AddEditOwnerModal extends Component
{
    use WithFileUploads;

    //Boolean
    public $isEditing = false;
    //String
    public $modalId, $userPhoto = null;
    //Array
    public $inputs = [
        'ownerName' => null,
        'mobilePhone' => null,
        'email' => null,
        'password' => null,
        'selectedUserId' => null,
        'selectedOwnerId' => null
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

    //ACTION - Set value for selected store
    #[On('open-add-edit-owner-modal')]
    public function setEditValue($id) {
        try {
            $this->isEditing = true;
            $realId = Crypt::decrypt($id);

            $ownerData = OwnerQuery::fetchProfileOwner($realId);

            $this->inputs['selectedUserId'] = Crypt::encrypt($ownerData->user_id);
            $this->inputs['ownerName'] = $ownerData->name;
            $this->inputs['mobilePhone'] = $ownerData->mobile_phone;
            $this->inputs['email'] = $ownerData->user->email;
            $this->inputs['selectedOwnerId'] = Crypt::encrypt($ownerData->id);
            $this->userPhoto = $ownerData->user->photo;
        } catch (DecryptException) {
            session()->flash('error-id-edit', 'Dilarang modifikasi ID parameter');
        }
    }

    //ACTION - Save owner to database
    public function saveOwner() {
        $decryptUserId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedUserId']) : null;
        $decryptOwnerId = $this->isEditing ? Crypt::decrypt($this->inputs['selectedOwnerId']) : null;

        //Validation Rules
        $rules = [
            'inputs.ownerName' => 'required|min:3',
            'inputs.mobilePhone' => [
                'required', 'min:7', 'max:12'
            ],
            'inputs.email' => [
                'required',
                'unique:users,email,'.$decryptUserId,
            ],
        ];
        $rules['inputs.password'] = $this->isEditing ? 'nullable' : 'required|min:6';

        $this->validate($rules, [
            'inputs.ownerName.required' => 'Nama pemilik wajib diisi.',
            'inputs.ownerName.min' => 'Nama pemilik minimal :min karakter.',

            'inputs.mobilePhone.required' => 'Nomor HP wajib diisi.',
            'inputs.mobilePhone.min' => 'Nomor HP minimal :min digit.',
            'inputs.mobilePhone.max' => 'Nomor HP maksimal :max digit.',

            'inputs.email.required' => 'Email wajib diisi.',
            'inputs.email.unique' => 'Email ini sudah digunakan.',

            'inputs.password.required' => 'Password wajib diisi.',
            'inputs.password.min' => 'Password minimal :min karakter.',
        ]);
        //#Validation Rules

        try {
            DB::transaction(function () use ($decryptUserId, $decryptOwnerId) {
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

                //Query to table Users
                $userData = [
                    'role_id' => RoleConst::OWNER,
                    'email' => $this->inputs['email'],
                    'name' => $this->inputs['ownerName'],
                    'photo' => $photoPersist,
                ];

                // Only set password on create, or when editing and user entered a new one
                if (!$this->isEditing || ($this->inputs['password'] !== null && $this->inputs['password'] !== '')) {
                    $userData['password'] = Hash::make($this->inputs['password']);
                }

                $saveUser = User::updateOrCreate([
                    'id' => $decryptUserId
                ], $userData);

                //Check if user just created
                if ($saveUser->wasRecentlyCreated) {
                    $decryptUserId = $saveUser->id;
                }

                //Query to tabel Owner
                Owner::updateOrCreate([
                    'id' => $decryptOwnerId,
                ], [
                    'user_id' => $decryptUserId,
                    'name' => $this->inputs['ownerName'],
                    'mobile_phone' => $this->inputs['mobilePhone']
                ]);
            });

            $this->dispatch('toast', type: 'success', message: 'Owner berhasil ditambahkan!');
            $this->reset();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.add-edit-owner-modal');
    }
}
