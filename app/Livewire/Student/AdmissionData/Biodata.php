<?php

namespace App\Livewire\Student\AdmissionData;

use Livewire\Component;
use App\Models\Core\Job;
use Detection\MobileDetect;
use App\Models\Core\Regency;
use App\Models\Core\Sallary;
use App\Models\Core\Village;
use App\Models\Core\District;
use App\Models\Core\Province;
use App\Queries\Core\JobQuery;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;
use App\Models\Core\LastEducation;
use App\Services\StudentDataService;
use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\Student;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheKeys\CoreCacheKey;
use App\Livewire\Forms\BiodataStudentForm;
use App\Queries\AdmissionData\StudentQuery;
use App\Services\AdmissionVerificationService;
use App\Helpers\CacheKeys\Student\StudentAdmissionDataKey;

#[Title('Biodata Siswa')]
class Biodata extends Component
{
   public BiodataStudentForm $form;
   public bool $isMobile = false, $isReviewOrDone = false, $isParent = true, $isCanEdit = false, $isEditingMode = false;
   public array $provinceLists = [], $regencyLists = [], $districtLists = [], $villageLists = [], $lastEducationLists = [], $jobLists = [], $sallaryLists = [];
   public string $fatherJobName = '';
   #[Locked]
   public int $studentId;
   #[Locked]
   public int $parentId;
   public Student $detailStudent;

   protected AdmissionVerificationService $admissionVerificationService;

   //HOOK - Execute every time component is rendered
   public function boot(MobileDetect $mobileDetect, AdmissionVerificationService $admissionVerificationService, StudentDataService $studentDataService)
   {
      $this->isMobile = $mobileDetect->isMobile();
      $this->admissionVerificationService = $admissionVerificationService;

      //Set value for submit parameter
      $this->parentId = session('userData')->parent->id;
      $this->studentId = $studentDataService->findActiveStudentId($this->parentId);
   }

   //HOOK - Execute once when component is rendered
   public function mount()
   {
      //Assign value for dropdown lists
      $provinceKey = CoreCacheKey::province();
      $lastEducationKey = CoreCacheKey::lastEducation();
      $sallaryKey = CoreCacheKey::sallary();
      $this->provinceLists = Cache::remember($provinceKey, 604800, fn() => Province::all()->toArray());
      $this->lastEducationLists = Cache::remember($lastEducationKey, 604800, fn() => LastEducation::all()->toArray());
      $this->initJobSearch();
      $this->sallaryLists = Cache::remember($sallaryKey, 604800, fn() => Sallary::all()->toArray());

      //Assign value for detail student and bind it to propery
      $this->detailStudent = $this->detailStudentQuery();
      $this->form->inputs['studentName'] = $this->detailStudent->name;
      $this->form->inputs['gender'] = $this->detailStudent->gender;
      $this->form->inputs['birthPlace'] = $this->detailStudent->birth_place;
      $this->form->inputs['birthDate'] = $this->detailStudent->birth_date;
      $this->form->inputs['mobilePhone'] = $this->detailStudent->mobile_phone;
      $this->form->inputs['nisn'] = $this->detailStudent->nisn;
      $this->form->inputs['address'] = $this->detailStudent->address;
      $this->form->inputs['oldSchoolName'] = $this->detailStudent->old_school_name;
      $this->form->inputs['oldSchoolAddress'] = $this->detailStudent->old_school_address;
      $this->form->inputs['oldSchoolNpsn'] = $this->detailStudent->npsn;
      $this->form->inputs['selectedProvinceId'] = $this->detailStudent->province_id ?? '';
      $this->form->inputs['selectedRegencyId'] = $this->detailStudent->regency_id ?? '';
      $this->form->inputs['selectedDistrictId'] = $this->detailStudent->district_id ?? '';
      $this->form->inputs['selectedVillageId'] = $this->detailStudent->village_id ?? '';
      $this->form->inputs['isParent'] = $this->detailStudent->parent?->is_parent;
      $this->form->inputs['fatherName'] = $this->detailStudent->parent->father_name ?? '';
      $this->form->inputs['fatherMobilePhone'] = $this->detailStudent->parent->father_mobile_phone;
      $this->form->inputs['fatherBirthPlace'] = $this->detailStudent->parent->father_birth_place;
      $this->form->inputs['fatherBirthDate'] = $this->detailStudent->parent->father_birth_date;
      $this->form->inputs['fatherAddress'] = $this->detailStudent->parent->father_address;
      $this->form->inputs['fatherSelectedLastEducationId'] = $this->detailStudent->parent->father_last_education_id ?? '';
      $this->form->inputs['fatherSelectedJobId'] = $this->detailStudent->parent->father_job_id ?? '';
      $this->form->inputs['fatherSelectedSallaryId'] = $this->detailStudent->parent->father_sallary_id ?? '';
      $this->form->inputs['motherName'] = $this->detailStudent->parent->mother_name ?? '';
      $this->form->inputs['motherMobilePhone'] = $this->detailStudent->parent->mother_mobile_phone;
      $this->form->inputs['motherBirthPlace'] = $this->detailStudent->parent->mother_birth_place;
      $this->form->inputs['motherBirthDate'] = $this->detailStudent->parent->mother_birth_date;
      $this->form->inputs['motherAddress'] = $this->detailStudent->parent->mother_address;
      $this->form->inputs['motherSelectedLastEducationId'] = $this->detailStudent->parent->mother_last_education_id ?? '';
      $this->form->inputs['motherSelectedJobId'] = $this->detailStudent->parent->mother_job_id ?? '';
      $this->form->inputs['motherSelectedSallaryId'] = $this->detailStudent->parent->mother_sallary_id ?? '';
      $this->form->inputs['guardianName'] = $this->detailStudent->parent->guardian_name ?? '';
      $this->form->inputs['guardianMobilePhone'] = $this->detailStudent->parent->guardian_mobile_phone;
      $this->form->inputs['guardianBirthPlace'] = $this->detailStudent->parent->guardian_birth_place;
      $this->form->inputs['guardianBirthDate'] = $this->detailStudent->parent->guardian_birth_date;
      $this->form->inputs['guardianAddress'] = $this->detailStudent->parent->guardian_address;
      $this->form->inputs['guardianSelectedLastEducationId'] = $this->detailStudent->parent->guardian_last_education_id ?? '';
      $this->form->inputs['guardianSelectedJobId'] = $this->detailStudent->parent->guardian_job_id ?? '';
      $this->form->inputs['guardianSelectedSallaryId'] = $this->detailStudent->parent->guardian_sallary_id ?? '';
      $this->form->inputs['searchFatherJobName'] = $this->detailStudent->parent->jobFather->name ?? '';
      $this->form->inputs['searchMotherJobName'] = $this->detailStudent->parent->jobMother->name ?? '';
      $this->form->inputs['searchGuardianJobName'] = $this->detailStudent->parent->jobGuardian->name ?? '';

      //Determine if user can edit biodata
      $this->isCanEdit = $this->isCanEditQuery();
      if ($this->detailStudent->biodata != VerificationStatusEnum::NOT_STARTED) {
         $this->dispatch('editing-mode');
      }

      //Setup default data for demografi
      $this->regencyLists = $this->form->inputs['selectedRegencyId'] ? $this->setRegencyLists() : [];
      $this->districtLists = $this->form->inputs['selectedDistrictId'] ? $this->setDistrictLists() : [];
      $this->villageLists = $this->form->inputs['selectedVillageId'] ? $this->setVillageLists() : [];
   }

