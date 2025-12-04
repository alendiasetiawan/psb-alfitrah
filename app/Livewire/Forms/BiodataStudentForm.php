<?php

namespace App\Livewire\Forms;

use App\Enums\VerificationStatusEnum;
use App\Models\AdmissionData\AdmissionVerification;
use Livewire\Form;
use Illuminate\Support\Facades\DB;
use App\Models\AdmissionData\Student;
use App\Models\AdmissionData\ParentModel;

class BiodataStudentForm extends Form
{
    public array $inputs = [
        'studentName' => '',
        'gender' => '',
        'birthPlace' => '',
        'birthDate' => '',
        'countryCode' => '62',
        'mobilePhone' => '',
        'nisn' => '',
        'address' => '',
        'oldSchoolName' => '',
        'oldSchoolAddress' => '',
        'oldSchoolNpsn' => '',
        'selectedProvinceId' => '',
        'selectedRegencyId' => '',
        'selectedDistrictId' => '',
        'selectedVillageId' => '',
        'fatherName' => '',
        'fatherMobilePhone' => '',
        'fatherBirthPlace' => '',
        'fatherBirthDate' => '',
        'fatherAddress' => '',
        'fatherSelectedLastEducationId' => '',
        'fatherSelectedJobId' => '',
        'fatherSelectedSallaryId' => '',
        'motherName' => '',
        'motherMobilePhone' => '',
        'motherBirthPlace' => '',
        'motherBirthDate' => '',
        'motherAddress' => '',
        'motherSelectedLastEducationId' => '',
        'motherSelectedJobId' => '',
        'motherSelectedSallaryId' => '',
        'guardianName' => '',
        'guardianMobilePhone' => '',
        'guardianBirthPlace' => '',
        'guardianBirthDate' => '',
        'guardianAddress' => '',
        'guardianSelectedLastEducationId' => '',
        'guardianSelectedJobId' => '',
        'guardianSelectedSallaryId' => '',
        'isParent' => '',
    ];

    protected $rules = [
        'inputs.studentName' => [
            'required',
            'min:3'
        ],
        'inputs.gender' => 'required',
        'inputs.birthPlace' => [
            'required',
            'min:5'
        ],
        'inputs.birthDate' => 'required',
        'inputs.mobilePhone' => [
            'required',
            'min:7',
            'max:12'
        ],
        'inputs.nisn' => [
            'required',
            'min:10',
            'max:10'
        ],
        'inputs.address' => [
            'required',
            'min:20',
            'max:500'
        ],
        'inputs.oldSchoolName' => [
            'required',
            'min:5'
        ],
        'inputs.oldSchoolAddress' => [
            'required',
            'min:20',
            'max:500'
        ],
        'inputs.selectedProvinceId' => 'required',
        'inputs.selectedRegencyId' => 'required',
        'inputs.selectedDistrictId' => 'required',
        'inputs.selectedVillageId' => 'required',
        'inputs.isParent' => 'required'
    ];

    protected $messages = [
        'inputs.studentName.required' => 'Nama harus diisi',
        'inputs.studentName.min' => 'Nama minimal 3 karakter',
        'inputs.birthPlace.required' => 'Tempat lahir harus diisi',
        'inputs.birthPlace.min' => 'Tempat lahir minimal 5 karakter',
        'inputs.birthDate.required' => 'Tanggal lahir harus diisi',
        'inputs.mobilePhone.required' => 'Nomor telepon harus diisi',
        'inputs.mobilePhone.min' => 'Nomor telepon minimal 7 karakter',
        'inputs.mobilePhone.max' => 'Nomor telepon maksimal 12 karakter',
        'inputs.nisn.required' => 'NISN harus diisi',
        'inputs.nisn.min' => 'NISN minimal 10 karakter',
        'inputs.nisn.max' => 'NISN maksimal 10 karakter',
        'inputs.address.required' => 'Alamat harus diisi',
        'inputs.address.min' => 'Alamat minimal 20 karakter',
        'inputs.address.max' => 'Alamat maksimal 500 karakter',
        'inputs.oldSchoolName.required' => 'Asal sekolah harus diisi',
        'inputs.oldSchoolName.min' => 'Asal sekolah minimal 5 karakter',
        'inputs.oldSchoolAddress.required' => 'Alamat asal sekolah harus diisi',
        'inputs.oldSchoolAddress.min' => 'Alamat asal sekolah minimal 20 karakter',
        'inputs.oldSchoolAddress.max' => 'Alamat asal sekolah maksimal 500 karakter',
        'inputs.selectedProvinceId.required' => 'Provinsi harus diisi',
        'inputs.selectedRegencyId.required' => 'Kota harus diisi',
        'inputs.selectedDistrictId.required' => 'Kecamatan harus diisi',
        'inputs.selectedVillageId.required' => 'Kelurahan harus diisi',
        'inputs.isParent.required' => 'Status orang tua harus diisi',
    ];

