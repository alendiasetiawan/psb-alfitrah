<?php

namespace App\Livewire\Student\AdmissionData;

use App\Enums\VerificationStatusEnum;
use App\Helpers\CacheKeys\Student\AdmissionDataCacheKey;
use App\Helpers\CacheKeys\Student\StudentAdmissionDataKey;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\StudentAttachment;
use App\Models\User;
use App\Queries\AdmissionData\StudentQuery;
use App\Services\AdmissionVerificationService;
use App\Services\StudentDataService;
use App\Services\UploadFileService;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Title('Berkas Siswa')]
class AdmissionAttachment extends Component
{
   use WithFileUploads;

   #[Locked]
   public $studentId;
   #[Locked]
   public $parentId;

   public bool $isMobile = false, $isSubmitActive = false, $isCanEdit = false;
   public $studentPhoto = null, $bornCard = null, $familyCard = null, $parentCard = null;
   public object $detailAttachment;
   public string $academicYear;

   protected UploadFileService $uploadFileService;
   protected StudentDataService $studentDataService;
   protected AdmissionVerificationService $admissionVerificationService;
   protected $rules = [
      'studentPhoto' => [
         'mimes:jpg,jpeg,png',
         'max:3072'
      ],
      'bornCard' => [
         'mimes:jpg,jpeg,png',
         'max:3072'
      ],
      'familyCard' => [
         'mimes:jpg,jpeg,png',
         'max:3072'
      ],
      'parentCard' => [
         'mimes:jpg,jpeg,png',
         'max:3072'
      ]
   ];
   protected $messages = [
      'studentPhoto.mimes' => 'Format Photo harus .jpg, .jpeg, atau .png',
      'studentPhoto.max' => 'Ukuran Photo maksimal 3MB',
      'bornCard.mimes' => 'Format Akte Kelahiran harus .jpg, .jpeg, atau .png',
      'bornCard.max' => 'Ukuran Akte Kelahiran maksimal 3MB',
      'familyCard.mimes' => 'Format Kartu Keluarga harus .jpg, .jpeg, atau .png',
      'familyCard.max' => 'Ukuran Kartu Keluarga maksimal 3MB',
      'parentCard.mimes' => 'Format KTP Orang Tua harus .jpg, .jpeg, atau .png',
      'parentCard.max' => 'Ukuran KTP Orang Tua maksimal 3MB',
   ];

   //HOOK - Execute once when component is rendered
   public function mount()
   {
      $this->studentPhoto = $this->detailAttachment->studentAttachment->photo ?? null;
      $this->bornCard = $this->detailAttachment->studentAttachment->born_card ?? null;
      $this->familyCard = $this->detailAttachment->studentAttachment->family_card ?? null;
      $this->parentCard = $this->detailAttachment->studentAttachment->parent_card ?? null;
      $this->academicYear = $this->detailAttachment->academic_year;

      //Determine is student can edit based on admission verification
      $this->isCanEdit = $this->admissionVerificationService->isStudentCadEditAttachment($this->detailAttachment->registration_payment, $this->detailAttachment->attachment);
   }

   public function boot(MobileDetect $mobileDetect, UploadFileService $uploadFileService, StudentDataService $studentDataService, AdmissionVerificationService $admissionVerificationService)
   {
      //Set dependency injection
      $this->studentDataService = $studentDataService;
      $this->isMobile = $mobileDetect->isMobile();
      $this->uploadFileService = $uploadFileService;
      $this->admissionVerificationService = $admissionVerificationService;

      //Set mandatory value
      $this->parentId = session('userData')->parent->id;
      $this->studentId = $this->studentDataService->findActiveStudentId($this->parentId);
      $this->detailAttachment = $this->detailAttachmentQuery();
   }

   //HOOK - Update the search results when the query changes
   public function updated($property)
   {
      // $this->detailAttachment = $this->detailAttachmentQuery();
      $this->isFormFilled();

      $validatedProperty = array('studentPhoto', 'bornCard', 'familyCard', 'parentCard');
      if (in_array($property, $validatedProperty)) {
         $this->validateOnly($property);
      }
   }

