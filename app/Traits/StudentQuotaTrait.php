<?php

namespace App\Traits;

use App\Queries\Core\AdmissionQuotaQuery;
use App\Queries\AdmissionData\StudentQuery;

trait StudentQuotaTrait
{
    //Count how many student pass placement test
    public function countStudentPassPlacementTest($admissionId, $educationProgramId)
    {
        return StudentQuery::countStudentPassTest($admissionId, $educationProgramId);
    }

    //Get quota of selected admission and program
    public function programQuota($admissionId, $educationProgramId)
    {
        return AdmissionQuotaQuery::fetchQuotaAdmissionProgram($admissionId, $educationProgramId);
    }

    //Check if quota is available with early return if the program is closed
    public function isQuotaAvailable($admissionId, $educationProgramId) {
        $programQuota = $this->programQuota($admissionId, $educationProgramId);
        if ($programQuota->status == 'Tutup') {
            return false;
        }

        $totalStudent = $this->countStudentPassPlacementTest($admissionId, $educationProgramId);
        $quota = $this->programQuota($admissionId, $educationProgramId)->amount;
        if ($totalStudent >= $quota) {
            return false;
        }

        return true;
    }
}