    //ACTION - Save student and parent data
    public function save($parentId, $studentId)
    {
        $this->validate();

        if ($this->inputs['isParent'] == 1) {
            $dataParent = [
                'is_parent' => $this->inputs['isParent'],
                'father_name' => $this->inputs['fatherName'],
                'father_birth_place' => $this->inputs['fatherBirthPlace'],
                'father_birth_date' => $this->inputs['fatherBirthDate'],
                'father_address' => $this->inputs['fatherAddress'],
                'father_country_code' => '62',
                'father_mobile_phone' => $this->inputs['fatherMobilePhone'],
                'father_last_education_id' => $this->inputs['fatherSelectedLastEducationId'],
                'father_job_id' => $this->inputs['fatherSelectedJobId'],
                'father_sallary_id' => $this->inputs['fatherSelectedSallaryId'],
                'mother_name' => $this->inputs['motherName'],
                'mother_birth_place' => $this->inputs['motherBirthPlace'],
                'mother_birth_date' => $this->inputs['motherBirthDate'],
                'mother_address' => $this->inputs['motherAddress'],
                'mother_country_code' => '62',
                'mother_mobile_phone' => $this->inputs['motherMobilePhone'],
                'mother_last_education_id' => $this->inputs['motherSelectedLastEducationId'],
                'mother_job_id' => $this->inputs['motherSelectedJobId'],
                'mother_sallary_id' => $this->inputs['motherSelectedSallaryId'],
            ];
        } else {
            $dataParent = [
                'is_parent' => $this->inputs['isParent'],
                'guardian_name' => $this->inputs['guardianName'],
                'guardian_birth_place' => $this->inputs['guardianBirthPlace'],
                'guardian_birth_date' => $this->inputs['guardianBirthDate'],
                'guardian_address' => $this->inputs['guardianAddress'],
                'guardian_country_code' => '62',
                'guardian_mobile_phone' => $this->inputs['guardianMobilePhone'],
                'guardian_last_education_id' => $this->inputs['guardianSelectedLastEducationId'],
                'guardian_job_id' => $this->inputs['guardianSelectedJobId'],
                'guardian_sallary_id' => $this->inputs['guardianSelectedSallaryId'],
            ];
        }

        DB::transaction(function () use ($parentId, $studentId, $dataParent) {
            //Save parent data
            $parentModel = ParentModel::where('user_id', session('userData')->id)->first();
            $parentModel->update($dataParent);

            //Update Student Data
            $student = Student::find($studentId);
            $student->update([
                'parent_id' => $parentId,
                'province_id' => $this->inputs['selectedProvinceId'],
                'regency_id' => $this->inputs['selectedRegencyId'],
                'district_id' => $this->inputs['selectedDistrictId'],
                'village_id' => $this->inputs['selectedVillageId'],
                'name' => $this->inputs['studentName'],
                'gender' => $this->inputs['gender'],
                'birth_place' => $this->inputs['birthPlace'],
                'birth_date' => $this->inputs['birthDate'],
                'address' => $this->inputs['address'],
                'nisn' =>  $this->inputs['nisn'],
                'old_school_name' => $this->inputs['oldSchoolName'],
                'old_school_address' => $this->inputs['oldSchoolAddress'],
                'npsn' => $this->inputs['oldSchoolNpsn'],
            ]);

            //Update admission verification data
            $verification = AdmissionVerification::where('student_id', $studentId)->first();
            if ($verification) {
                $verification->update([
                    'biodata' => VerificationStatusEnum::PROCESS
                ]);
            }
        });
    }
}
