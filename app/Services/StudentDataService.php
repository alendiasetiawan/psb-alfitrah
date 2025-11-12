<?php

namespace App\Services;

use App\Models\AdmissionData\MultiStudent;

class StudentDataService
{
    public function findActiveStudentId($parentId) {
        $data = MultiStudent::where('parent_id', $parentId)
        ->first();

        return $data->student_id;
    }
}