   //HOOK - Execute when property is changed
   public function updated($propertyName)
   {
      if ($propertyName == 'form.inputs.selectedProvinceId') {
         $this->regencyLists = $this->setRegencyLists();
      }

      if ($propertyName == 'form.inputs.selectedRegencyId') {
         $this->districtLists = $this->setDistrictLists();
      }

      if ($propertyName == 'form.inputs.selectedDistrictId') {
         $this->villageLists = $this->setVillageLists();
      }

      //Search father job name
      if ($propertyName == 'form.inputs.searchFatherJobName') {
         if (strlen($this->form->inputs['searchFatherJobName']) > 2) {
            $this->jobLists = JobQuery::getJobBySearchName($this->form->inputs['searchFatherJobName']);
         } else {
            $this->jobLists = [];
         }

         if ($this->form->inputs['searchFatherJobName'] == '') {
            $this->form->inputs['selectedFatherJobId'] = '';
         }
      }

      //Search mother job name
      if ($propertyName == 'form.inputs.searchMotherJobName') {
         if (strlen($this->form->inputs['searchMotherJobName']) > 2) {
            $this->jobLists = JobQuery::getJobBySearchName($this->form->inputs['searchMotherJobName']);
         } else {
            $this->jobLists = [];
         }

         if ($this->form->inputs['searchMotherJobName'] == '') {
            $this->form->inputs['selectedMotherJobId'] = '';
         }
      }

      //Search guardian job name
      if ($propertyName == 'form.inputs.searchGuardianJobName') {
         if (strlen($this->form->inputs['searchGuardianJobName']) > 2) {
            $this->jobLists = JobQuery::getJobBySearchName($this->form->inputs['searchGuardianJobName']);
         } else {
            $this->jobLists = [];
         }

         if ($this->form->inputs['searchGuardianJobName'] == '') {
            $this->form->inputs['selectedGuardianJobId'] = '';
         }
      }
   }

   //ACTION - Init value for results when user click the input
   public function initJobSearch()
   {
      $jobKey = CoreCacheKey::job();
      $this->jobLists = Cache::remember($jobKey, 604800, fn() => Job::all()->toArray());
   }

   //ACTION - Fetch student detail
   public function detailStudentQuery()
   {
      $key = StudentAdmissionDataKey::studentBiodata($this->studentId);

      return Cache::remember(
         $key,
         604800,
         fn() =>
         StudentQuery::fetchStudentDetailWithStatus($this->studentId)
      );
   }

   //ACTION - Check if user can edit biodata
   public function isCanEditQuery()
   {
      return $this->admissionVerificationService->isStudentCanEditBiodata($this->detailStudent->registration_payment, $this->detailStudent->biodata);
   }

   //ACTION - Set regency list
   public function setRegencyLists()
   {
      return Regency::where('province_id', $this->form->inputs['selectedProvinceId'])->get()->toArray();
   }

   //ACTION - Set district list
   public function setDistrictLists()
   {
      return District::where('regency_id', $this->form->inputs['selectedRegencyId'])->get()->toArray();
   }

   //ACTION - Set village list
   public function setVillageLists()
   {
      return Village::where('district_id', $this->form->inputs['selectedDistrictId'])->get()->toArray();
   }

   //ACTION - Process store student and parent data
   public function saveBiodata()
   {

      //Process saving data
      try {
         $this->form->save($this->parentId, $this->studentId);
         $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
         $this->redirect(route('student.admission_data.biodata'), navigate: true);
      } catch (\Throwable $th) {
         session()->flash('save-failed', 'Data gagal disimpan, silahkan coba lagi!');
      }
   }

   public function render()
   {
      if ($this->isMobile) {
         return view('livewire.mobile.student.admission-data.biodata')->layout('components.layouts.mobile.mobile-app', [
            'isShowBottomNavbar' => true,
            'isShowTitle' => true
         ]);
      }
      return view('livewire.web.student.admission-data.biodata')->layout('components.layouts.web.web-app');
   }
}
