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
use Livewire\Attributes\Computed;
use App\Models\Core\LastEducation;
use App\Services\StudentDataService;
use Illuminate\Support\Facades\Auth;
use App\Models\AdmissionData\Student;
use App\Livewire\Forms\BiodataStudentForm;
use App\Queries\AdmissionData\StudentQuery;
use App\Services\AdmissionVerificationService;
use App\Queries\AdmissionData\AdmissionVerificationQuery;

#[Title('Biodata Siswa')]
class Biodata extends Component
{
    public BiodataStudentForm $form;
    public bool $isMobile = false, $isReviewOrDone = false, $isParent = true, $isCanEdit = false;
    public array $provinceLists = [], $regencyLists = [], $districtLists = [], $villageLists = [], $lastEducationLists = [], $jobLists = [], $sallaryLists = [];
    #[Locked]
    public int $studentId;
    #[Locked]
    public int $parentId;
    public Student $detailStudent;

    protected AdmissionVerificationService $admissionVerificationService;
    protected StudentDataService $studentDataService;

    //HOOK - Execute every time component is rendered
    public function boot(MobileDetect $mobileDetect, AdmissionVerificationService $admissionVerificationService, StudentDataService $studentDataService)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->admissionVerificationService = $admissionVerificationService;
        $this->studentDataService = $studentDataService;

        //Set value for submit parameter
        $this->parentId = session('userData')->parent->id;
        $this->studentId = $this->studentDataService->findActiveStudentId($this->parentId);
        $this->detailStudent = $this->detailStudentQuery();
    }

    //HOOK - Execute once when component is rendered
    public function mount() {
        //Assign value for dropdown lists
        $this->provinceLists = Province::all()->toArray();
        $this->lastEducationLists = LastEducation::all()->toArray();
        $this->initJobSearch();
        $this->sallaryLists = Sallary::all()->toArray();

        //Assign value for detail student and bind it to propery
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
        $this->form->inputs['isParent'] = $this->detailStudent->is_parent;
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
        
        //Determine if user can edit biodata
        $this->isCanEdit = $this->isCanEditQuery();
    }

    //HOOK - Execute when property is changed
    public function updated($propertyName) {
        if ($propertyName == 'form.inputs.selectedProvinceId') {
            $this->regencyLists = Regency::where('province_id', $this->form->inputs['selectedProvinceId'])->get()->toArray();
        }

        if ($propertyName == 'form.inputs.selectedRegencyId') {
            $this->districtLists = District::where('regency_id', $this->form->inputs['selectedRegencyId'])->get()->toArray();
        }

        if ($propertyName == 'form.inputs.selectedDistrictId') {
            $this->villageLists = Village::where('district_id', $this->form->inputs['selectedDistrictId'])->get()->toArray();
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
    public function initJobSearch() {
        $this->jobLists = Job::get()->toArray();
    }

    //ACTION - Fetch student detail
    public function detailStudentQuery() {
        return StudentQuery::fetchStudentDetailWithStatus($this->studentId);
    }

    //ACTION - Check if user can edit biodata
    public function isCanEditQuery() {
        return $this->admissionVerificationService->isStudentCanEditBiodata($this->detailStudent->registration_payment, $this->detailStudent->biodata);
    }

    //ACTION - Process store student and parent data
    public function saveBiodata() {
        $parentId = Student::where('id', $this->studentId)->first()->parent_id;

        $this->form->save($parentId, $this->studentId);

        $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
        $this->detailStudentQuery();
        $this->isCanEditQuery();
        // $this->redirect(route('student.admission_data.biodata'), navigate: true);
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.web.student.admission-data.biodata')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }
        return view('livewire.web.student.admission-data.biodata')->layout('components.layouts.web.web-app');
    }
}
