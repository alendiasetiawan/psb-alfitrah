<?php

namespace App\Livewire\Student\AdmissionData;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Services\UploadFileService;
use App\Services\StudentDataService;
use App\Queries\AdmissionData\StudentQuery;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Title('Lampiran Berkas Siswa')]
class AdmissionAttachment extends Component
{
    use WithFileUploads;
    public bool $isMobile = false, $isSubmitActive = false;
    public $studentPhoto = null, $bornCard = null, $familyCard = null, $parentCard = null;
    protected UploadFileService $uploadFileService;
    protected StudentDataService $studentDataService;
    protected $rules = [
        'studentPhoto' => [
            'mimes:jpg,jpeg,png', 'max:5120'
        ],
        'bornCard' => [
            'mimes:jpg,jpeg,png', 'max:5120'
        ],
        'familyCard' => [
            'mimes:jpg,jpeg,png', 'max:5120'
        ],
        'parentCard' => [
            'mimes:jpg,jpeg,png', 'max:5120'
        ]
    ];
    protected $messages = [
        'studentPhoto.mimes' => 'Format Photo harus .jpg, .jpeg, atau .png',
        'studentPhoto.max' => 'Ukuran Photo maksimal 5MB',
        'bornCard.mimes' => 'Format Akte Kelahiran harus .jpg, .jpeg, atau .png',
        'bornCard.max' => 'Ukuran Akte Kelahiran maksimal 5MB',
        'familyCard.mimes' => 'Format Kartu Keluarga harus .jpg, .jpeg, atau .png',
        'familyCard.max' => 'Ukuran Kartu Keluarga maksimal 5MB',
        'parentCard.mimes' => 'Format KTP Orang Tua harus .jpg, .jpeg, atau .png',
        'parentCard.max' => 'Ukuran KTP Orang Tua maksimal 5MB',
    ];

    #[Computed(cache: true, key: 'student-verification-attachment')]
    public function detailStudentAttachment() {
        return StudentQuery::fetchStudentAttachmentWithStatus($this->studentId);
    }

    //HOOK - Execute every time component is render
    public function boot(MobileDetect $mobileDetect, UploadFileService $uploadFileService, StudentDataService $studentDataService) {
        $this->isMobile = $mobileDetect->isMobile();
        $this->uploadFileService = $uploadFileService;
        $this->parentId = session('userData')->parent->id;
        $this->studentId = $studentDataService->findActiveStudentId($this->parentId);
    }

    //HOOK - Update the search results when the query changes
    public function updated($property) {
        $this->isFormFilled();

        if ($property == 'studentPhoto') {
            $this->validateOnly('studentPhoto');
        }

        if ($property == 'bornCard') {
            $this->validateOnly('bornCard');
        }

        if ($property == 'familyCard') {
            $this->validateOnly('familyCard');
        }

        if ($property == 'parentCard') {
            $this->validateOnly('parentCard');
        }
    }

    //ACTION - Check if submit button active
    public function isFormFilled() {
        if (!empty($this->studentPhoto) && !empty($this->bornCard) && !empty($this->familyCard) && !empty($this->parentCard)) {
            $this->isSubmitActive = true;
        } else {
            $this->isSubmitActive = false;
        }
    }

    //ACTION - Reset selected property
    public function resetSelectedProperty($property) {
        // dd('halo');
        $this->reset($property);
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.student.admission-data.admission-attachment')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }
        return view('livewire.web.student.admission-data.admission-attachment')->layout('components.layouts.web.web-app');
    }
}
