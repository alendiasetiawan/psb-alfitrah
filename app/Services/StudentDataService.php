<?php

namespace App\Services;

use App\Models\AdmissionData\MultiStudent;
use App\Models\AdmissionData\Student;

class StudentDataService
{
    public function findActiveStudentId($parentId)
    {
        $data = MultiStudent::where('parent_id', $parentId)
            ->first();

        return $data->student_id;
    }

    public function isMultiStudent($userId)
    {
        $countStudent = Student::where('user_id', $userId)
            ->count();

        return $countStudent > 1 ? true : false;
    }
}