   //ACTION - Fetch detail attachment student with status and store in cache
   public function detailAttachmentQuery()
   {
      $key = StudentAdmissionDataKey::studentAttachment($this->studentId);
      return Cache::remember(
         $key,
         604800,
         fn() =>
         StudentQuery::fetchStudentAttachmentWithStatus($this->studentId)
      );
   }

   //ACTION - Check if submit button active
   public function isFormFilled()
   {
      if (!empty($this->studentPhoto) && !empty($this->bornCard) && !empty($this->familyCard) && !empty($this->parentCard)) {
         $this->isSubmitActive = true;
      } else {
         $this->isSubmitActive = false;
      }
   }

   //ACTION - Reset selected property
   public function resetSelectedProperty($property)
   {
      $this->reset($property);
      $this->isSubmitActive = false;
   }

   //ACTION - Save attachment data
   public function saveAttachment()
   {
      //Logic to set folder name
      $folderName = 'student-attachments/' . $this->academicYear . '';

      try {
         //--Student Photo File--//
         $oldStudentPhoto = $this->detailAttachment->studentAttachment->photo ?? null;
         if ($this->studentPhoto instanceof TemporaryUploadedFile) {
            //If new upload, replace old file
            $uploadedStudentPhoto = $this->uploadFileService->compressAndSavePhoto($this->studentPhoto, $folderName, $oldStudentPhoto);
         } else {
            //no file uploaded, keep old file
            $uploadedStudentPhoto = $oldStudentPhoto;
         }

         //--Born Card File--//
         $oldBornCard = $this->detailAttachment->studentAttachment->born_card ?? null;
         if ($this->bornCard instanceof TemporaryUploadedFile) {
            //If new upload, replace old file
            $uploadedBornCard = $this->uploadFileService->compressAndSavePhoto($this->bornCard, $folderName, $oldBornCard);
         } else {
            //no file uploaded, keep old file
            $uploadedBornCard = $oldBornCard;
         }

         //--Family Card File--//
         $oldFamilyCard = $this->detailAttachment->studentAttachment->family_card ?? null;
         if ($this->familyCard instanceof TemporaryUploadedFile) {
            //If new upload, replace old file
            $uploadedFamilyCard = $this->uploadFileService->compressAndSavePhoto($this->familyCard, $folderName, $oldFamilyCard);
         } else {
            //no file uploaded, keep old file
            $uploadedFamilyCard = $oldFamilyCard;
         }

         //--Parent Card File--//
         $oldParentCard = $this->detailAttachment->studentAttachment->parent_card ?? null;
         if ($this->parentCard instanceof TemporaryUploadedFile) {
            //If new upload, replace old file
            $uploadedParentCard = $this->uploadFileService->compressAndSavePhoto($this->parentCard, $folderName, $oldParentCard);
         } else {
            //no file uploaded, keep old file
            $uploadedParentCard = $oldParentCard;
         }

         //Update or Create attachment data
         StudentAttachment::updateOrCreate([
            'student_id' => $this->studentId,
         ], [
            'photo' => $uploadedStudentPhoto,
            'born_card' => $uploadedBornCard,
            'family_card' => $uploadedFamilyCard,
            'parent_card' => $uploadedParentCard,
            'photo_status' => VerificationStatusEnum::PROCESS,
            'parent_card_status' => VerificationStatusEnum::PROCESS,
            'born_card_status' => VerificationStatusEnum::PROCESS,
            'family_card_status' => VerificationStatusEnum::PROCESS,
            'modified_at' => now(),
         ]);

         //Update photo for user's avatar
         $user = User::find(session('userData')->id);
         $user->update([
            'photo' => $uploadedStudentPhoto
         ]);

         //Update admission verification status
         $verification = AdmissionVerification::where('student_id', $this->studentId)->firstOrFail();
         $verification->update([
            'attachment' => VerificationStatusEnum::PROCESS
         ]);

         $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
         $this->redirect(route('student.admission_data.admission_attachment'), navigate: true);
      } catch (\Throwable) {
         session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
      }
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
